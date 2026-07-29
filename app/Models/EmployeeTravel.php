<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTravel extends Model
{
    use HasFactory;

    protected $table = 'xin_employee_travels';
    protected $primaryKey = 'travel_id';
    public $timestamps = false;

    public function getTable()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('employee_travels')) {
            return 'employee_travels';
        }
        return parent::getTable();
    }

    protected $fillable = [
        'company_id',
        'employee_id',
        'travel_type',
        'visit_purpose',
        'visit_place',
        'start_date',
        'end_date',
        'expected_budget',
        'actual_budget',
        'travel_mode',
        'arrangement_type',
        'cost',
        'date',
        'description',
        'status',
        'added_by',
        'created_at'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
