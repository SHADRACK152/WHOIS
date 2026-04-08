<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

final class WhoisPgConnection
{
    private $stream;
    private bool $inTransaction = false;
    private string $user;
    private string $password;
    private string $database;

    private function __construct($stream, string $user, string $password, string $database)
    {
        $this->stream = $stream;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
    }

    public static function connect(string $url): self
    {
        $parts = parse_url($url);

        if ($parts === false) {
            throw new RuntimeException('Invalid PostgreSQL connection string.');
        }

        $scheme = strtolower((string) ($parts['scheme'] ?? ''));

        if (!in_array($scheme, ['postgresql', 'postgres', 'pgsql'], true)) {
            throw new RuntimeException('Unsupported database scheme.');
        }

        $host = (string) ($parts['host'] ?? 'localhost');
        $port = (int) ($parts['port'] ?? 5432);
        $database = ltrim((string) ($parts['path'] ?? ''), '/');
        $user = rawurldecode((string) ($parts['user'] ?? ''));
        $password = rawurldecode((string) ($parts['pass'] ?? ''));

        if ($host === '' || $database === '' || $user === '') {
            throw new RuntimeException('The PostgreSQL connection string is missing required parts.');
        }

        $query = [];
        if (!empty($parts['query'])) {
            parse_str((string) $parts['query'], $query);
        }

        $timeout = (float) (getenv('NEON_TIMEOUT') ?: 20);
        $insecure = (getenv('NEON_INSECURE_SSL') === '1');

        $contextOptions = [
            'ssl' => [
                'verify_peer' => !$insecure,
                'verify_peer_name' => !$insecure,
                'allow_self_signed' => $insecure,
                'peer_name' => $host,
            ],
        ];

        $context = stream_context_create($contextOptions);
        $stream = stream_socket_client(
            'tcp://' . $host . ':' . $port,
            $errno,
            $errstr,
            $timeout,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!is_resource($stream)) {
            throw new RuntimeException('Unable to connect to the PostgreSQL host: ' . $errstr . ' (' . $errno . ')');
        }

        stream_set_timeout($stream, (int) ceil($timeout));

        self::negotiateSsl($stream);

        $connection = new self($stream, $user, $password, $database);
        $connection->startup();

        return $connection;
    }

    public function beginTransaction(): void
    {
        $this->execute('BEGIN');
        $this->inTransaction = true;
    }

    public function commit(): void
    {
        $this->execute('COMMIT');
        $this->inTransaction = false;
    }

    public function rollBack(): void
    {
        $this->execute('ROLLBACK');
        $this->inTransaction = false;
    }

    public function inTransaction(): bool
    {
        return $this->inTransaction;
    }

    public function fetchAll(string $sql): array
    {
        [$rows] = $this->runQuery($sql);

        return $rows;
    }

    public function fetchOne(string $sql): ?array
    {
        $rows = $this->fetchAll($sql);

        if ($rows === []) {
            return null;
        }

        return $rows[0];
    }

    public function execute(string $sql): int
    {
        [, $commandTag] = $this->runQuery($sql);

        return self::extractAffectedRows($commandTag);
    }

    private function startup(): void
    {
        $payload = pack('N', 196608);
        $payload .= 'user' . "\0" . $this->user . "\0";
        $payload .= 'database' . "\0" . $this->database . "\0";
        $payload .= 'client_encoding' . "\0" . 'UTF8' . "\0";
        $payload .= "\0";

        $this->writePacket($payload);

        $scramState = [
            'client_first_bare' => '',
            'client_nonce' => '',
            'server_first' => '',
        ];

        while (true) {
            [$type, $body] = $this->readMessage();

            if ($type === 'R') {
                $authCode = self::readInt32($body, 0);

                if ($authCode === 0) {
                    continue;
                }

                if ($authCode === 3) {
                    $this->sendPasswordMessage($this->password);
                    continue;
                }

                if ($authCode === 5) {
                    $salt = substr($body, 4, 4);
                    $this->sendMd5PasswordMessage($salt);
                    continue;
                }

                if ($authCode === 10) {
                    if (strpos($body, 'SCRAM-SHA-256') === false) {
                        throw new RuntimeException('The server requested an unsupported authentication mechanism.');
                    }

                    $scramState = $this->sendScramInitialResponse();
                    continue;
                }

                if ($authCode === 11) {
                    $scramState['server_first'] = substr($body, 4);
                    $this->sendScramFinalResponse($scramState);
                    continue;
                }

                if ($authCode === 12) {
                    continue;
                }

                throw new RuntimeException('Unsupported PostgreSQL authentication request: ' . $authCode);
            }

            if ($type === 'E') {
                throw new RuntimeException(self::parseErrorMessage($body));
            }

            if ($type === 'Z') {
                return;
            }
        }
    }

