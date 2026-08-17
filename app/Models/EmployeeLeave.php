<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class EmployeeLeave extends Model
{
    use HasFactory;

    protected $table = 'xin_leave_applications';
    protected $primaryKey = 'leave_id';
    public $timestamps = false;

    public function getTable()
    {
        if (Schema::hasTable('xin_leave_applications')) {
            return 'xin_leave_applications';
        }
        if (Schema::hasTable('leave_applications')) {
            return 'leave_applications';
        }
        if (Schema::hasTable('employee_leaves')) {
            return 'employee_leaves';
        }
        return parent::getTable();
    }

    public function getKeyName()
    {
        $table = $this->getTable();
        if (Schema::hasColumn($table, 'leave_id')) {
            return 'leave_id';
        }
        return 'id';
    }

    public function getCreatedAtColumn()
    {
        $table = $this->getTable();
        if (Schema::hasColumn($table, 'created_at')) {
            return 'created_at';
        }
        return $this->getKeyName();
    }

    protected $fillable = [
        'company_id',
        'employee_id',
        'manager_id',
        'leave_type_id',
        'start_duration',
        'from_date',
        'to_date',
        'end_duration',
        'applied_on',
        'casual_deducted',
        'earned_deducted',
        'reason',
        'remarks',
        'status',
        'created_at',
        'contract_id',
        'casual_leave',
        'medical_leave'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
