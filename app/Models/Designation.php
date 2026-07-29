<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_designations';

    protected $primaryKey = 'designation_id';

    public function getIdAttribute()
    {
        return $this->attributes['designation_id'] ?? $this->attributes['id'] ?? null;
    }

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'top_designation_id',
        'department_id',
        'company_id',
        'designation_name',
        'added_by',
        'status'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employeeContracts()
    {
        return $this->hasMany(EmployeeContract::class, 'designation_id');
    }

    public function employeeContractLogs()
    {
        return $this->hasMany(EmployeeContractLog::class, 'designation_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'designation_id');
    }

    public function employeeBkp3723s()
    {
        return $this->hasMany(EmployeeBkp3723::class, 'designation_id');
    }

    public function employeeLogs()
    {
        return $this->hasMany(EmployeeLog::class, 'designation_id');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'designation_id');
    }

    public function makePayments()
    {
        return $this->hasMany(MakePayment::class, 'designation_id');
    }

    public function performanceIndicators()
    {
        return $this->hasMany(PerformanceIndicator::class, 'designation_id');
    }

    public function trainers()
    {
        return $this->hasMany(Trainer::class, 'designation_id');
    }
}
