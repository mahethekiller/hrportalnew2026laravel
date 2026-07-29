<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$tables = ['xin_job_posts', 'xin_job_applications', 'xin_job_interviews'];

foreach ($tables as $t) {
    echo "=== TABLE {$t} ===\n";
    if (Schema::hasTable($t)) {
        print_r(Schema::getColumnListing($t));
    } else {
        echo "Table {$t} does not exist.\n";
    }
    echo "\n";
}
