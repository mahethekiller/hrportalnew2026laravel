<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeExitTypeLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_exit_type_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'exit_type_id',
        'company_id',
        'type',
        'updated_by',
        'updated_date'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
