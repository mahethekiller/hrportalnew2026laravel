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
        Schema::create('goal_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('tracking_type_id');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('subject');
            $table->string('target_achiement');
            $table->text('description');
            $table->string('goal_progress');
            $table->integer('goal_status')->default(0);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goal_trackings');
    }
};
