<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarningType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'warning_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'type'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employeeWarnings()
    {
        return $this->hasMany(EmployeeWarning::class, 'warning_type_id');
    }

    public function employeeWarningLogs()
    {
        return $this->hasMany(EmployeeWarningLog::class, 'warning_type_id');
    }
}
