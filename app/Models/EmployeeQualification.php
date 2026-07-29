<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeQualification extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_employee_qualification';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'qualification_id';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'interview_id',
        'name',
        'education_level_id',
        'from_year',
        'language_id',
        'to_year',
        'skill_id',
        'specialization',
        'description'
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
