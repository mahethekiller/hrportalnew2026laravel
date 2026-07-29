<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeContactLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_contacts_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contact_id',
        'employee_id',
        'relation',
        'is_primary',
        'is_dependent',
        'contact_name',
        'work_phone',
        'work_phone_extension',
        'mobile_phone',
        'home_phone',
        'work_email',
        'personal_email',
        'address_1',
        'address_2',
        'city',
        'state',
        'zipcode',
        'country',
        'age',
        'occupation',
        'qualification',
        'updated_by',
        'updated_date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
