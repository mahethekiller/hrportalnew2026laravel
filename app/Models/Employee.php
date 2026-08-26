<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_employees';

    protected $primaryKey = 'user_id';

    public function getIdAttribute()
    {
        return $this->attributes['user_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * Disable model timestamps since employees table does not have created_at/updated_at.
     *
     * @var bool
     */
    public $timestamps = false;

    public const STATUS_RESIGNED = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_TERMINATED = 2;
    public const STATUS_LEFT = 3;
    public const STATUS_ABSCOND = 4;
    public const STATUS_DISABLE = 5;

    public const STATUSES = [
        1 => 'Active',
        2 => 'Terminated',
        3 => 'Left',
        4 => 'Abscond',
        5 => 'Disable',
        0 => 'Resigned',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->is_active] ?? 'Unknown';
    }

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
        'sub_manager_id',
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
        'kpi_doc',
        'notice_period',
        'created_at',
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

    public function announcementSubmissionsAsUser()
    {
        return $this->hasMany(AnnouncementSubmission::class, 'user_id', 'user_id');
    }

    public function announcementSubmissionsAsEmployee()
    {
        return $this->hasMany(AnnouncementSubmission::class, 'employee_id');
    }

    public function childHoodImages()
    {
        return $this->hasMany(ChildHoodImage::class, 'user_id', 'user_id');
    }

    public function designationChangeDetails()
    {
        return $this->hasMany(DesignationChangeDetail::class, 'employee_id');
    }

    public function rejoinEmpData()
    {
        return $this->hasMany(RejoinEmpData::class, 'employee_id');
    }

    public function weightLosses()
    {
        return $this->hasMany(WeightLoss::class, 'user_id', 'user_id');
    }

    public function adminTickets()
    {
        return $this->hasMany(AdminTicket::class, 'employee_id');
    }

    public function advanceSalaries()
    {
        return $this->hasMany(AdvanceSalary::class, 'employee_id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'employee_id');
    }

    public function attendanceTimes()
    {
        return $this->hasMany(AttendanceTime::class, 'employee_id');
    }

    public function awards()
    {
        return $this->hasMany(Award::class, 'employee_id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'employee_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'user_id', 'user_id');
    }

    public function documentInterns()
    {
        return $this->hasMany(DocumentIntern::class, 'user_id', 'user_id');
    }

    public function emailHistories()
    {
        return $this->hasMany(EmailHistory::class, 'user_id', 'user_id');
    }

    public function empDevices()
    {
        return $this->hasMany(EmpDevice::class, 'user_id', 'user_id');
    }

    public function empHealthIns()
    {
        return $this->hasMany(EmpHealthIn::class, 'user_id', 'user_id');
    }

    public function employeeBankaccounts()
    {
        return $this->hasMany(EmployeeBankaccount::class, 'employee_id');
    }

    public function employeeBankaccountLogs()
    {
        return $this->hasMany(EmployeeBankaccountLog::class, 'employee_id');
    }

    public function employeeContacts()
    {
        return $this->hasMany(EmployeeContact::class, 'employee_id');
    }

    public function employeeContactLogs()
    {
        return $this->hasMany(EmployeeContactLog::class, 'employee_id');
    }

    public function employeeContracts()
    {
        return $this->hasMany(EmployeeContract::class, 'employee_id');
    }

    public function employeeContractLogs()
    {
        return $this->hasMany(EmployeeContractLog::class, 'employee_id');
    }

    public function employeeDataUpdates()
    {
        return $this->hasMany(EmployeeDataUpdate::class, 'user_id', 'user_id');
    }

    public function employeeDocuments()
    {
        return $this->hasMany(EmployeeDocument::class, 'employee_id');
    }

    public function employeeDocumentLogs()
    {
        return $this->hasMany(EmployeeDocumentLog::class, 'employee_id');
    }

    public function employeeExits()
    {
        return $this->hasMany(EmployeeExit::class, 'employee_id');
    }

    public function employeeExitLogs()
    {
        return $this->hasMany(EmployeeExitLog::class, 'employee_id');
    }

    public function employeeImmigrations()
    {
        return $this->hasMany(EmployeeImmigration::class, 'employee_id');
    }

    public function employeeImmigrationLogs()
    {
        return $this->hasMany(EmployeeImmigrationLog::class, 'employee_id');
    }

    public function employeeLeaves()
    {
        return $this->hasMany(EmployeeLeave::class, 'employee_id');
    }

    public function employeeLeaveLogs()
    {
        return $this->hasMany(EmployeeLeaveLog::class, 'employee_id');
    }

    public function employeeLocations()
    {
        return $this->hasMany(EmployeeLocation::class, 'employee_id');
    }

    public function employeeLocationLogs()
    {
        return $this->hasMany(EmployeeLocationLog::class, 'employee_id');
    }

    public function employeePromotions()
    {
        return $this->hasMany(EmployeePromotion::class, 'employee_id');
    }

    public function employeePromotionLogs()
    {
        return $this->hasMany(EmployeePromotionLog::class, 'employee_id');
    }

    public function employeeQualifications()
    {
        return $this->hasMany(EmployeeQualification::class, 'employee_id');
    }

    public function employeeQualificationLogs()
    {
        return $this->hasMany(EmployeeQualificationLog::class, 'employee_id');
    }

    public function employeeResignations()
    {
        return $this->hasMany(EmployeeResignation::class, 'employee_id');
    }

    public function employeeResignationLogs()
    {
        return $this->hasMany(EmployeeResignationLog::class, 'employee_id');
    }

    public function employeeSalaries()
    {
        return $this->hasMany(EmployeeSalary::class, 'employee_id');
    }

    public function employeeShifts()
    {
        return $this->hasMany(EmployeeShift::class, 'employee_id');
    }

    public function employeeShiftLogs()
    {
        return $this->hasMany(EmployeeShiftLog::class, 'employee_id');
    }

    public function employeeTerminations()
    {
        return $this->hasMany(EmployeeTermination::class, 'employee_id');
    }

    public function employeeTerminationLogs()
    {
        return $this->hasMany(EmployeeTerminationLog::class, 'employee_id');
    }

    public function employeeTransfers()
    {
        return $this->hasMany(EmployeeTransfer::class, 'employee_id');
    }

    public function employeeTransferLogs()
    {
        return $this->hasMany(EmployeeTransferLog::class, 'employee_id');
    }

    public function employeeTravels()
    {
        return $this->hasMany(EmployeeTravel::class, 'employee_id');
    }

    public function employeeTravelLogs()
    {
        return $this->hasMany(EmployeeTravelLog::class, 'employee_id');
    }

    public function employeeWorkExperiences()
    {
        return $this->hasMany(EmployeeWorkExperience::class, 'employee_id');
    }

    public function employeeWorkExperienceLogs()
    {
        return $this->hasMany(EmployeeWorkExperienceLog::class, 'employee_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'employee_id');
    }

    public function employeeBkp3723s()
    {
        return $this->hasMany(EmployeeBkp3723::class, 'employee_id');
    }

    public function employeeLogsAsUser()
    {
        return $this->hasMany(EmployeeLog::class, 'user_id', 'user_id');
    }

    public function employeeLogsAsEmployee()
    {
        return $this->hasMany(EmployeeLog::class, 'employee_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'employee_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'employee_id');
    }

    public function feedbackFormAnswers()
    {
        return $this->hasMany(FeedbackFormAnswer::class, 'user_id', 'user_id');
    }

    public function fileManagers()
    {
        return $this->hasMany(FileManager::class, 'user_id', 'user_id');
    }

    public function hrTickets()
    {
        return $this->hasMany(HrTicket::class, 'employee_id');
    }

    public function interns()
    {
        return $this->hasMany(Intern::class, 'employee_id');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'user_id', 'user_id');
    }

    public function jobInterviews()
    {
        return $this->hasMany(JobInterview::class, 'employee_id');
    }

    public function jobInterviewLogs()
    {
        return $this->hasMany(JobInterviewLog::class, 'employee_id');
    }

    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class, 'employee_id');
    }

    public function makePayments()
    {
        return $this->hasMany(MakePayment::class, 'employee_id');
    }

    public function medClaims()
    {
        return $this->hasMany(MedClaim::class, 'employee_id');
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'employee_id');
    }

    public function passwordResetReqs()
    {
        return $this->hasMany(PasswordResetReq::class, 'employee_id');
    }

    public function performanceAppraisals()
    {
        return $this->hasMany(PerformanceAppraisal::class, 'employee_id');
    }

    public function pipEmployees()
    {
        return $this->hasMany(PipEmployee::class, 'employee_id');
    }

    public function projectBugs()
    {
        return $this->hasMany(ProjectBug::class, 'user_id', 'user_id');
    }

    public function projectDiscussions()
    {
        return $this->hasMany(ProjectDiscussion::class, 'user_id', 'user_id');
    }

    public function supportTicketFiles()
    {
        return $this->hasMany(SupportTicketFile::class, 'employee_id');
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class, 'employee_id');
    }

    public function taskComments()
    {
        return $this->hasMany(TaskComment::class, 'user_id', 'user_id');
    }

    public function ticketComments()
    {
        return $this->hasMany(TicketComment::class, 'user_id', 'user_id');
    }

    public function roleRelation()
    {
        return $this->belongsTo(UserRole::class, 'user_role_id', 'id');
    }

    public function trainings()
    {
        return $this->hasMany(Training::class, 'employee_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id', 'user_id');
    }

    public function subManager()
    {
        return $this->belongsTo(Employee::class, 'sub_manager_id', 'user_id');
    }

    /**
     * Profile Picture URL Accessor.
     */
    public function getProfilePictureUrlAttribute(): string
    {
        return \App\Helpers\UploadHelper::url('profile', $this->profile_picture, $this->gender ?? 'Male');
    }

    /**
     * Notice Period in Months Accessor.
     */
    public function getNoticePeriodMonthsAttribute(): int
    {
        $val = (int) ($this->notice_period ?? 1);
        return $val > 0 ? $val : 1;
    }

    /**
     * Calculate Last Working Day (LWD) based on Notice Period Months.
     */
    public function calculateLwd(?string $noticeDate = null): \Carbon\Carbon
    {
        $startDate = !empty($noticeDate) ? \Carbon\Carbon::parse($noticeDate) : \Carbon\Carbon::today();
        return $startDate->copy()->addMonths($this->notice_period_months);
    }
}
