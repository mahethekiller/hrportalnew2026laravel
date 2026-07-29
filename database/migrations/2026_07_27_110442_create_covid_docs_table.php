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
        Schema::create('covid_docs', function (Blueprint $table) {
            $table->id();
            $table->integer('userid');
            $table->string('infection_status');
            $table->string('infection_report');
            $table->string('recovered_status');
            $table->string('recovery_report');
            $table->string('infection_date');
            $table->string('recovery_date');
            $table->string('vaccine_status');
            $table->string('vaccine_name');
            $table->string('dose1_date');
            $table->string('dose2_date');
            $table->text('remarks');
            $table->integer('show_status')->default(1);
            $table->string('dose1_doc');
            $table->string('dose2_doc');
            $table->string('updated_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('covid_docs');
    }
};
