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
        if (!Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->text('application_name');
            $table->text('default_currency');
            $table->text('default_currency_symbol');
            $table->text('show_currency');
            $table->text('currency_position');
            $table->text('notification_position');
            $table->text('notification_close_btn');
            $table->text('notification_bar');
            $table->text('enable_registration');
            $table->text('login_with');
            $table->text('date_format_xi');
            $table->text('support_email');
            $table->text('employee_manage_own_contact');
            $table->text('employee_manage_own_profile');
            $table->text('employee_manage_own_qualification');
            $table->text('employee_manage_own_work_experience');
            $table->text('employee_manage_own_document');
            $table->text('employee_manage_own_picture');
            $table->text('employee_manage_own_social');
            $table->text('employee_manage_own_bank_account');
            $table->text('enable_attendance');
            $table->text('enable_clock_in_btn');
            $table->text('enable_email_notification');
            $table->text('payroll_include_day_summary');
            $table->text('payroll_include_hour_summary');
            $table->text('payroll_include_leave_summary');
            $table->text('enable_job_application_candidates');
            $table->text('job_logo');
            $table->text('payroll_logo');
            $table->integer('is_payslip_password_generate');
            $table->text('payslip_password_format');
            $table->text('enable_profile_background');
            $table->text('enable_policy_link');
            $table->text('enable_layout');
            $table->text('job_application_format');
            $table->text('project_email');
            $table->text('holiday_email');
            $table->text('leave_email');
            $table->text('payslip_email');
            $table->text('award_email');
            $table->text('recruitment_email');
            $table->text('announcement_email');
            $table->text('training_email');
            $table->text('task_email');
            $table->text('compact_sidebar');
            $table->text('fixed_header');
            $table->text('fixed_sidebar');
            $table->text('boxed_wrapper');
            $table->text('layout_static');
            $table->text('system_skin');
            $table->text('animation_effect');
            $table->text('animation_effect_modal');
            $table->text('animation_effect_topmenu');
            $table->text('footer_text');
            $table->text('system_timezone');
            $table->text('system_ip_address');
            $table->text('system_ip_restriction');
            $table->text('google_maps_api_key');
            $table->string('module_recruitment');
            $table->string('module_travel');
            $table->string('module_performance');
            $table->string('module_files');
            $table->string('module_awards');
            $table->string('module_training');
            $table->string('module_inquiry');
            $table->string('module_language');
            $table->string('module_orgchart');
            $table->string('module_accounting');
            $table->string('module_events');
            $table->string('module_goal_tracking');
            $table->string('module_assets');
            $table->string('module_projects_tasks');
            $table->string('module_chat_box');
            $table->text('enable_page_rendered');
            $table->text('enable_current_year');
            $table->text('employee_login_id');
            $table->string('enable_auth_background');
            $table->text('daily_quote')->nullable();
            $table->text('quote_author')->nullable();
            $table->decimal('expense_balance_left', 50, 2);
            $table->text('hr_version');
            $table->string('hr_release_date');
            $table->text('enable_income_declaration')->nullable();
            $table->text('income_dec_file')->nullable();
            $table->text('income_dec_file_roi')->nullable();
            $table->text('income_doc_last_date')->nullable();
            $table->text('default_from_email');
            $table->text('income_dec_file_ixcheck');
            $table->text('income_dec_file_xtra');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
