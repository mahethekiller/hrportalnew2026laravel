<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_code',
        'country_name',
        'country_flag'
    ];

    public function employeeImmigrations()
    {
        return $this->hasMany(EmployeeImmigration::class, 'country_id');
    }

    public function employeeImmigrationLogs()
    {
        return $this->hasMany(EmployeeImmigrationLog::class, 'country_id');
    }
}
