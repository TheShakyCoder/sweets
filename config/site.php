<?php

return [
    'fullname' => env('SITE_FULLNAME', 'Acme Sweets'),
    'address' => env('SITE_ADDRESS', 'Liverpool Road, Penwortham, Preston, PR1 0TB'),
    'telephone' => env('SITE_TELEPHONE', '01772 378 415'),
    'email' => env('SITE_EMAIL', 'hello@lolipopslancashire.co.uk'),
    'established' => env('SITE_ESTABLISHED', '2022'),
    'opening_times' => "Monday - Saturday: 11:00am - 5:00pm<br>Sunday: Closed",

    'social' => [
        'instagram' => 'https://www.instagram.com/lolipops.penwortham/',
        'facebook' => 'https://www.facebook.com/Lolipopslancashireltd',
        'tiktok' => 'https://www.tiktok.com/@lolipops_lancashire',
    ],

    'nav_links' => [
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Our Sweets', 'href' => '#sweets'],
        ['label' => 'About', 'href' => '#about'],
        ['label' => 'Events', 'href' => '#events'],
        ['label' => 'News', 'href' => '/news-updates'],
        ['label' => 'Contact', 'href' => '#contact'],
    ],

    'robots_allowed' => env('ROBOTS_ALLOWED', false),
];