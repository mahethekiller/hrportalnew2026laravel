<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_employee_salary';

    /**
     * Primary key column name.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Disable model timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'old_salary',
        'new_salary',
        'appraisal_date',
        'added_by',
        'added_date',
        'show_status',
    ];

    /**
     * Formatted Appraisal Date Accessor.
     */
    public function getFormattedAppraisalDateAttribute(): string
    {
        if (empty($this->appraisal_date)) {
            return '--';
        }

        try {
            return Carbon::parse($this->appraisal_date)->format('M d, Y');
        } catch (\Throwable $e) {
            return (string) $this->appraisal_date;
        }
    }

    /**
     * Employee relation.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }
}
