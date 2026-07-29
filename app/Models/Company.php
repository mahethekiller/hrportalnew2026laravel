<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_companies';

    protected $primaryKey = 'company_id';

    public function getIdAttribute()
    {
        return $this->attributes['company_id'] ?? $this->attributes['id'] ?? null;
    }

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_id',
        'name',
        'trading_name',
        'username',
        'password',
        'registration_no',
        'government_tax',
        'email',
        'logo',
        'contact_number',
        'website_url',
        'address_1',
        'address_2',
        'city',
        'state',
        'zipcode',
        'country',
        'is_active',
        'added_by'
    ];

    public function adminTickets()
    {
        return $this->hasMany(AdminTicket::class, 'company_id');
    }

    public function advanceSalaries()
    {
        return $this->hasMany(AdvanceSalary::class, 'company_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'company_id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'company_id');
    }

    public function assetCategories()
    {
        return $this->hasMany(AssetCategory::class, 'company_id');
    }

    public function awardTypes()
    {
        return $this->hasMany(AwardType::class, 'company_id');
    }

    public function awards()
    {
        return $this->hasMany(Award::class, 'company_id');
    }

    public function companyPolicies()
    {
        return $this->hasMany(CompanyPolicy::class, 'company_id');
    }

    public function contractTypes()
    {
        return $this->hasMany(ContractType::class, 'company_id');
    }

    public function currencies()
    {
        return $this->hasMany(Currency::class, 'company_id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'company_id');
    }

    public function designations()
    {
        return $this->hasMany(Designation::class, 'company_id');
    }

    public function documentTypes()
    {
        return $this->hasMany(DocumentType::class, 'company_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'company_id');
    }

    public function employeeComplaints()
    {
        return $this->hasMany(EmployeeComplaint::class, 'company_id');
    }

    public function employeeComplaintLogs()
    {
        return $this->hasMany(EmployeeComplaintLog::class, 'company_id');
    }

    public function employeeExits()
    {
        return $this->hasMany(EmployeeExit::class, 'company_id');
    }

    public function employeeExitLogs()
    {
        return $this->hasMany(EmployeeExitLog::class, 'company_id');
    }

    public function employeeExitTypes()
    {
        return $this->hasMany(EmployeeExitType::class, 'company_id');
    }

    public function employeeExitTypeLogs()
    {
        return $this->hasMany(EmployeeExitTypeLog::class, 'company_id');
    }

    public function employeePromotions()
    {
        return $this->hasMany(EmployeePromotion::class, 'company_id');
    }

    public function employeePromotionLogs()
    {
        return $this->hasMany(EmployeePromotionLog::class, 'company_id');
    }

    public function employeeResignations()
    {
        return $this->hasMany(EmployeeResignation::class, 'company_id');
    }

    public function employeeResignationLogs()
    {
        return $this->hasMany(EmployeeResignationLog::class, 'company_id');
    }

    public function employeeTerminations()
    {
        return $this->hasMany(EmployeeTermination::class, 'company_id');
    }

    public function employeeTerminationLogs()
    {
        return $this->hasMany(EmployeeTerminationLog::class, 'company_id');
    }

    public function employeeTransfers()
    {
        return $this->hasMany(EmployeeTransfer::class, 'company_id');
    }

    public function employeeTransferLogs()
    {
        return $this->hasMany(EmployeeTransferLog::class, 'company_id');
    }

    public function employeeTravels()
    {
        return $this->hasMany(EmployeeTravel::class, 'company_id');
    }

    public function employeeTravelLogs()
    {
        return $this->hasMany(EmployeeTravelLog::class, 'company_id');
    }

    public function employeeWarnings()
    {
        return $this->hasMany(EmployeeWarning::class, 'company_id');
    }

    public function employeeWarningLogs()
    {
        return $this->hasMany(EmployeeWarningLog::class, 'company_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'company_id');
    }

    public function employeeBkp3723s()
    {
        return $this->hasMany(EmployeeBkp3723::class, 'company_id');
    }

    public function employeeLogs()
    {
        return $this->hasMany(EmployeeLog::class, 'company_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'company_id');
    }

    public function expenseTypes()
    {
        return $this->hasMany(ExpenseType::class, 'company_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'company_id');
    }

    public function goalTrackings()
    {
        return $this->hasMany(GoalTracking::class, 'company_id');
    }

    public function goalTrackingTypes()
    {
        return $this->hasMany(GoalTrackingType::class, 'company_id');
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class, 'company_id');
    }

    public function hourlyTemplates()
    {
        return $this->hasMany(HourlyTemplate::class, 'company_id');
    }

    public function hrTickets()
    {
        return $this->hasMany(HrTicket::class, 'company_id');
    }

    public function interns()
    {
        return $this->hasMany(Intern::class, 'company_id');
    }

    public function interviewSalaryTemplates()
    {
        return $this->hasMany(InterviewSalaryTemplate::class, 'company_id');
    }

    public function jobCodes()
    {
        return $this->hasMany(JobCode::class, 'company_id');
    }

    public function jobRequests()
    {
        return $this->hasMany(JobRequest::class, 'company_id');
    }

    public function jobTypes()
    {
        return $this->hasMany(JobType::class, 'company_id');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'company_id');
    }

    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class, 'company_id');
    }

    public function leaveTypes()
    {
        return $this->hasMany(LeaveType::class, 'company_id');
    }

    public function makePayments()
    {
        return $this->hasMany(MakePayment::class, 'company_id');
    }

    public function medClaims()
    {
        return $this->hasMany(MedClaim::class, 'company_id');
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class, 'company_id');
    }

    public function officeLocations()
    {
        return $this->hasMany(OfficeLocation::class, 'company_id');
    }

    public function officeShifts()
    {
        return $this->hasMany(OfficeShift::class, 'company_id');
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class, 'company_id');
    }

    public function performanceAppraisals()
    {
        return $this->hasMany(PerformanceAppraisal::class, 'company_id');
    }

    public function performanceIndicators()
    {
        return $this->hasMany(PerformanceIndicator::class, 'company_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'company_id');
    }

    public function qualificationEducationLevels()
    {
        return $this->hasMany(QualificationEducationLevel::class, 'company_id');
    }

    public function qualificationLanguages()
    {
        return $this->hasMany(QualificationLanguage::class, 'company_id');
    }

    public function qualificationSkills()
    {
        return $this->hasMany(QualificationSkill::class, 'company_id');
    }

    public function salaryTemplates()
    {
        return $this->hasMany(SalaryTemplate::class, 'company_id');
    }

    public function subDepartments()
    {
        return $this->hasMany(SubDepartment::class, 'company_id');
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class, 'company_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'company_id');
    }

    public function terminationTypes()
    {
        return $this->hasMany(TerminationType::class, 'company_id');
    }

    public function trainers()
    {
        return $this->hasMany(Trainer::class, 'company_id');
    }

    public function trainings()
    {
        return $this->hasMany(Training::class, 'company_id');
    }

    public function trainingTypes()
    {
        return $this->hasMany(TrainingType::class, 'company_id');
    }

    public function travelArrangementTypes()
    {
        return $this->hasMany(TravelArrangementType::class, 'company_id');
    }

    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'company_id');
    }

    public function warningTypes()
    {
        return $this->hasMany(WarningType::class, 'company_id');
    }
}
