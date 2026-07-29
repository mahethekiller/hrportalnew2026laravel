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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->string('job_code');
            $table->string('job_title');
            $table->foreignId('designation_id');
            $table->integer('job_type');
            $table->integer('is_featured');
            $table->integer('job_vacancy');
            $table->string('gender');
            $table->string('minimum_experience');
            $table->string('maximum_experience');
            $table->string('start_date')->nullable();
            $table->string('date_of_closing');
            $table->integer('department');
            $table->string('priority');
            $table->integer('hiring_manager');
            $table->string('job_location');
            $table->text('short_description');
            $table->text('long_description');
            $table->integer('status');
            $table->string('show_on_website')->default('no');
            $table->string('added_by');
            $table->string('updated_date')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('show_status')->nullable()->default(1);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
