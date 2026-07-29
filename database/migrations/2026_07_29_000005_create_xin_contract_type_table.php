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
        if (!Schema::hasTable('xin_contract_type')) {
            Schema::create('xin_contract_type', function (Blueprint $table) {
                $table->id('contract_type_id');
                $table->foreignId('company_id');
                $table->string('name');
                $table->string('created_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xin_contract_type');
    }
};
