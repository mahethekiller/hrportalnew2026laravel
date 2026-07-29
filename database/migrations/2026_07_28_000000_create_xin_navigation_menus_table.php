<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('xin_navigation_menus')) {
            Schema::create('xin_navigation_menus', function (Blueprint $table) {
                $table->bigIncrements('menu_id');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('title', 100);
                $table->string('icon', 100)->nullable();
                $table->string('route_name', 150)->nullable();
                $table->string('resource_key', 100)->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xin_navigation_menus');
    }
};
