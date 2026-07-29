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
        if (!Schema::hasTable('xin_employees')) {
            Schema::create('xin_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique();
            $table->foreignId('employee_id');
            $table->integer('card_no');
            $table->foreignId('office_shift_id');
            $table->text('first_name');
            $table->text('last_name');
            $table->string('username');
            $table->text('email');
            $table->text('password');
            $table->text('date_of_birth');
            $table->text('gender');
            $table->integer('e_status');
            $table->foreignId('user_role_id');
            $table->foreignId('department_id');
            $table->text('sub_department')->default('');
            $table->foreignId('designation_id');
            $table->foreignId('manager_id');
            $table->foreignId('sub_manager_id')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->text('salary_template');
            $table->foreignId('hourly_grade_id');
            $table->foreignId('monthly_grade_id');
            $table->text('date_of_joining');
            $table->text('date_of_leaving');
            $table->text('marital_status');
            $table->text('salary');
            $table->text('address');
            $table->text('profile_picture');
            $table->text('profile_background');
            $table->text('resume');
            $table->text('skype_id');
            $table->text('contact_no');
            $table->text('facebook_link');
            $table->text('twitter_link');
            $table->text('blogger_link');
            $table->text('linkdedin_link');
            $table->text('google_plus_link');
            $table->text('instagram_link');
            $table->text('pinterest_link');
            $table->text('youtube_link');
            $table->text('reporting_location')->default('HO');
            $table->text('employee_source')->default('Recruiter');
            $table->foreignId('ref_emp_id')->default(0);
            $table->text('probation_status')->default('Probation');
            $table->text('probation_end_date');
            $table->text('resign_date');
            $table->text('confirmation_date');
            $table->foreignId('rejoin_emp_id')->default(0);
            $table->string('has_rejoined')->default('no');
            $table->boolean('is_active');
            $table->rememberToken();
            $table->text('last_login_date');
            $table->text('last_logout_date');
            $table->text('last_login_ip');
            $table->integer('is_logged_in');
            $table->integer('online_status');
            $table->integer('created_by');
            $table->text('email_personal');
            $table->text('date_of_birth_doc');
            $table->text('mother_tongue');
            $table->string('age');
            $table->text('place_of_birth');
            $table->string('blood_group');
            $table->text('pan_number');
            $table->text('aadhar_no');
            $table->string('category');
            $table->text('address_com');
            $table->string('earned_leave')->default(0);
            $table->string('casual_leave')->default(0);
            $table->integer('other_leaves_taken_days');
            $table->string('paytm_no');
            $table->string('vehicle_no');
            $table->string('pf_opted');
            $table->string('health_ins_opted');
            $table->string('official_contact_no');
            $table->string('vehicle_type');
            $table->text('city_temp');
            $table->text('city');
            $table->text('state_temp');
            $table->text('state');
            $table->text('pin_temp');
            $table->text('pincode');
            $table->string('corporate_bank_account')->default('no');
            $table->string('prob_mail_status')->nullable();
            $table->text('employment_type')->default('permanent');
            $table->decimal('experience', 10, 2);
            $table->text('kra_doc')->default('');
            $table->text('kpi_doc')->nullable()->default('');
            $table->integer('notice_period')->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->foreign('office_shift_id')->references('id')->on('office_shifts')->onDelete('cascade');
            $table->foreign('user_role_id')->references('id')->on('user_roles')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
