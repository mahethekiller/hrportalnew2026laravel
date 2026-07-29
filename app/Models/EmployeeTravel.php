<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTravel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_travels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'employee_id',
        'start_date',
        'end_date',
        'visit_purpose',
        'visit_place',
        'travel_mode',
        'arrangement_type',
        'expected_budget',
        'actual_budget',
        'date',
        'from_p',
        'to_p',
        'from_reading',
        'to_reading',
        'distance',
        'cost',
        'description',
        'status',
        'added_by'
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
