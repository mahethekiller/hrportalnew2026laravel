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
     * Default model attributes for MySQL legacy non-null columns.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_primary' => 0,
        'is_dependent' => 0,
        'country' => 1,
        'work_phone' => '',
        'work_phone_extension' => '',
        'mobile_phone' => '',
        'home_phone' => '',
        'work_email' => '',
        'personal_email' => '',
        'address_1' => '',
        'address_2' => '',
        'city' => '',
        'state' => '',
        'zipcode' => '',
        'age' => '',
        'occupation' => '',
        'qualification' => '',
    ];

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
        'qualification',
        'created_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->created_at)) {
                $model->created_at = date('d-m-Y h:i:s');
            }
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}

