<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeContact extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_employee_contacts';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'contact_id';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
        'qualification'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
