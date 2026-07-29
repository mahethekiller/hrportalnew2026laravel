<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$migrationsDir = __DIR__ . '/database/migrations';
$allFiles = glob($migrationsDir . '/*.php');
sort($allFiles); // Sort alphabetically

$db = $app->make('db');
$existingMigrations = $db->table('migrations')->pluck('migration')->toArray();

$batch = 1;
$fakedCount = 0;

foreach ($allFiles as $file) {
    $baseName = basename($file);
    $migrationName = substr($baseName, 0, -4); // Strip .php extension
    
    if (in_array($migrationName, $existingMigrations)) {
        continue;
    }
    
    $db->table('migrations')->insert([
        'migration' => $migrationName,
        'batch' => $batch
    ]);
    echo "Faked migration: $migrationName\n";
    $fakedCount++;
}

echo "Seeded $fakedCount migrations into database.\n";
