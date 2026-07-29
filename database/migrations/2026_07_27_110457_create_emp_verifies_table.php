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
        if (!Schema::hasTable('emp_verifies')) {
            Schema::create('emp_verifies', function (Blueprint $table) {
            $table->id();
            $table->integer('userid');
            $table->string('emp_code');
            $table->string('designation');
            $table->string('organization');
            $table->string('manager_name');
            $table->string('manager_email');
            $table->string('manager_phone');
            $table->string('hr_name');
            $table->string('hr_email');
            $table->string('hr_phone');
            $table->string('organization2')->nullable()->default('');
            $table->string('manager_name2')->nullable()->default('');
            $table->string('manager_email2')->nullable()->default('');
            $table->string('manager_phone2')->nullable()->default('');
            $table->string('hr_name2')->nullable()->default('');
            $table->string('hr_email2')->nullable()->default('');
            $table->string('date_of_leaving')->nullable()->default('');
            $table->string('date_of_joining')->nullable()->default('');
            $table->string('hr_phone2')->nullable()->default('');
            $table->string('reason_to_leave');
            $table->string('time_duration');
            $table->string('exit_formalities');
            $table->text('exit_formalities_desc');
            $table->string('offer_letter');
            $table->string('relieving_letter');
            $table->string('increment_letter');
            $table->string('experience_letter');
            $table->integer('designation2');
            $table->string('emp_code2')->nullable();
            $table->string('date_of_leaving2')->nullable();
            $table->string('date_of_joining2')->nullable();
            $table->string('reason_to_leave2')->nullable();
            $table->text('exit_formalities2')->nullable();
            $table->string('exit_formalities_desc2')->nullable();
            $table->string('letter_of_authentication');
            $table->text('comments');
            $table->string('verification_status')->default('Pending');
            $table->string('added_by');
            $table->integer('show_status')->default(1);
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_verifies');
    }
};
