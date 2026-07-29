<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class MakePayment extends Model
{
    use HasFactory;

    protected $table = 'xin_make_payment';
    protected $primaryKey = 'make_payment_id';
    public $timestamps = false;

    public function getTable()
    {
        if (Schema::hasTable('xin_make_payment')) {
            return 'xin_make_payment';
        }
        if (Schema::hasTable('make_payments')) {
            return 'make_payments';
        }
        return parent::getTable();
    }

    public function getKeyName()
    {
        $table = $this->getTable();
        if (Schema::hasColumn($table, 'make_payment_id')) {
            return 'make_payment_id';
        }
        return 'id';
    }

    protected $fillable = [
        'employee_id',
        'department_id',
        'company_id',
        'location_id',
        'designation_id',
        'payment_date',
        'basic_salary',
        'payment_amount',
        'gross_salary',
        'total_allowances',
        'total_deductions',
        'net_salary',
        'house_rent_allowance',
        'medical_allowance',
        'travelling_allowance',
        'dearness_allowance',
        'provident_fund',
        'tax_deduction',
        'security_deposit',
        'overtime_rate',
        'is_advance_salary_deduct',
        'advance_salary_amount',
        'is_payment',
        'payment_method',
        'hourly_rate',
        'total_hours_work',
        'comments',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
}
