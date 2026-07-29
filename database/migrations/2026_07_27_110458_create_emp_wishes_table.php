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
        if (!Schema::hasTable('emp_wishes')) {
            Schema::create('emp_wishes', function (Blueprint $table) {
            $table->id();
            $table->integer('recieving_emp');
            $table->integer('wished_by');
            $table->text('message');
            $table->date('date');
            $table->string('wish_type');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_wishes');
    }
};