    private function sendPasswordMessage(string $password): void
    {
        $this->sendMessage('p', $password . "\0");
    }

    private function sendMd5PasswordMessage(string $salt): void
    {
        $firstHash = md5($this->password . $this->user);
        $secondHash = md5($firstHash . $salt);

        $this->sendMessage('p', 'md5' . $secondHash . "\0");
    }

    private function sendScramInitialResponse(): array
    {
        $nonce = bin2hex(random_bytes(18));
        $clientFirstBare = 'n=' . self::scramEscape($this->user) . ',r=' . $nonce;
        $clientFirstMessage = 'n,,' . $clientFirstBare;

        $payload = 'SCRAM-SHA-256' . "\0" . pack('N', strlen($clientFirstMessage)) . $clientFirstMessage;
        $this->sendMessage('p', $payload);

        return [
            'client_first_bare' => $clientFirstBare,
            'client_nonce' => $nonce,
            'server_first' => '',
        ];
    }

    private function sendScramFinalResponse(array $state): void
    {
        $serverFirst = (string) $state['server_first'];
        $serverAttributes = self::parseScramAttributes($serverFirst);
        $salt = (string) ($serverAttributes['s'] ?? '');
        $iterations = (int) ($serverAttributes['i'] ?? 0);
        $serverNonce = (string) ($serverAttributes['r'] ?? '');

        if ($salt === '' || $iterations <= 0 || $serverNonce === '') {
            throw new RuntimeException('Invalid SCRAM challenge from PostgreSQL.');
        }

        $clientFinalWithoutProof = 'c=biws,r=' . $serverNonce;
        $authMessage = (string) $state['client_first_bare'] . ',' . $serverFirst . ',' . $clientFinalWithoutProof;
        $saltedPassword = hash_pbkdf2('sha256', $this->password, base64_decode($salt, true) ?: '', $iterations, 32, true);
        $clientKey = hash_hmac('sha256', 'Client Key', $saltedPassword, true);
        $storedKey = hash('sha256', $clientKey, true);
        $clientSignature = hash_hmac('sha256', $authMessage, $storedKey, true);
        $clientProof = self::xorBytes($clientKey, $clientSignature);
        $finalMessage = $clientFinalWithoutProof . ',p=' . base64_encode($clientProof);

        $this->sendMessage('p', $finalMessage);
    }

    private function runQuery(string $sql): array
    {
        $this->sendMessage('Q', $sql . "\0");

        $columns = [];
        $rows = [];
        $commandTag = '';

        while (true) {
            [$type, $body] = $this->readMessage();

            if ($type === 'T') {
                $columns = self::parseRowDescription($body);
                continue;
            }

            if ($type === 'D') {
                $rows[] = self::parseDataRow($body, $columns);
                continue;
            }

            if ($type === 'C') {
                $commandTag = rtrim($body, "\0");
                continue;
            }

            if ($type === 'E') {
                throw new RuntimeException(self::parseErrorMessage($body));
            }

            if ($type === 'Z') {
                return [$rows, $commandTag];
            }
        }
    }

    private function sendMessage(string $type, string $payload): void
    {
        $packet = $type . pack('N', strlen($payload) + 4) . $payload;
        $written = fwrite($this->stream, $packet);

        if ($written === false || $written < strlen($packet)) {
            throw new RuntimeException('Failed to write to the PostgreSQL connection.');
        }
    }

