<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
        $this->call([
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            NavigationMenuSeeder::class,
            EmailTemplateSeeder::class,
        ]);
    }
}
