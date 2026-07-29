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
        if (!Schema::hasTable('employee_resignations')) {
            Schema::create('employee_resignations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('employee_id');
            $table->foreignId('manager_id');
            $table->string('notice_date');
            $table->string('resignation_date');
            $table->string('requested_notice');
            $table->text('manager_comment');
            $table->string('manager_status')->default(0);
            $table->text('it_comment');
            $table->string('it_status')->default(0);
            $table->string('account_status')->default(0);
            $table->text('account_comment');
            $table->text('hr_comment');
            $table->string('hr_status')->default(0);
            $table->string('head_status')->default(0);
            $table->string('it_person');
            $table->string('account_per');
            $table->string('hr_person');
            $table->string('manager_person');
            $table->string('sage_person');
            $table->string('login_person');
            $table->string('coo_status')->default(0);
            $table->text('coo_comment');
            $table->integer('sage_status')->default(0);
            $table->string('sage_comment');
            $table->string('employee_accept');
            $table->text('reason');
            $table->string('exit_form')->nullable();
            $table->integer('added_by');
            $table->string('status')->default('pending');
            $table->text('comments');
            $table->integer('show_status')->default(1);
            $table->string('login_status')->default(0);
            $table->string('login_comment');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_resignations');
    }
};
