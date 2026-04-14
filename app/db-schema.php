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
            is_premium BOOLEAN NOT NULL DEFAULT FALSE,
            status TEXT NOT NULL DEFAULT 'live',
            sold_price NUMERIC(12,2),
            sold_buyer_name TEXT,
            sold_buyer_email TEXT,
            sold_at TIMESTAMPTZ,
            created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
            updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
        )
    SQL);

    whois_db_execute(<<<'SQL'
        CREATE TABLE IF NOT EXISTS marketplace_bids (
            id BIGSERIAL PRIMARY KEY,
            marketplace_item_id BIGINT NOT NULL,
            domain_name TEXT NOT NULL,
            bidder_name TEXT,
            bidder_email TEXT,
            bid_amount NUMERIC(12,2) NOT NULL,
            note TEXT,
            created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
        )
    SQL);

    whois_db_execute(<<<'SQL'
        CREATE TABLE IF NOT EXISTS contact_submissions (
            id BIGSERIAL PRIMARY KEY,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            subject TEXT,
            message TEXT NOT NULL,
            type TEXT NOT NULL DEFAULT 'contact',
            ip_address TEXT,
            created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
        )
    SQL);

    whois_db_execute(<<<'SQL'
        CREATE TABLE IF NOT EXISTS articles (
            id BIGSERIAL PRIMARY KEY,
            slug TEXT NOT NULL UNIQUE,
            title TEXT NOT NULL,
            category TEXT NOT NULL,
            excerpt TEXT,
            content TEXT NOT NULL,
            image_url TEXT,
            author_string TEXT,
            read_time_minutes INTEGER DEFAULT 5,
            status TEXT NOT NULL DEFAULT 'draft',
            published_at TIMESTAMPTZ,
            created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
            updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
        )
    SQL);

    whois_db_execute("ALTER TABLE marketplace_items ADD COLUMN IF NOT EXISTS source_submission_id BIGINT");
    whois_db_execute("ALTER TABLE marketplace_items ADD COLUMN IF NOT EXISTS is_premium BOOLEAN NOT NULL DEFAULT FALSE");
    whois_db_execute("ALTER TABLE marketplace_items ADD COLUMN IF NOT EXISTS sold_price NUMERIC(12,2)");
    whois_db_execute("ALTER TABLE marketplace_items ADD COLUMN IF NOT EXISTS sold_buyer_name TEXT");
    whois_db_execute("ALTER TABLE marketplace_items ADD COLUMN IF NOT EXISTS sold_buyer_email TEXT");
    whois_db_execute("ALTER TABLE marketplace_items ADD COLUMN IF NOT EXISTS sold_at TIMESTAMPTZ");
    whois_db_execute("ALTER TABLE marketplace_items ADD COLUMN IF NOT EXISTS background_image_url TEXT");
    whois_db_execute("ALTER TABLE marketplace_items ADD COLUMN IF NOT EXISTS ai_description TEXT");
    whois_db_execute("ALTER TABLE marketplace_items ADD COLUMN IF NOT EXISTS ai_why_bullets TEXT");
    whois_db_execute("ALTER TABLE marketplace_items ADD COLUMN IF NOT EXISTS ai_technical_log TEXT");
    whois_db_execute("ALTER TABLE marketplace_items ADD COLUMN IF NOT EXISTS ai_use_cases TEXT");

    whois_db_execute("CREATE INDEX IF NOT EXISTS auction_submissions_status_created_idx ON auction_submissions (status, created_at DESC)");
    whois_db_execute("CREATE INDEX IF NOT EXISTS marketplace_items_status_sort_idx ON marketplace_items (status, sort_order DESC, created_at DESC)");
    whois_db_execute("CREATE UNIQUE INDEX IF NOT EXISTS marketplace_items_source_submission_id_idx ON marketplace_items (source_submission_id) WHERE source_submission_id IS NOT NULL");
    whois_db_execute("CREATE INDEX IF NOT EXISTS marketplace_bids_item_created_idx ON marketplace_bids (marketplace_item_id, created_at DESC)");
    whois_db_execute("CREATE INDEX IF NOT EXISTS articles_status_published_idx ON articles (status, published_at DESC)");
    whois_db_execute("CREATE UNIQUE INDEX IF NOT EXISTS articles_slug_idx ON articles (slug)");

    whois_db_seed_marketplace_items($database);
    whois_db_seed_articles($database);
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
function whois_db_seed_articles($database): void
{
    $countRow = whois_db_fetch_one('SELECT COUNT(*) AS count FROM articles');
    $count = (int) ($countRow['count'] ?? 0);

    if ($count > 0) {
        return;
    }

    $articles = [
        [
            'slug' => 'psychology-of-short-domain-names',
            'title' => 'The Psychology of Short Domain Names',
            'category' => 'Branding Tips',
            'excerpt' => 'Exploring why four-letter domains command premium prices and how human cognition processes short digital identities.',
            'content' => '<p class="text-xl text-black font-medium leading-relaxed mb-8">In the intricate world of digital identity, four-letter domains command astronomical prices. Let\'s delve into how human cognition processes short digital identities and why brevity represents the ultimate luxury in web real estate.</p><h2 class="text-3xl font-bold font-headline text-black mt-12 mb-6 tracking-tight">The Cognitive Load Architecture</h2><p class="mb-6">When users interact with web addresses, they construct a mental model of your brand. Shorter domains heavily reduce cognitive parsing—drastically improving raw recall. The psychological aspect is simple: authority intrinsically correlates with exact-match simplicity.</p><p class="mb-6">According to recent analytics from enterprise registrars, a drop from 8 characters to 4 characters corresponds to a near 400% increase in baseline trust perception. This explains why corporate giants actively spend millions to collapse their branding down into liquid acronyms.</p><h2 class="text-3xl font-bold font-headline text-black mt-12 mb-6 tracking-tight">Building Long-Term Brand Liquidity</h2><p class="mb-6">Think of a short domain name not merely as a marketing expense, but as an appreciating digital asset. In Web3 protocols, secondary blockchain namespaces actively target minimal letter counts. The overlap between traditional DNS and decentralized ENS forces extreme competition on short strings.</p><blockquote class="border-l-4 border-black pl-6 my-10 italic text-2xl text-black font-headline">"Your domain is the first handshake your business makes with a human across the digital divide. Make it swift."</blockquote><p class="mb-6">If you are currently evaluating your portfolio, focus strictly on liquid assets. Ensure you check our Live Premium intelligence and Appraisals before securing your next block of IP.</p>',
            'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAUa4kiT2ay7pwHvTxqcmYl3HB_HhlBTLDAvHoBUMuuLrwQXHezl-G7SF7zAxPuzP4nDGcfNUEo9-kwnLhidMMNSlxy-DgOul0BbdULzmDbAHtP3qS7DLkKPPRu7Bdrlv1FnbztZX3JFDtDvSh26cPbHjDeJRGqNl33i7OJktIpgZ1-1XyxD43KNJyiXpRtJFDsH3XMLnxFTSqjQM81tfUzQIVa1q0-qGZb8RF3fGtkbStaXOfHh3oPsPozDNaHekL2F7GR-IeGhH7x',
            'author_string' => 'System Administrator',
            'status' => 'published',
            'published_at' => date('Y-m-d H:i:s'),
        ]
    ];

    foreach ($articles as $article) {
        whois_db_execute(<<<'SQL'
            INSERT INTO articles (
                slug, title, category, excerpt, content, image_url, author_string, status, published_at
            ) VALUES (
                :slug, :title, :category, :excerpt, :content, :image_url, :author_string, :status, :published_at
            ) ON CONFLICT (slug) DO NOTHING
        SQL, $article);
    }
}