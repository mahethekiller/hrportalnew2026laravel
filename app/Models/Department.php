<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_departments';

    protected $primaryKey = 'department_id';

    public function getIdAttribute()
    {
        return $this->attributes['department_id'] ?? $this->attributes['id'] ?? null;
    }

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'department_name',
        'company_id',
        'location_id',
        'employee_id',
        'added_by',
        'status'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'department_id');
    }

    public function designations()
    {
        return $this->hasMany(Designation::class, 'department_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id');
    }

    public function employeeBkp3723s()
    {
        return $this->hasMany(EmployeeBkp3723::class, 'department_id');
    }

    public function employeeLogs()
    {
        return $this->hasMany(EmployeeLog::class, 'department_id');
    }

    public function fileManagers()
    {
        return $this->hasMany(FileManager::class, 'department_id');
    }

    public function interns()
    {
        return $this->hasMany(Intern::class, 'department_id');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'department_id');
    }

    public function jobRequests()
    {
        return $this->hasMany(JobRequest::class, 'department_id');
    }

    public function makePayments()
    {
        return $this->hasMany(MakePayment::class, 'department_id');
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class, 'department_id');
    }
}
