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
        Schema::create('employees_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('employee_id');
            $table->integer('card_no');
            $table->foreignId('office_shift_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->string('date_of_birth');
            $table->string('gender');
            $table->integer('e_status');
            $table->foreignId('user_role_id');
            $table->foreignId('department_id');
            $table->string('sub_department')->default('');
            $table->foreignId('designation_id');
            $table->foreignId('manager_id');
            $table->foreignId('company_id')->nullable();
            $table->string('salary_template');
            $table->foreignId('hourly_grade_id');
            $table->foreignId('monthly_grade_id');
            $table->string('date_of_joining');
            $table->string('date_of_leaving');
            $table->string('marital_status');
            $table->string('salary');
            $table->text('address');
            $table->text('profile_picture');
            $table->text('profile_background');
            $table->text('resume');
            $table->string('skype_id');
            $table->string('contact_no');
            $table->text('facebook_link');
            $table->text('twitter_link');
            $table->text('blogger_link');
            $table->text('linkdedin_link');
            $table->text('google_plus_link');
            $table->string('instagram_link');
            $table->string('pinterest_link');
            $table->string('youtube_link');
            $table->string('reporting_location')->default('HO');
            $table->string('employee_source')->default('Recruiter');
            $table->foreignId('ref_emp_id')->default(0);
            $table->string('probation_status')->default('Confirmed');
            $table->string('probation_end_date');
            $table->string('resign_date');
            $table->string('confirmation_date');
            $table->foreignId('rejoin_emp_id')->default(0);
            $table->string('has_rejoined')->default('no');
            $table->boolean('is_active');
            $table->string('last_login_date');
            $table->string('last_logout_date');
            $table->string('last_login_ip');
            $table->integer('is_logged_in');
            $table->integer('online_status');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->string('updated_date');
            $table->string('email_personal');
            $table->string('date_of_birth_doc');
            $table->string('mother_tongue');
            $table->string('age');
            $table->string('place_of_birth');
            $table->string('blood_group');
            $table->string('pan_number');
            $table->string('aadhar_no');
            $table->string('category');
            $table->string('address_com');
            $table->integer('earned_leave')->default(0);
            $table->integer('casual_leave')->default(0);
            $table->integer('other_leaves_taken_days');
            $table->string('paytm_no');
            $table->string('vehicle_no');
            $table->string('pf_opted');
            $table->string('health_ins_opted');
            $table->string('official_contact_no');
            $table->string('vehicle_type');
            $table->string('city_temp');
            $table->string('city');
            $table->string('state_temp');
            $table->string('state');
            $table->string('pin_temp');
            $table->string('pincode');
            $table->string('prob_mail_status')->nullable();
            $table->string('employment_type')->default('permanent');
            $table->decimal('experience', 10, 2);
            $table->string('kra_doc')->default('');
            $table->integer('notice_period')->default(0);
            $table->text('updates')->nullable();
            $table->foreign('user_id')->references('user_id')->on('employees')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('office_shift_id')->references('id')->on('office_shifts')->onDelete('cascade');
            $table->foreign('user_role_id')->references('id')->on('user_roles')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees_logs');
    }
};
