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
        if (!Schema::hasTable('travel_arrangement_types')) {
            Schema::create('travel_arrangement_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->string('type');
            $table->boolean('status')->default(1);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_arrangement_types');
    }
};