    private function writePacket(string $payload): void
    {
        $packet = pack('N', strlen($payload) + 4) . $payload;
        $written = fwrite($this->stream, $packet);

        if ($written === false || $written < strlen($packet)) {
            throw new RuntimeException('Failed to write the startup packet to PostgreSQL.');
        }
    }

    private function readMessage(): array
    {
        $type = $this->readBytes(1);
        $length = self::readInt32($this->readBytes(4), 0);
        $payload = $this->readBytes($length - 4);

        return [$type, $payload];
    }

    private function readBytes(int $length): string
    {
        $buffer = '';

        while (strlen($buffer) < $length) {
            $chunk = fread($this->stream, $length - strlen($buffer));

            if ($chunk === false || $chunk === '') {
                $meta = stream_get_meta_data($this->stream);

                if (!empty($meta['timed_out'])) {
                    throw new RuntimeException('Timed out reading from the PostgreSQL connection.');
                }

                throw new RuntimeException('Unexpected end of stream from the PostgreSQL connection.');
            }

            $buffer .= $chunk;
        }

        return $buffer;
    }

    private static function parseRowDescription(string $payload): array
    {
        $count = self::readInt16($payload, 0);
        $offset = 2;
        $columns = [];

        for ($index = 0; $index < $count; $index++) {
            $nameEnd = strpos($payload, "\0", $offset);

            if ($nameEnd === false) {
                break;
            }

            $columns[] = substr($payload, $offset, $nameEnd - $offset);
            $offset = $nameEnd + 1 + 18;
        }

        return $columns;
    }

    private static function parseDataRow(string $payload, array $columns): array
    {
        $count = self::readInt16($payload, 0);
        $offset = 2;
        $row = [];

        for ($index = 0; $index < $count; $index++) {
            $length = self::readInt32($payload, $offset);
            $offset += 4;

            if ($length === 4294967295) {
                $row[$columns[$index] ?? (string) $index] = null;
                continue;
            }

            $value = substr($payload, $offset, $length);
            $offset += $length;
            $row[$columns[$index] ?? (string) $index] = $value;
        }

        return $row;
    }

    private static function parseErrorMessage(string $payload): string
    {
        $offset = 0;
        $messages = [];

        while ($offset < strlen($payload)) {
            $fieldType = $payload[$offset];
            $offset++;

            if ($fieldType === "\0") {
                break;
            }

            $fieldEnd = strpos($payload, "\0", $offset);

            if ($fieldEnd === false) {
                break;
            }

            $fieldValue = substr($payload, $offset, $fieldEnd - $offset);
            $offset = $fieldEnd + 1;

            if ($fieldType === 'M') {
                $messages[] = $fieldValue;
            }
        }

        return $messages !== [] ? implode(' ', $messages) : 'PostgreSQL returned an error.';
    }

    private static function extractAffectedRows(string $commandTag): int
    {
        if (preg_match('/(\d+)$/', $commandTag, $matches) !== 1) {
            return 0;
        }

        return (int) $matches[1];
    }

    private static function negotiateSsl($stream): void
    {
        $packet = pack('N2', 8, 80877103);
        $written = fwrite($stream, $packet);

        if ($written === false || $written < strlen($packet)) {
            throw new RuntimeException('Failed to request SSL negotiation.');
        }

        $response = fread($stream, 1);

        if ($response !== 'S') {
            throw new RuntimeException('The PostgreSQL server rejected SSL negotiation.');
        }

        $cryptoMethod = defined('STREAM_CRYPTO_METHOD_TLS_CLIENT') ? STREAM_CRYPTO_METHOD_TLS_CLIENT : STREAM_CRYPTO_METHOD_ANY_CLIENT;

        if (stream_socket_enable_crypto($stream, true, $cryptoMethod) !== true) {
            throw new RuntimeException('Unable to enable TLS for the PostgreSQL connection.');
        }
    }

    private static function readInt16(string $buffer, int $offset): int
    {
        return unpack('n', substr($buffer, $offset, 2))[1];
    }

    private static function readInt32(string $buffer, int $offset): int
    {
        return unpack('N', substr($buffer, $offset, 4))[1];
    }

