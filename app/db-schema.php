<?php

declare(strict_types=1);

function whois_db_initialize($database): void
{
    whois_db_execute(<<<'SQL'
        CREATE TABLE IF NOT EXISTS auction_submissions (
            id BIGSERIAL PRIMARY KEY,
            domain_name TEXT NOT NULL,
            category TEXT,
            keywords TEXT,
            reserve_price NUMERIC(12,2),
            bin_price NUMERIC(12,2),
            starting_bid NUMERIC(12,2),
            auction_type TEXT,
            duration_days INTEGER,
            start_date DATE,
            status TEXT NOT NULL DEFAULT 'new',
            source_page TEXT,
            created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
            updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
        )
    SQL);

    whois_db_execute(<<<'SQL'
        CREATE TABLE IF NOT EXISTS marketplace_items (
            id BIGSERIAL PRIMARY KEY,
            domain_name TEXT NOT NULL UNIQUE,
            extension TEXT NOT NULL,
            price NUMERIC(12,2) NOT NULL,
            appraisal_price NUMERIC(12,2),
            listing_type TEXT NOT NULL DEFAULT 'row',
            badge_text TEXT,
            categories TEXT,
            icon_name TEXT,
            search_text TEXT,
            sort_order INTEGER NOT NULL DEFAULT 0,
            source_submission_id BIGINT,
            status TEXT NOT NULL DEFAULT 'live',
            created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
            updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
        )
    SQL);

    whois_db_execute("ALTER TABLE marketplace_items ADD COLUMN IF NOT EXISTS source_submission_id BIGINT");
    whois_db_execute("CREATE INDEX IF NOT EXISTS auction_submissions_status_created_idx ON auction_submissions (status, created_at DESC)");
    whois_db_execute("CREATE INDEX IF NOT EXISTS marketplace_items_status_sort_idx ON marketplace_items (status, sort_order DESC, created_at DESC)");
    whois_db_execute("CREATE UNIQUE INDEX IF NOT EXISTS marketplace_items_source_submission_id_idx ON marketplace_items (source_submission_id) WHERE source_submission_id IS NOT NULL");

    whois_db_seed_marketplace_items($database);
}

function whois_db_seed_marketplace_items($database): void
{
    $countRow = whois_db_fetch_one('SELECT COUNT(*) AS count FROM marketplace_items');
    $count = (int) ($countRow['count'] ?? 0);

    if ($count > 0) {
        return;
    }

    $items = [
        [
            'domain_name' => 'neural.ai',
            'extension' => 'ai',
            'price' => 18900,
            'appraisal_price' => 24500,
            'listing_type' => 'featured',
            'badge_text' => 'Available Now',
            'categories' => 'Premium, Machine Intelligence, Automation',
            'icon_name' => 'star',
            'search_text' => 'neural ai premium available appraisal machine intelligence automation',
            'sort_order' => 100,
        ],
        [
            'domain_name' => 'flow.io',
            'extension' => 'io',
            'price' => 9500,
            'appraisal_price' => 12000,
            'listing_type' => 'featured',
            'badge_text' => 'New Arrival',
            'categories' => 'Product Design, Workflow',
            'icon_name' => 'bolt',
            'search_text' => 'flow io new arrival appraisal product design workflow',
            'sort_order' => 90,
        ],
        [
            'domain_name' => 'cryptopulse.com',
            'extension' => 'com',
            'price' => 6500,
            'appraisal_price' => 8400,
            'listing_type' => 'row',
            'badge_text' => 'Indexed',
            'categories' => 'Fintech, Blockchain, Analysis',
            'icon_name' => 'globe',
            'search_text' => 'cryptopulse com fintech blockchain analysis appraisal price buy now',
            'sort_order' => 80,
        ],
        [
            'domain_name' => 'zenith.ai',
            'extension' => 'ai',
            'price' => 28000,
            'appraisal_price' => 32000,
            'listing_type' => 'row',
            'badge_text' => 'Priority',
            'categories' => 'Intelligence, SaaS, Enterprise',
            'icon_name' => 'bolt',
            'search_text' => 'zenith ai intelligence saas enterprise appraisal price buy now',
            'sort_order' => 70,
        ],
        [
            'domain_name' => 'shoply.net',
            'extension' => 'net',
            'price' => 3200,
            'appraisal_price' => 4500,
            'listing_type' => 'row',
            'badge_text' => 'Trending',
            'categories' => 'Retail, Marketplace, Logistics',
            'icon_name' => 'shopping_cart',
            'search_text' => 'shoply net retail marketplace logistics appraisal price buy now',
            'sort_order' => 60,
        ],
        [
            'domain_name' => 'nexus.sh',
            'extension' => 'sh',
            'price' => 999,
            'appraisal_price' => 1200,
            'listing_type' => 'row',
            'badge_text' => 'Low Entry',
            'categories' => 'Developer Tools, Infrastructure',
            'icon_name' => 'cloud',
            'search_text' => 'nexus sh developer tools infrastructure appraisal price buy now',
            'sort_order' => 50,
        ],
    ];

    foreach ($items as $item) {
        whois_db_execute(<<<'SQL'
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
                status
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
                'live'
            ) ON CONFLICT (domain_name) DO NOTHING
        SQL, $item);
    }
}