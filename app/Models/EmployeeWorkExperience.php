<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeWorkExperience extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_employee_work_experience';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'work_experience_id';

    public $timestamps = false;

    /**
     * Default model attributes for MySQL legacy non-null columns.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'interview_id' => 0,
        'company_name' => '',
        'post' => '',
        'description' => '',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'interview_id',
        'company_name',
        'from_date',
        'to_date',
        'post',
        'description',
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

