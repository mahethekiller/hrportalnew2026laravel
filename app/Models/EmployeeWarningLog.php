<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeWarningLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_warnings_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'warning_id',
        'company_id',
        'warning_to',
        'warning_by',
        'warning_date',
        'warning_type_id',
        'subject',
        'description',
        'status',
        'updated_by',
        'updated_date'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function warningType()
    {
        return $this->belongsTo(WarningType::class, 'warning_type_id');
    }
}
