<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeResignation extends Model
{
    use HasFactory;

    protected $table = 'xin_employee_resignations';
    protected $primaryKey = 'resignation_id';
    public $timestamps = false;

    public function getTable()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('employee_resignations')) {
            return 'employee_resignations';
        }
        return parent::getTable();
    }

    protected $fillable = [
        'company_id',
        'employee_id',
        'manager_id',
        'notice_date',
        'resignation_date',
        'requested_notice',
        'reason',
        'manager_comment',
        'it_comment',
        'account_comment',
        'hr_comment',
        'coo_comment',
        'sage_comment',
        'login_comment',
        'it_person',
        'account_per',
        'hr_person',
        'manager_person',
        'sage_person',
        'login_person',
        'employee_accept',
        'comments',
        'added_by',
        'status',
        'created_at'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
