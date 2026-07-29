<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewSalaryTemplate extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'interview_salary_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'salary_template_id',
        'company_id',
        'job_interview_id',
        'salary_grades',
        'basic_salary',
        'overtime_rate',
        'house_rent_allowance',
        'meal_allowance',
        'car_allowance',
        'books_allowance',
        'uniform_allowance',
        'special_allowance',
        'security_deposit',
        'provident_fund',
        'tax_deduction',
        'gross_salary',
        'total_allowance',
        'total_deduction',
        'net_salary',
        'added_by'
    ];

    public function salaryTemplate()
    {
        return $this->belongsTo(SalaryTemplate::class, 'salary_template_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function jobInterview()
    {
        return $this->belongsTo(JobInterview::class, 'job_interview_id');
    }
}
