<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBkp3723 extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employees_bkp_3_7_23s';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'employee_id',
        'card_no',
        'office_shift_id',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'date_of_birth',
        'gender',
        'e_status',
        'user_role_id',
        'department_id',
        'sub_department',
        'designation_id',
        'manager_id',
        'company_id',
        'salary_template',
        'hourly_grade_id',
        'monthly_grade_id',
        'date_of_joining',
        'date_of_leaving',
        'marital_status',
        'salary',
        'address',
        'profile_picture',
        'profile_background',
        'resume',
        'skype_id',
        'contact_no',
        'facebook_link',
        'twitter_link',
        'blogger_link',
        'linkdedin_link',
        'google_plus_link',
        'instagram_link',
        'pinterest_link',
        'youtube_link',
        'reporting_location',
        'employee_source',
        'ref_emp_id',
        'probation_status',
        'probation_end_date',
        'resign_date',
        'confirmation_date',
        'rejoin_emp_id',
        'has_rejoined',
        'is_active',
        'last_login_date',
        'last_logout_date',
        'last_login_ip',
        'is_logged_in',
        'online_status',
        'created_by',
        'email_personal',
        'date_of_birth_doc',
        'mother_tongue',
        'age',
        'place_of_birth',
        'blood_group',
        'pan_number',
        'aadhar_no',
        'category',
        'address_com',
        'earned_leave',
        'casual_leave',
        'other_leaves_taken_days',
        'paytm_no',
        'vehicle_no',
        'pf_opted',
        'health_ins_opted',
        'official_contact_no',
        'vehicle_type',
        'city_temp',
        'city',
        'state_temp',
        'state',
        'pin_temp',
        'pincode',
        'corporate_bank_account',
        'prob_mail_status',
        'employment_type',
        'experience',
        'kra_doc',
        'notice_period'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function officeShift()
    {
        return $this->belongsTo(OfficeShift::class, 'office_shift_id');
    }

    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'user_role_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
