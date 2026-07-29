<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpHealthIn extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'emp_health_ins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'spouse_name',
        'spouse_gender',
        'spouse_dob',
        'child1_name',
        'child1_gender',
        'child1_dob',
        'child2_name',
        'child2_dob',
        'child2_gender',
        'parent1_name',
        'parent1_gender',
        'parent1_dob',
        'parent2_name',
        'parent2_gender',
        'parent2_dob',
        'parent1_relation',
        'parent2_relation',
        'remarks',
        'show_status'
    ];

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'user_id');
    }
}
