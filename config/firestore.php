<?php

declare(strict_types=1);

return [
    'project_id' => $_ENV['FIRESTORE_PROJECT_ID'] ?? 'elara-parlor-demo',
    'database' => $_ENV['FIRESTORE_DATABASE'] ?? '(default)',
    'credentials_file' => dirname(__DIR__) . '/' . ($_ENV['GOOGLE_APPLICATION_CREDENTIALS'] ?? 'serviceAccountKey.json'),
    'emulator_fallback' => filter_var($_ENV['FIRESTORE_EMULATOR_FALLBACK'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'emulator_storage_path' => dirname(__DIR__) . '/storage/firestore_db.json',
    'rest_endpoint' => 'https://firestore.googleapis.com/v1',
    'oauth_token_uri' => 'https://oauth2.googleapis.com/token',
    'scope' => 'https://www.googleapis.com/auth/datastore',
];

