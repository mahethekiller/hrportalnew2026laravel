<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeQualificationLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_qualification_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'qualification_id',
        'employee_id',
        'name',
        'education_level_id',
        'from_year',
        'language_id',
        'to_year',
        'skill_id',
        'description',
        'updated_by',
        'updated_date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
