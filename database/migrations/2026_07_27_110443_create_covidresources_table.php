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
        if (!Schema::hasTable('covidresources')) {
            Schema::create('covidresources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_no');
            $table->string('resource_type');
            $table->string('verified_date');
            $table->string('verified_time')->nullable();
            $table->string('status');
            $table->integer('added_by');
            $table->string('last_updated');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->integer('show_status')->default(1);
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('covidresources');
    }
};
