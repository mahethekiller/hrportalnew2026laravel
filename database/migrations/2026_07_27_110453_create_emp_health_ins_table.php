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
        if (!Schema::hasTable('emp_health_ins')) {
            Schema::create('emp_health_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('spouse_name');
            $table->string('spouse_gender');
            $table->string('spouse_dob');
            $table->string('child1_name');
            $table->string('child1_gender');
            $table->string('child1_dob');
            $table->string('child2_name');
            $table->string('child2_dob');
            $table->string('child2_gender');
            $table->string('parent1_name');
            $table->string('parent1_gender');
            $table->string('parent1_dob');
            $table->string('parent2_name');
            $table->string('parent2_gender');
            $table->string('parent2_dob');
            $table->string('parent1_relation');
            $table->string('parent2_relation');
            $table->text('remarks');
            $table->integer('show_status')->default(1);
            $table->foreign('user_id')->references('user_id')->on('employees')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_health_ins');
    }
};
