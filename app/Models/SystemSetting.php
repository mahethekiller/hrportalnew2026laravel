<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_system_setting';

    /**
     * Primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'setting_id';

    /**
     * Disable model timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Primary Key Accessor.
     */
    public function getIdAttribute()
    {
        return $this->attributes['setting_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'application_name',
        'default_currency',
        'default_currency_symbol',
        'show_currency',
        'currency_position',
        'notification_position',
        'notification_close_btn',
        'notification_bar',
        'enable_registration',
        'login_with',
        'date_format_xi',
        'support_email',
        'employee_manage_own_contact',
        'employee_manage_own_profile',
        'employee_manage_own_qualification',
        'employee_manage_own_work_experience',
        'employee_manage_own_document',
        'employee_manage_own_picture',
        'employee_manage_own_social',
        'employee_manage_own_bank_account',
        'enable_attendance',
        'enable_clock_in_btn',
        'enable_email_notification',
        'payroll_include_day_summary',
        'payroll_include_hour_summary',
        'payroll_include_leave_summary',
        'enable_job_application_candidates',
        'system_timezone',
        'module_recruitment',
        'module_travel',
        'module_performance',
        'module_files',
        'module_awards',
        'module_training',
        'module_assets',
        'footer_text',
        'default_from_email',
        'updated_at',
    ];
}
