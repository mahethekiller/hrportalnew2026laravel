<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeResignation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_resignations';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'employee_id',
        'manager_id',
        'notice_date',
        'resignation_date',
        'requested_notice',
        'manager_comment',
        'manager_status',
        'it_comment',
        'it_status',
        'account_status',
        'account_comment',
        'hr_comment',
        'hr_status',
        'head_status',
        'it_person',
        'account_per',
        'hr_person',
        'manager_person',
        'sage_person',
        'login_person',
        'coo_status',
        'coo_comment',
        'sage_status',
        'sage_comment',
        'employee_accept',
        'reason',
        'exit_form',
        'added_by',
        'status',
        'comments',
        'show_status',
        'login_status',
        'login_comment'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
