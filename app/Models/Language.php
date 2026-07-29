<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'languages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'language_name',
        'language_code',
        'language_flag',
        'is_active'
    ];

    public function employeeQualifications()
    {
        return $this->hasMany(EmployeeQualification::class, 'language_id');
    }

    public function employeeQualificationLogs()
    {
        return $this->hasMany(EmployeeQualificationLog::class, 'language_id');
    }

    public function qualificationLanguages()
    {
        return $this->hasMany(QualificationLanguage::class, 'language_id');
    }
}
