<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryTemplate extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'salary_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'salary_grades',
        'basic_salary',
        'overtime_rate',
        'house_rent_allowance',
        'medical_allowance',
        'travelling_allowance',
        'dearness_allowance',
        'security_deposit',
        'provident_fund',
        'tax_deduction',
        'gross_salary',
        'total_allowance',
        'total_deduction',
        'net_salary',
        'added_by'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function interviewSalaryTemplates()
    {
        return $this->hasMany(InterviewSalaryTemplate::class, 'salary_template_id');
    }

    public function jobInterviews()
    {
        return $this->hasMany(JobInterview::class, 'salary_template_id');
    }

    public function jobInterviewLogs()
    {
        return $this->hasMany(JobInterviewLog::class, 'salary_template_id');
    }
}
