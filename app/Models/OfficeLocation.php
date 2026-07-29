<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeLocation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_office_location';

    protected $primaryKey = 'location_id';

    public function getIdAttribute()
    {
        return $this->attributes['location_id'] ?? $this->attributes['id'] ?? null;
    }

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'location_head',
        'location_manager',
        'location_name',
        'email',
        'phone',
        'fax',
        'address_1',
        'address_2',
        'city',
        'state',
        'zipcode',
        'country',
        'added_by',
        'status'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employeeLocations()
    {
        return $this->hasMany(EmployeeLocation::class, 'office_location_id');
    }

    public function employeeLocationLogs()
    {
        return $this->hasMany(EmployeeLocationLog::class, 'office_location_id');
    }
}