    private static function scramEscape(string $value): string
    {
        return strtr($value, [
            '=' => '=3D',
            ',' => '=2C',
        ]);
    }

    private static function parseScramAttributes(string $message): array
    {
        $attributes = [];

        foreach (explode(',', $message) as $pair) {
            $equals = strpos($pair, '=');

            if ($equals === false) {
                continue;
            }

            $attributes[substr($pair, 0, $equals)] = substr($pair, $equals + 1);
        }

        return $attributes;
    }

    private static function xorBytes(string $left, string $right): string
    {
        $result = '';
        $length = min(strlen($left), strlen($right));

        for ($index = 0; $index < $length; $index++) {
            $result .= $left[$index] ^ $right[$index];
        }

        return $result;
    }
}

function whois_db_connection_url(): ?string
{
    foreach (['NEON_DATABASE_URL', 'DATABASE_URL'] as $name) {
        $value = getenv($name);

        if (is_string($value) && trim($value) !== '') {
            return trim($value);
        }
    }

    return null;
}

function whois_db_connection(): ?WhoisPgConnection
{
    static $connection = null;
    static $attempted = false;

    if ($attempted) {
        return $connection;
    }

    $attempted = true;

    $url = whois_db_connection_url();

    if ($url === null) {
        return null;
    }

    try {
        $connection = WhoisPgConnection::connect($url);
        require_once __DIR__ . '/db-schema.php';
        whois_db_initialize($connection);
        return $connection;
    } catch (Throwable $exception) {
        return null;
    }
}

function whois_db_is_available(): bool
{
    return whois_db_connection() instanceof WhoisPgConnection;
}

