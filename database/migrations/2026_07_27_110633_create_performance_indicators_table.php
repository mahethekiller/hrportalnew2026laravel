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
        Schema::create('performance_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('designation_id');
            $table->integer('customer_experience');
            $table->integer('marketing');
            $table->integer('management');
            $table->integer('administration');
            $table->integer('presentation_skill');
            $table->integer('quality_of_work');
            $table->integer('efficiency');
            $table->integer('integrity');
            $table->integer('professionalism');
            $table->integer('team_work');
            $table->integer('critical_thinking');
            $table->integer('conflict_management');
            $table->integer('attendance');
            $table->integer('ability_to_meet_deadline');
            $table->integer('added_by');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_indicators');
    }
};
