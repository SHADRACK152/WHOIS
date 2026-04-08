<?php

declare(strict_types=1);

function whois_dns_checker_nodes(): array
{
    return [
        ['markerId' => 'marker_1', 'continent' => 'north-america', 'country' => 'us', 'countryName' => 'United States', 'location' => 'San Francisco CA, United States', 'provider' => 'OpenDNS', 'resolver' => '208.67.222.222', 'lat' => 37.7749, 'lon' => -122.4194],
        ['markerId' => 'marker_4', 'continent' => 'north-america', 'country' => 'us', 'countryName' => 'United States', 'location' => 'Mountain View CA, United States', 'provider' => 'Google', 'resolver' => '8.8.8.8', 'lat' => 37.3861, 'lon' => -122.0839],
        ['markerId' => 'marker_78', 'continent' => 'north-america', 'country' => 'us', 'countryName' => 'United States', 'location' => 'Berkeley, US', 'provider' => 'Quad9', 'resolver' => '9.9.9.9', 'lat' => 37.8715, 'lon' => -122.273],
        ['markerId' => 'marker_71', 'continent' => 'north-america', 'country' => 'us', 'countryName' => 'United States', 'location' => 'Kansas City, United States', 'provider' => 'Level 3', 'resolver' => '4.2.2.1', 'lat' => 39.0997, 'lon' => -94.5786],
        ['markerId' => 'marker_73', 'continent' => 'north-america', 'country' => 'us', 'countryName' => 'United States', 'location' => 'United States', 'provider' => 'Level 3', 'resolver' => '4.2.2.2', 'lat' => 39.8283, 'lon' => -98.5795],
        ['markerId' => 'marker_79', 'continent' => 'north-america', 'country' => 'us', 'countryName' => 'United States', 'location' => 'San Francisco, US', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1', 'lat' => 37.7749, 'lon' => -122.4194],
        ['markerId' => 'marker_75', 'continent' => 'north-america', 'country' => 'us', 'countryName' => 'United States', 'location' => 'Ashburn, United States', 'provider' => 'NeuStar', 'resolver' => '64.6.64.6', 'lat' => 39.0438, 'lon' => -77.4874],
        ['markerId' => 'marker_136', 'continent' => 'north-america', 'country' => 'ca', 'countryName' => 'Canada', 'location' => 'Burnaby, Canada', 'provider' => 'Cloudflare', 'resolver' => '1.0.0.1', 'lat' => 49.2488, 'lon' => -122.9805],
        ['markerId' => 'marker_455', 'continent' => 'north-america', 'country' => 'mx', 'countryName' => 'Mexico', 'location' => 'Mexico City, Mexico', 'provider' => 'OpenDNS', 'resolver' => '208.67.222.222', 'lat' => 19.4326, 'lon' => -99.1332],
        ['markerId' => 'marker_25', 'continent' => 'europe', 'country' => 'ru', 'countryName' => 'Russian Federation', 'location' => 'St Petersburg, Russia', 'provider' => 'Yandex', 'resolver' => '77.88.8.8', 'lat' => 59.9311, 'lon' => 30.3609],
        ['markerId' => 'marker_234', 'continent' => 'europe', 'country' => 'nl', 'countryName' => 'Netherlands', 'location' => 'Amsterdam, Netherlands', 'provider' => 'OpenDNS', 'resolver' => '208.67.220.220', 'lat' => 52.3676, 'lon' => 4.9041],
        ['markerId' => 'marker_256', 'continent' => 'europe', 'country' => 'fr', 'countryName' => 'France', 'location' => 'Lille, France', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1', 'lat' => 50.6292, 'lon' => 3.0573],
        ['markerId' => 'marker_18', 'continent' => 'europe', 'country' => 'es', 'countryName' => 'Spain', 'location' => 'Paterna de Rivera, Spain', 'provider' => 'Google', 'resolver' => '8.8.4.4', 'lat' => 36.5215, 'lon' => -5.8661],
        ['markerId' => 'marker_214', 'continent' => 'europe', 'country' => 'at', 'countryName' => 'Austria', 'location' => 'Innsbruck, Austria', 'provider' => 'Quad9', 'resolver' => '9.9.9.9', 'lat' => 47.2692, 'lon' => 11.4041],
        ['markerId' => 'marker_459', 'continent' => 'europe', 'country' => 'gb', 'countryName' => 'United Kingdom', 'location' => 'Salford, United Kingdom', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1', 'lat' => 53.4875, 'lon' => -2.2901],
        ['markerId' => 'marker_122', 'continent' => 'europe', 'country' => 'de', 'countryName' => 'Germany', 'location' => 'Leipzig, Germany', 'provider' => 'Google', 'resolver' => '8.8.8.8', 'lat' => 51.3397, 'lon' => 12.3731],
        ['markerId' => 'marker_213', 'continent' => 'europe', 'country' => 'ie', 'countryName' => 'Ireland', 'location' => 'Dublin, Ireland', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1', 'lat' => 53.3498, 'lon' => -6.2603],
        ['markerId' => 'marker_453', 'continent' => 'africa', 'country' => 'za', 'countryName' => 'South Africa', 'location' => 'Cullinan, South Africa', 'provider' => 'Quad9', 'resolver' => '149.112.112.112', 'lat' => -25.6701, 'lon' => 28.5236],
        ['markerId' => 'marker_300', 'continent' => 'south-america', 'country' => 'br', 'countryName' => 'Brazil', 'location' => 'Sao Paulo, Brazil', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1', 'lat' => -23.5558, 'lon' => -46.6396],
        ['markerId' => 'marker_24', 'continent' => 'australia', 'country' => 'au', 'countryName' => 'Australia', 'location' => 'Research, Australia', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1', 'lat' => -37.6996, 'lon' => 145.1835],
        ['markerId' => 'marker_27', 'continent' => 'australia', 'country' => 'au', 'countryName' => 'Australia', 'location' => 'Melbourne, Australia', 'provider' => 'Google', 'resolver' => '8.8.8.8', 'lat' => -37.8136, 'lon' => 144.9631],
        ['markerId' => 'marker_387', 'continent' => 'asia', 'country' => 'sg', 'countryName' => 'Singapore', 'location' => 'Singapore', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1', 'lat' => 1.3521, 'lon' => 103.8198],
        ['markerId' => 'marker_460', 'continent' => 'asia', 'country' => 'kr', 'countryName' => 'South Korea', 'location' => 'Seoul, South Korea', 'provider' => 'Google', 'resolver' => '8.8.8.8', 'lat' => 37.5665, 'lon' => 126.978],
        ['markerId' => 'marker_364', 'continent' => 'asia', 'country' => 'cn', 'countryName' => 'China', 'location' => 'Hangzhou, China', 'provider' => 'AliDNS', 'resolver' => '223.5.5.5', 'lat' => 30.2741, 'lon' => 120.1551],
        ['markerId' => 'marker_9', 'continent' => 'asia', 'country' => 'tr', 'countryName' => 'Turkey', 'location' => 'Antalya, Turkey', 'provider' => 'Quad9', 'resolver' => '9.9.9.11', 'lat' => 36.8969, 'lon' => 30.7133],
        ['markerId' => 'marker_273', 'continent' => 'asia', 'country' => 'in', 'countryName' => 'India', 'location' => 'Coimbatore, India', 'provider' => 'Cloudflare', 'resolver' => '1.1.1.1', 'lat' => 11.0168, 'lon' => 76.9558],
        ['markerId' => 'marker_67', 'continent' => 'asia', 'country' => 'pk', 'countryName' => 'Pakistan', 'location' => 'Islamabad, Pakistan', 'provider' => 'Google', 'resolver' => '8.8.8.8', 'lat' => 33.6844, 'lon' => 73.0479],
        ['markerId' => 'marker_429', 'continent' => 'asia', 'country' => 'bd', 'countryName' => 'Bangladesh', 'location' => 'Kaharole, Bangladesh', 'provider' => 'Google', 'resolver' => '8.8.8.8', 'lat' => 25.798, 'lon' => 88.6604],
    ];
}