function whois_db_quote_value(mixed $value): string
{
    if ($value === null) {
        return 'NULL';
    }

    if (is_bool($value)) {
        return $value ? 'TRUE' : 'FALSE';
    }

    if (is_int($value)) {
        return (string) $value;
    }

    if (is_float($value)) {
        $formatted = rtrim(rtrim(sprintf('%.15F', $value), '0'), '.');

        return $formatted === '' ? '0' : $formatted;
    }

    if (is_array($value) || is_object($value)) {
        $value = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    $string = str_replace("'", "''", (string) $value);

    return "'" . $string . "'";
}

function whois_db_interpolate(string $sql, array $params): string
{
    if ($params === []) {
        return $sql;
    }

    $replacements = [];

    foreach ($params as $key => $value) {
        $replacements[':' . ltrim((string) $key, ':')] = whois_db_quote_value($value);
    }

    return strtr($sql, $replacements);
}

function whois_db_fetch_all(string $sql, array $params = []): array
{
    $connection = whois_db_connection();

    if (!$connection) {
        return [];
    }

    return $connection->fetchAll(whois_db_interpolate($sql, $params));
}

function whois_db_fetch_one(string $sql, array $params = []): ?array
{
    $rows = whois_db_fetch_all($sql, $params);

    if ($rows === []) {
        return null;
    }

    return $rows[0];
}

function whois_db_execute(string $sql, array $params = []): int
{
    $connection = whois_db_connection();

    if (!$connection) {
        throw new RuntimeException('Database connection is not available.');
    }

    return $connection->execute(whois_db_interpolate($sql, $params));
}

function whois_db_list_submissions(array $filters = []): array
{
    $sql = 'SELECT * FROM auction_submissions WHERE 1 = 1';
    $params = [];

    if (!empty($filters['status'])) {
        $sql .= ' AND status = :status';
        $params['status'] = strtolower(trim((string) $filters['status']));
    }

    if (!empty($filters['domain_name'])) {
        $sql .= ' AND domain_name ILIKE :domain_name';
        $params['domain_name'] = '%' . trim((string) $filters['domain_name']) . '%';
    }

    $sql .= ' ORDER BY created_at DESC';

    if (isset($filters['limit'])) {
        $sql .= ' LIMIT ' . max(1, min(200, (int) $filters['limit']));
    }

    return whois_db_fetch_all($sql, $params);
}

function whois_db_get_submission(int $submissionId): ?array
{
    return whois_db_fetch_one('SELECT * FROM auction_submissions WHERE id = :id', ['id' => $submissionId]);
}

function whois_db_upsert_marketplace_item_from_submission(array $submission): ?array
{
    $domainName = strtolower(trim((string) ($submission['domain_name'] ?? '')));

    if ($domainName === '') {
        return null;
    }

    $extension = '';
    $dotPosition = strrpos($domainName, '.');

    if ($dotPosition !== false) {
        $extension = substr($domainName, $dotPosition + 1);
    }

    $reservePrice = (float) ($submission['reserve_price'] ?? 0);
    $binPrice = (float) ($submission['bin_price'] ?? 0);
    $startingBid = (float) ($submission['starting_bid'] ?? 0);
    $price = $binPrice > 0 ? $binPrice : ($reservePrice > 0 ? $reservePrice : $startingBid);
    $appraisalPrice = $reservePrice > 0 ? $reservePrice : ($binPrice > 0 ? $binPrice : $startingBid);
    $isPremium = $price >= 1500 || $appraisalPrice >= 1500;
    $categories = trim(implode(', ', array_filter([
        trim((string) ($submission['category'] ?? '')),
        trim((string) ($submission['keywords'] ?? '')),
    ])));
    $searchText = trim(implode(' ', array_filter([
        $domainName,
        (string) ($submission['category'] ?? ''),
        (string) ($submission['keywords'] ?? ''),
        (string) ($submission['auction_type'] ?? ''),
    ])));

    return whois_db_fetch_one(<<<'SQL'
        INSERT INTO marketplace_items (
            domain_name,
            extension,
            price,
            appraisal_price,
            listing_type,
            badge_text,
            categories,
            icon_name,
            search_text,
            sort_order,
            source_submission_id,
            is_premium,
            status,
            created_at,
            updated_at
        ) VALUES (
            :domain_name,
            :extension,
            :price,
            :appraisal_price,
            :listing_type,
            :badge_text,
            :categories,
            :icon_name,
            :search_text,
            :sort_order,
            :source_submission_id,
            :is_premium,
            :status,
            NOW(),
            NOW()
        )
        ON CONFLICT (domain_name) DO UPDATE SET
            extension = EXCLUDED.extension,
            price = EXCLUDED.price,
            appraisal_price = EXCLUDED.appraisal_price,
            listing_type = EXCLUDED.listing_type,
            badge_text = EXCLUDED.badge_text,
            categories = EXCLUDED.categories,
            icon_name = EXCLUDED.icon_name,
            search_text = EXCLUDED.search_text,
            sort_order = EXCLUDED.sort_order,
            source_submission_id = EXCLUDED.source_submission_id,
            is_premium = EXCLUDED.is_premium,
            status = EXCLUDED.status,
            updated_at = NOW()
        RETURNING *
    SQL, [
        'domain_name' => $domainName,
        'extension' => $extension !== '' ? $extension : 'com',
        'price' => $price > 0 ? $price : 0,
        'appraisal_price' => $appraisalPrice > 0 ? $appraisalPrice : 0,
        'listing_type' => 'row',
        'badge_text' => $isPremium ? 'Premium' : 'Approved',
        'categories' => $categories,
        'icon_name' => 'gavel',
        'search_text' => trim($searchText . ($isPremium ? ' premium' : '')),
        'sort_order' => 85,
        'source_submission_id' => (int) ($submission['id'] ?? 0),
        'is_premium' => $isPremium,
        'status' => 'live',
    ]);
}

function whois_db_get_marketplace_item_by_domain(string $domainName): ?array
{
    $domainName = strtolower(trim($domainName));

    if ($domainName === '') {
        return null;
    }

    return whois_db_fetch_one('SELECT * FROM marketplace_items WHERE domain_name = :domain_name', [
        'domain_name' => $domainName,
    ]);
}

function whois_db_search_text_with_premium_flag(string $searchText, bool $isPremium): string
{
    $normalized = preg_replace('/\s+/', ' ', trim($searchText)) ?? '';
    $withoutPremium = trim((string) preg_replace('/\bpremium\b/i', '', $normalized));
    $withoutPremium = preg_replace('/\s+/', ' ', $withoutPremium) ?? '';

    if (!$isPremium) {
        return trim($withoutPremium);
    }

    return trim($withoutPremium . ' premium');
}

function whois_db_record_marketplace_bid(array $bid): ?array
{
    $domainName = strtolower(trim((string) ($bid['domain_name'] ?? '')));
    $bidAmount = (float) ($bid['bid_amount'] ?? 0);

    if ($domainName === '' || $bidAmount <= 0) {
        return null;
    }

    $item = whois_db_get_marketplace_item_by_domain($domainName);

    if (!$item || strtolower((string) ($item['status'] ?? 'live')) !== 'live') {
        return null;
    }

    $bidRow = whois_db_fetch_one(<<<'SQL'
        INSERT INTO marketplace_bids (
            marketplace_item_id,
            domain_name,
            bidder_name,
            bidder_email,
            bid_amount,
            note
        ) VALUES (
            :marketplace_item_id,
            :domain_name,
            :bidder_name,
            :bidder_email,
            :bid_amount,
            :note
        )
        RETURNING *
    SQL, [
        'marketplace_item_id' => (int) ($item['id'] ?? 0),
        'domain_name' => $domainName,
        'bidder_name' => trim((string) ($bid['bidder_name'] ?? '')),
        'bidder_email' => trim((string) ($bid['bidder_email'] ?? '')),
        'bid_amount' => $bidAmount,
        'note' => trim((string) ($bid['note'] ?? '')),
    ]);

    if (!$bidRow) {
        return null;
    }

    $isPremium = $bidAmount >= 1500 || (bool) ($item['is_premium'] ?? false);
    $badgeText = $isPremium ? 'Premium' : 'Available';
    $searchText = whois_db_search_text_with_premium_flag((string) ($item['search_text'] ?? ''), $isPremium);

    $updatedItem = whois_db_fetch_one(<<<'SQL'
        UPDATE marketplace_items
        SET price = :price,
            appraisal_price = :appraisal_price,
            badge_text = :badge_text,
            is_premium = :is_premium,
            search_text = :search_text,
            updated_at = NOW()
        WHERE id = :id
        RETURNING *
    SQL, [
        'price' => $bidAmount,
        'appraisal_price' => max((float) ($item['appraisal_price'] ?? 0), $bidAmount),
        'badge_text' => $badgeText,
        'is_premium' => $isPremium,
        'search_text' => $searchText,
        'id' => (int) ($item['id'] ?? 0),
    ]);

    return [
        'bid' => $bidRow,
        'item' => $updatedItem,
    ];
}

function whois_db_list_marketplace_bids(string $domainName, int $limit = 200): array
{
    $domainName = strtolower(trim($domainName));

    if ($domainName === '') {
        return [];
    }

    $safeLimit = max(1, min(500, $limit));

    return whois_db_fetch_all(
        'SELECT * FROM marketplace_bids WHERE domain_name = :domain_name ORDER BY created_at DESC LIMIT ' . $safeLimit,
        ['domain_name' => $domainName]
    );
}

function whois_db_mark_marketplace_item_sold(int $itemId, array $saleDetails = []): ?array
{
    if ($itemId <= 0) {
        return null;
    }

    $soldPrice = (float) ($saleDetails['sold_price'] ?? 0);
    $buyerName = trim((string) ($saleDetails['buyer_name'] ?? ''));
    $buyerEmail = trim((string) ($saleDetails['buyer_email'] ?? ''));
    $isPremium = $soldPrice > 1500;

    return whois_db_fetch_one(<<<'SQL'
        UPDATE marketplace_items
        SET status = 'sold',
            price = :price,
            sold_price = :sold_price,
            sold_buyer_name = :sold_buyer_name,
            sold_buyer_email = :sold_buyer_email,
            is_premium = :is_premium,
            badge_text = :badge_text,
            search_text = :search_text,
            sold_at = NOW(),
            updated_at = NOW()
        WHERE id = :id
        RETURNING *
    SQL, [
        'id' => $itemId,
        'price' => $soldPrice,
        'sold_price' => $soldPrice,
        'sold_buyer_name' => $buyerName,
        'sold_buyer_email' => $buyerEmail,
        'is_premium' => $isPremium,
        'badge_text' => $isPremium ? 'Premium' : 'Sold',
        'search_text' => whois_db_search_text_with_premium_flag((string) ($saleDetails['search_text'] ?? ''), $isPremium),
    ]);
}

function whois_db_approve_submission(int $submissionId): ?array
{
    $connection = whois_db_connection();

    if (!$connection) {
        return null;
    }

    $connection->beginTransaction();

    try {
        $submission = whois_db_fetch_one('SELECT * FROM auction_submissions WHERE id = :id FOR UPDATE', ['id' => $submissionId]);

        if (!$submission) {
            $connection->rollBack();
            return null;
        }

        whois_db_execute('UPDATE auction_submissions SET status = :status, updated_at = NOW() WHERE id = :id', [
            'status' => 'approved',
            'id' => $submissionId,
        ]);

        $listing = whois_db_upsert_marketplace_item_from_submission($submission);

        $connection->commit();

        return [
            'submission' => array_merge($submission, ['status' => 'approved']),
            'listing' => $listing,
        ];
    } catch (Throwable $exception) {
        if ($connection->inTransaction()) {
            $connection->rollBack();
        }

        throw $exception;
    }
}

function whois_db_reject_submission(int $submissionId): ?array
{
    return whois_db_fetch_one('UPDATE auction_submissions SET status = :status, updated_at = NOW() WHERE id = :id RETURNING *', [
        'status' => 'rejected',
        'id' => $submissionId,
    ]);
}

function whois_db_save_submission(array $submission): ?array
{
    return whois_db_fetch_one(<<<'SQL'
        INSERT INTO auction_submissions (
            domain_name,
            category,
            keywords,
            reserve_price,
            bin_price,
            starting_bid,
            auction_type,
            duration_days,
            start_date,
            status,
            source_page
        ) VALUES (
            :domain_name,
            :category,
            :keywords,
            :reserve_price,
            :bin_price,
            :starting_bid,
            :auction_type,
            :duration_days,
            :start_date,
            :status,
            :source_page
        )
        RETURNING *
    SQL, [
        'domain_name' => $submission['domain_name'] ?? '',
        'category' => $submission['category'] ?? null,
        'keywords' => $submission['keywords'] ?? null,
        'reserve_price' => $submission['reserve_price'] !== '' ? $submission['reserve_price'] : null,
        'bin_price' => $submission['bin_price'] !== '' ? $submission['bin_price'] : null,
        'starting_bid' => $submission['starting_bid'] !== '' ? $submission['starting_bid'] : null,
        'auction_type' => $submission['auction_type'] ?? null,
        'duration_days' => $submission['duration_days'] !== '' ? (int) $submission['duration_days'] : null,
        'start_date' => $submission['start_date'] ?? null,
        'status' => 'new',
        'source_page' => $submission['source_page'] ?? 'submit-domain-page',
    ]);
}

function whois_db_list_marketplace_items(array $filters = []): array
{
    $sql = 'SELECT * FROM marketplace_items WHERE status = :status';
    $params = ['status' => $filters['status'] ?? 'live'];

    if (!empty($filters['extension'])) {
        $sql .= ' AND extension = :extension';
        $params['extension'] = strtolower(trim((string) $filters['extension']));
    }

    if (array_key_exists('max_price', $filters) && $filters['max_price'] !== null && $filters['max_price'] !== '') {
        $sql .= ' AND price <= :max_price';
        $params['max_price'] = (float) $filters['max_price'];
    }

    if (!empty($filters['query'])) {
        $sql .= ' AND (domain_name ILIKE :query OR categories ILIKE :query OR search_text ILIKE :query)';
        $params['query'] = '%' . trim((string) $filters['query']) . '%';
    }

    $sql .= ' ORDER BY sort_order DESC, created_at DESC';

    return whois_db_fetch_all($sql, $params);
}

whois_db_connection();