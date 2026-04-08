<?php

declare(strict_types=1);

function whois_dns_checker_nodes(): array
{
    return [
        ['markerId' => 'marker_1', 'continent' => 'north-america', 'country' => 'us', 'countryName' => 'United States', 'location' => 'San Francisco CA, United States', 'provider' => 'OpenDNS', 'resolver' => '208.67.222.222'],
        ['markerId' => 'marker_4', 'continent' => 'north-america', 'country' => 'us', 'countryName' => 'United States', 'location' => 'Mountain View CA, United States', 'provider' => 'Google', 'resolver' => '8.8.8.8'],
        ['markerId' => 'marker_78', 'continent' => 'north-america', 'country' => 'us', 'countryName' => 'United States', 'location' => 'Berkeley, US', 'provider' => 'Quad9', 'resolver' => '9.9.9.9'],
        ['markerId' => 'marker_71', 'continent' => 'north-america', 'country' => 'us', 'countryName' => 'United States', 'location' => 'Kansas City, United States', 'provider' => 'Level 3', 'resolver' => '4.2.2.1'],
        ['markerId' => 'marker_73', 'continent' => 'north-america', 'country' => 'us', 'countryName' => 'United States', 'location' => 'United States', 'provider' => 'Level 3', 'resolver' => '4.2.2.2'],
        ['markerId' => 'marker_79', 'continent' => 'north-america', 'country' => 'us', 'countryName' => 'United States', 'location' => 'San Francisco, US', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1'],
        ['markerId' => 'marker_75', 'continent' => 'north-america', 'country' => 'us', 'countryName' => 'United States', 'location' => 'Ashburn, United States', 'provider' => 'NeuStar', 'resolver' => '64.6.64.6'],
        ['markerId' => 'marker_136', 'continent' => 'north-america', 'country' => 'ca', 'countryName' => 'Canada', 'location' => 'Burnaby, Canada', 'provider' => 'Cloudflare', 'resolver' => '1.0.0.1'],
        ['markerId' => 'marker_455', 'continent' => 'north-america', 'country' => 'mx', 'countryName' => 'Mexico', 'location' => 'Mexico City, Mexico', 'provider' => 'OpenDNS', 'resolver' => '208.67.222.222'],
        ['markerId' => 'marker_25', 'continent' => 'europe', 'country' => 'ru', 'countryName' => 'Russian Federation', 'location' => 'St Petersburg, Russia', 'provider' => 'Yandex', 'resolver' => '77.88.8.8'],
        ['markerId' => 'marker_234', 'continent' => 'europe', 'country' => 'nl', 'countryName' => 'Netherlands', 'location' => 'Amsterdam, Netherlands', 'provider' => 'OpenDNS', 'resolver' => '208.67.220.220'],
        ['markerId' => 'marker_256', 'continent' => 'europe', 'country' => 'fr', 'countryName' => 'France', 'location' => 'Lille, France', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1'],
        ['markerId' => 'marker_18', 'continent' => 'europe', 'country' => 'es', 'countryName' => 'Spain', 'location' => 'Paterna de Rivera, Spain', 'provider' => 'Google', 'resolver' => '8.8.4.4'],
        ['markerId' => 'marker_214', 'continent' => 'europe', 'country' => 'at', 'countryName' => 'Austria', 'location' => 'Innsbruck, Austria', 'provider' => 'Quad9', 'resolver' => '9.9.9.9'],
        ['markerId' => 'marker_459', 'continent' => 'europe', 'country' => 'gb', 'countryName' => 'United Kingdom', 'location' => 'Salford, United Kingdom', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1'],
        ['markerId' => 'marker_122', 'continent' => 'europe', 'country' => 'de', 'countryName' => 'Germany', 'location' => 'Leipzig, Germany', 'provider' => 'Google', 'resolver' => '8.8.8.8'],
        ['markerId' => 'marker_213', 'continent' => 'europe', 'country' => 'ie', 'countryName' => 'Ireland', 'location' => 'Dublin, Ireland', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1'],
        ['markerId' => 'marker_453', 'continent' => 'africa', 'country' => 'za', 'countryName' => 'South Africa', 'location' => 'Cullinan, South Africa', 'provider' => 'Quad9', 'resolver' => '149.112.112.112'],
        ['markerId' => 'marker_300', 'continent' => 'south-america', 'country' => 'br', 'countryName' => 'Brazil', 'location' => 'Sao Paulo, Brazil', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1'],
        ['markerId' => 'marker_24', 'continent' => 'australia', 'country' => 'au', 'countryName' => 'Australia', 'location' => 'Research, Australia', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1'],
        ['markerId' => 'marker_27', 'continent' => 'australia', 'country' => 'au', 'countryName' => 'Australia', 'location' => 'Melbourne, Australia', 'provider' => 'Google', 'resolver' => '8.8.8.8'],
        ['markerId' => 'marker_387', 'continent' => 'asia', 'country' => 'sg', 'countryName' => 'Singapore', 'location' => 'Singapore', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1'],
        ['markerId' => 'marker_460', 'continent' => 'asia', 'country' => 'kr', 'countryName' => 'South Korea', 'location' => 'Seoul, South Korea', 'provider' => 'Google', 'resolver' => '8.8.8.8'],
        ['markerId' => 'marker_364', 'continent' => 'asia', 'country' => 'cn', 'countryName' => 'China', 'location' => 'Hangzhou, China', 'provider' => 'AliDNS', 'resolver' => '223.5.5.5'],
        ['markerId' => 'marker_9', 'continent' => 'asia', 'country' => 'tr', 'countryName' => 'Turkey', 'location' => 'Antalya, Turkey', 'provider' => 'Quad9', 'resolver' => '9.9.9.11'],
        ['markerId' => 'marker_273', 'continent' => 'asia', 'country' => 'in', 'countryName' => 'India', 'location' => 'Coimbatore, India', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1'],
        ['markerId' => 'marker_67', 'continent' => 'asia', 'country' => 'pk', 'countryName' => 'Pakistan', 'location' => 'Islamabad, Pakistan', 'provider' => 'Google', 'resolver' => '8.8.8.8'],
        ['markerId' => 'marker_429', 'continent' => 'asia', 'country' => 'bd', 'countryName' => 'Bangladesh', 'location' => 'Kaharole, Bangladesh', 'provider' => 'Google', 'resolver' => '8.8.8.8'],
    ];
}
