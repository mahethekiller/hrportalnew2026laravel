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
        Schema::create('emp_certificate_claims', function (Blueprint $table) {
            $table->id();
            $table->integer('userid');
            $table->string('certificate_name');
            $table->string('certificate_doc');
            $table->string('from_date');
            $table->string('to_date');
            $table->string('institute');
            $table->string('amount');
            $table->string('reimburse_amount_req');
            $table->string('approved_amt');
            $table->string('issued_date');
            $table->text('remarks');
            $table->integer('added_by');
            $table->string('last_updated');
            $table->integer('updated_by');
            $table->integer('show_status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_certificate_claims');
    }
};
