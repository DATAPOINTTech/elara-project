<?php

declare(strict_types=1);

return [
    'name' => $_ENV['APP_NAME'] ?? 'ELARA Beauté Studio Management System',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'url' => rtrim($_ENV['APP_URL'] ?? 'https://elara.datapointtechnology.com', '/'),

    'parlor' => [
        'name' => $_ENV['PARLOR_NAME'] ?? 'ELARA BEAUTÉ STUDIO',
        'tagline' => $_ENV['PARLOR_TAGLINE'] ?? 'By Elia & Sehar',
        'subtitle' => $_ENV['PARLOR_SUBTITLE'] ?? 'Haute Beauté & Luxury Wellness Studio',
        'address' => $_ENV['PARLOR_ADDRESS'] ?? 'Main Qasimabad Road, near Ali CNG, Hyderabad, Sindh, Pakistan',
        'city' => $_ENV['PARLOR_CITY'] ?? 'Hyderabad',
        'country' => $_ENV['PARLOR_COUNTRY'] ?? 'Pakistan',
        'phone' => $_ENV['PARLOR_PHONE'] ?? '0335 2004959',
        'whatsapp' => $_ENV['PARLOR_WHATSAPP'] ?? '+923352004959',
        'email' => $_ENV['PARLOR_EMAIL'] ?? 'concierge@elarabeaute.pk',
        'currency_symbol' => $_ENV['CURRENCY_SYMBOL'] ?? 'Rs. ',
        'currency_code' => $_ENV['CURRENCY_CODE'] ?? 'PKR',
        'tax_rate' => (float)($_ENV['PARLOR_TAX_RATE'] ?? 5.0),
        'tax_name' => $_ENV['PARLOR_TAX_NAME'] ?? 'Sales Tax (PRA/SRB)',
        'logo_path' => '/assets/images/elara_logo.jpg',
        'social' => [
            'instagram' => $_ENV['PARLOR_INSTAGRAM'] ?? 'https://www.instagram.com/elarabeautestudio/',
            'facebook' => $_ENV['PARLOR_FACEBOOK'] ?? 'https://www.facebook.com/elarabeautestudio',
            'tiktok' => $_ENV['PARLOR_TIKTOK'] ?? 'https://www.tiktok.com/@elarabeautestudio',
        ],
    ],

    'ai' => [
        'provider' => $_ENV['AI_PROVIDER'] ?? 'gemini',
        'gemini' => [
            'api_key' => $_ENV['GEMINI_API_KEY'] ?? '',
            'model' => $_ENV['GEMINI_MODEL'] ?? 'gemini-1.5-flash',
        ],
        'openai' => [
            'api_key' => $_ENV['OPENAI_API_KEY'] ?? '',
            'model' => $_ENV['OPENAI_MODEL'] ?? 'gpt-4o-mini',
        ],
    ],
];
