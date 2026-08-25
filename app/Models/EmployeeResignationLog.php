<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class EmployeeResignationLog extends Model
{
    use HasFactory;

    protected $table = 'xin_employee_resignations_logs';

    public function getTable()
    {
        if (Schema::hasTable('xin_employee_resignations_logs')) {
            return 'xin_employee_resignations_logs';
        }
        if (Schema::hasTable('xin_employee_resignation_logs')) {
            return 'xin_employee_resignation_logs';
        }
        if (Schema::hasTable('employee_resignations_logs')) {
            return 'employee_resignations_logs';
        }
        return parent::getTable();
    }

    protected $fillable = [
        'resignation_id',
        'company_id',
        'employee_id',
        'notice_date',
        'resignation_date',
        'reason',
        'added_by',
        'updated_by',
        'updated_date'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }

    public function updater()
    {
        return $this->belongsTo(Employee::class, 'updated_by', 'user_id');
    }
}
