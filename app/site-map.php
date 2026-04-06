<?php

declare(strict_types=1);

return [
    'brand' => [
        'name' => 'WHOIS Intelligence',
        'shortName' => 'WHOIS',
        'landingPage' => 'whois_premium_domain_intelligence_landing_page.html',
    ],
    'primaryNav' => [
        ['label' => 'Home', 'file' => 'whois_premium_domain_intelligence_landing_page.html', 'group' => 'Home'],
        ['label' => 'Search', 'file' => 'whois_ai_domain_search.html', 'group' => 'Search'],
        ['label' => 'AI Assistants', 'file' => 'whois_ai_brand_assistant.html', 'group' => 'AI Assistants'],
        ['label' => 'Marketplace', 'file' => 'whois_premium_domain_marketplace.html', 'group' => 'Marketplace'],
        ['label' => 'Tools', 'file' => 'whois_domain_tools_overview.html', 'group' => 'Tools'],
        ['label' => 'Insights', 'file' => 'whois_industry_insights_blog.html', 'group' => 'Insights'],
        ['label' => 'Partner', 'file' => 'whois_partner_with_us.html', 'group' => 'Partner'],
    ],
    'exploreGroups' => [
        [
            'label' => 'Search',
            'summary' => 'Core lookup and results flows',
            'pages' => [
                ['label' => 'AI Domain Search', 'file' => 'whois_ai_domain_search.html'],
                ['label' => 'Premium AI Search', 'file' => 'whois_premium_ai_domain_search.html'],
                ['label' => 'Professional Lookup', 'file' => 'whois_professional_lookup_tool.html'],
                ['label' => 'Optimized Results', 'file' => 'whois_optimized_premium_search_results.html'],
            ],
        ],
        [
            'label' => 'AI Assistants',
            'summary' => 'Naming, brand, and idea generation tools',
            'pages' => [
                ['label' => 'Brand Assistant', 'file' => 'whois_ai_brand_assistant.html'],
                ['label' => 'Business Idea Generator', 'file' => 'whois_ai_business_idea_generator.html'],
                ['label' => 'Domain Name Generator', 'file' => 'whois_ai_domain_name_generator.html'],
            ],
        ],
        [
            'label' => 'Marketplace',
            'summary' => 'Acquisition, auctions, and premium assets',
            'pages' => [
                ['label' => 'Premium Marketplace', 'file' => 'whois_premium_domain_marketplace.html'],
                ['label' => 'Limited Auctions', 'file' => 'whois_limited_time_domain_auctions.html'],
                ['label' => 'Submit for Auction', 'file' => 'whois_submit_domain_for_auction.html'],
                ['label' => 'Premium Logos Results', 'file' => 'whois_search_results_with_premium_logos.html'],
            ],
        ],
        [
            'label' => 'Tools',
            'summary' => 'Utilities for appraisal and discovery',
            'pages' => [
                ['label' => 'Tools Overview', 'file' => 'whois_domain_tools_overview.html'],
                ['label' => 'Domain Appraisal', 'file' => 'whois_domain_appraisal_tool.html'],
            ],
        ],
        [
            'label' => 'Results',
            'summary' => 'Search outcomes and canonical result views',
            'pages' => [
                ['label' => 'Comprehensive Results', 'file' => 'whois_comprehensive_search_results.html'],
                ['label' => 'Search Results', 'file' => 'whois_domain_search_results.html'],
                ['label' => 'Optimized Premium Search Results', 'file' => 'whois_optimized_premium_search_results.html'],
            ],
        ],
        [
            'label' => 'More',
            'summary' => 'Editorial and partnership pages',
            'pages' => [
                ['label' => 'Landing Page', 'file' => 'whois_premium_domain_intelligence_landing_page.html'],
                ['label' => 'Industry Insights', 'file' => 'whois_industry_insights_blog.html'],
                ['label' => 'Partner With Us', 'file' => 'whois_partner_with_us.html'],
            ],
        ],
    ],
    'pages' => [
        ['label' => 'Premium Domain Intelligence Landing Page', 'file' => 'whois_premium_domain_intelligence_landing_page.html', 'group' => 'Home', 'summary' => 'Main entry point for the suite.'],
        ['label' => 'AI Domain Search', 'file' => 'whois_ai_domain_search.html', 'group' => 'Search', 'summary' => 'Core AI-powered search entry.'],
        ['label' => 'Premium AI Domain Search', 'file' => 'whois_premium_ai_domain_search.html', 'group' => 'Search', 'summary' => 'Premium search experience with stronger conversion cues.'],
        ['label' => 'Professional Lookup Tool', 'file' => 'whois_professional_lookup_tool.html', 'group' => 'Search', 'summary' => 'Detailed lookup and WHOIS inspection page.'],
        ['label' => 'AI Brand Assistant', 'file' => 'whois_ai_brand_assistant.html', 'group' => 'AI Assistants', 'summary' => 'Brand-first assistant flow.'],
        ['label' => 'AI Business Idea Generator', 'file' => 'whois_ai_business_idea_generator.html', 'group' => 'AI Assistants', 'summary' => 'Idea generation and discovery flow.'],
        ['label' => 'AI Domain Name Generator', 'file' => 'whois_ai_domain_name_generator.html', 'group' => 'AI Assistants', 'summary' => 'Naming generator for domains and brands.'],
        ['label' => 'Premium Domain Marketplace', 'file' => 'whois_premium_domain_marketplace.html', 'group' => 'Marketplace', 'summary' => 'Marketplace landing page.'],
        ['label' => 'Limited Time Domain Auctions', 'file' => 'whois_limited_time_domain_auctions.html', 'group' => 'Marketplace', 'summary' => 'Auction-style inventory and urgency layout.'],
        ['label' => 'Submit Domain for Auction', 'file' => 'whois_submit_domain_for_auction.html', 'group' => 'Marketplace', 'summary' => 'Submission flow for auction listings.'],
        ['label' => 'Search Results with Premium Logos', 'file' => 'whois_search_results_with_premium_logos.html', 'group' => 'Marketplace', 'summary' => 'Premium results variation with brand emphasis.'],
        ['label' => 'Domain Tools Overview', 'file' => 'whois_domain_tools_overview.html', 'group' => 'Tools', 'summary' => 'Central tools hub.'],
        ['label' => 'Domain Appraisal Tool', 'file' => 'whois_domain_appraisal_tool.html', 'group' => 'Tools', 'summary' => 'Valuation and appraisal utility.'],
        ['label' => 'Industry Insights Blog', 'file' => 'whois_industry_insights_blog.html', 'group' => 'Insights', 'summary' => 'Editorial insights and market commentary.'],
        ['label' => 'Partner With Us', 'file' => 'whois_partner_with_us.html', 'group' => 'Partner', 'summary' => 'Partnership and collaboration page.'],
        ['label' => 'Comprehensive Search Results', 'file' => 'whois_comprehensive_search_results.html', 'group' => 'Results', 'summary' => 'Full search results layout.'],
        ['label' => 'Domain Search Results', 'file' => 'whois_domain_search_results.html', 'group' => 'Results', 'summary' => 'Search results page with side navigation.'],
        ['label' => 'Optimized Premium Search Results', 'file' => 'whois_optimized_premium_search_results.html', 'group' => 'Results', 'summary' => 'Premium search optimization layout.'],
    ],
];
