<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intern extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'interns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'date_of_birth',
        'gender',
        'e_status',
        'department_id',
        'company_id',
        'date_of_joining',
        'date_of_leaving',
        'salary',
        'address',
        'contact_no',
        'employee_source',
        'ref_emp_id',
        'created_by',
        'category',
        'address_com',
        'city_temp',
        'city',
        'state_temp',
        'state',
        'pin_temp',
        'pincode',
        'nationality',
        'religion',
        'college',
        'project',
        'tpa',
        'em_name',
        'em_relation',
        'em_contact',
        'reporting_location',
        'show_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

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
}
