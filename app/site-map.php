<?php

declare(strict_types=1);

return [
    'brand' => [
        'name'        => 'WHOIS Intelligence',
        'shortName'   => 'WHOIS',
        'landingPage' => 'whois_premium_domain_intelligence_landing_page.php',
    ],
    'primaryNav' => [
        ['label' => 'Home',        'file' => 'whois_premium_domain_intelligence_landing_page.php', 'group' => 'Home'],
        ['label' => 'WHOIS Lookup','file' => 'whois_professional_lookup_tool.php',                'group' => 'Search'],
        ['label' => 'Search',      'file' => 'whois_ai_domain_search.php',                         'group' => 'Search'],
        ['label' => 'AI Tools',    'file' => 'whois_ai_brand_assistant.php',                       'group' => 'AI Assistants'],
        ['label' => 'Marketplace', 'file' => 'whois_premium_domain_marketplace.php',               'group' => 'Marketplace'],
        ['label' => 'DNS Checker', 'file' => 'whois_dns_checker.php',                              'group' => 'Tools'],
        ['label' => 'Tools',       'file' => 'whois_domain_tools_overview.php',                    'group' => 'Tools'],
        ['label' => 'Insights',    'file' => 'whois_industry_insights_blog.php',                   'group' => 'Insights'],
        ['label' => 'Partner',     'file' => 'whois_partner_with_us.php',                          'group' => 'Partner'],
    ],
    'exploreGroups' => [
        [
            'label'   => 'Search',
            'summary' => 'Core lookup and results flows',
            'pages'   => [
                ['label' => 'AI Domain Search',     'file' => 'whois_ai_domain_search.php'],
                ['label' => 'WHOIS Lookup',         'file' => 'whois_professional_lookup_tool.php'],
                ['label' => 'Search Results',       'file' => 'whois_comprehensive_search_results.php'],
                ['label' => 'AI Generated Domains', 'file' => 'whois_ai_generated_domains.php'],
            ],
        ],
        [
            'label'   => 'AI Assistants',
            'summary' => 'Naming, brand, and idea generation tools',
            'pages'   => [
                ['label' => 'Brand Assistant',        'file' => 'whois_ai_brand_assistant.php'],
                ['label' => 'Business Idea Generator','file' => 'whois_ai_business_idea_generator.php'],
                ['label' => 'Domain Name Generator',  'file' => 'whois_ai_domain_name_generator.php'],
                ['label' => 'Brand Preview',          'file' => 'whois_brand_preview.php'],
            ],
        ],
        [
            'label'   => 'Marketplace',
            'summary' => 'Acquisition, auctions, and premium assets',
            'pages'   => [
                ['label' => 'Premium Marketplace',  'file' => 'whois_premium_domain_marketplace.php'],
                ['label' => 'Live Auctions',         'file' => 'whois_limited_time_domain_auctions.php'],
                ['label' => 'Submit for Auction',   'file' => 'whois_submit_domain_for_auction.php'],
            ],
        ],
        [
            'label'   => 'Tools',
            'summary' => 'Utilities for appraisal, DNS, and discovery',
            'pages'   => [
                ['label' => 'Tools Overview',    'file' => 'whois_domain_tools_overview.php'],
                ['label' => 'Domain Appraisal',  'file' => 'whois_domain_appraisal_tool.php'],
                ['label' => 'DNS Checker',       'file' => 'whois_dns_checker.php'],
            ],
        ],
        [
            'label'   => 'More',
            'summary' => 'Editorial and partnership pages',
            'pages'   => [
                ['label' => 'Landing Page',    'file' => 'whois_premium_domain_intelligence_landing_page.php'],
                ['label' => 'Industry Insights','file' => 'whois_industry_insights_blog.php'],
                ['label' => 'Partner With Us', 'file' => 'whois_partner_with_us.php'],
            ],
        ],
    ],
    'pages' => [
        ['label' => 'Landing Page',               'file' => 'whois_premium_domain_intelligence_landing_page.php', 'group' => 'Home',        'summary' => 'Main entry point with hero search and AI appraisal.'],
        ['label' => 'AI Domain Search',           'file' => 'whois_ai_domain_search.php',                         'group' => 'Search',      'summary' => 'Core AI-powered search entry.'],
        ['label' => 'WHOIS Lookup',               'file' => 'whois_professional_lookup_tool.php',                 'group' => 'Search',      'summary' => 'Detailed lookup and WHOIS/RDAP inspection.'],
        ['label' => 'Comprehensive Results',      'file' => 'whois_comprehensive_search_results.php',             'group' => 'Search',      'summary' => 'Full search results layout.'],
        ['label' => 'AI Generated Domains',       'file' => 'whois_ai_generated_domains.php',                     'group' => 'Search',      'summary' => 'AI name generation results page.'],
        ['label' => 'Brand Assistant',            'file' => 'whois_ai_brand_assistant.php',                       'group' => 'AI Tools',    'summary' => 'Brand-first assistant flow.'],
        ['label' => 'Business Idea Generator',    'file' => 'whois_ai_business_idea_generator.php',               'group' => 'AI Tools',    'summary' => 'Idea generation and discovery.'],
        ['label' => 'Domain Name Generator',      'file' => 'whois_ai_domain_name_generator.php',                 'group' => 'AI Tools',    'summary' => 'Naming generator for domains and brands.'],
        ['label' => 'Brand Preview',              'file' => 'whois_brand_preview.php',                            'group' => 'AI Tools',    'summary' => 'Visual brand preview for any domain.'],
        ['label' => 'Premium Marketplace',        'file' => 'whois_premium_domain_marketplace.php',               'group' => 'Marketplace', 'summary' => 'Marketplace landing page.'],
        ['label' => 'Live Auctions',              'file' => 'whois_limited_time_domain_auctions.php',             'group' => 'Marketplace', 'summary' => 'Auction-style inventory with urgency layout.'],
        ['label' => 'Submit Domain for Auction',  'file' => 'whois_submit_domain_for_auction.php',                'group' => 'Marketplace', 'summary' => 'Submission flow for auction listings.'],
        ['label' => 'Tools Overview',             'file' => 'whois_domain_tools_overview.php',                    'group' => 'Tools',       'summary' => 'Central tools hub.'],
        ['label' => 'Domain Appraisal Tool',      'file' => 'whois_domain_appraisal_tool.php',                    'group' => 'Tools',       'summary' => 'Valuation and appraisal utility.'],
        ['label' => 'DNS Checker',                'file' => 'whois_dns_checker.php',                              'group' => 'Tools',       'summary' => 'Global DNS propagation checker.'],
        ['label' => 'Industry Insights Blog',     'file' => 'whois_industry_insights_blog.php',                   'group' => 'Insights',    'summary' => 'Editorial insights and market commentary.'],
        ['label' => 'Partner With Us',            'file' => 'whois_partner_with_us.php',                          'group' => 'Partner',     'summary' => 'Partnership, brokerage, and contact page.'],
    ],
];
