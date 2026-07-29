<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollPayment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_make_payment';

    /**
     * Primary key column name.
     *
     * @var string
     */
    protected $primaryKey = 'make_payment_id';

    /**
     * Disable model timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Primary Key Accessor.
     */
    public function getIdAttribute()
    {
        return $this->attributes['make_payment_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'department_id',
        'company_id',
        'location_id',
        'designation_id',
        'payment_date',
        'basic_salary',
        'payment_amount',
        'gross_salary',
        'total_allowances',
        'total_deductions',
        'net_salary',
        'house_rent_allowance',
        'medical_allowance',
        'travelling_allowance',
        'dearness_allowance',
        'provident_fund',
        'tax_deduction',
        'security_deposit',
        'overtime_rate',
        'is_advance_salary_deduct',
        'advance_salary_amount',
        'is_payment',
        'payment_method',
        'hourly_rate',
        'total_hours_work',
        'comments',
        'status',
        'created_at',
    ];

    /**
     * Status Label Accessor.
     */
    public function getStatusLabelAttribute(): string
    {
        $status = (string) ($this->status ?? '1');
        return match ($status) {
            '1' => 'Paid',
            '0' => 'Unpaid',
            default => ucfirst($status),
        };
    }

    /**
     * Status Badge Class Accessor.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $status = (string) ($this->status ?? '1');
        return match ($status) {
            '1' => 'badge-light-success',
            '0' => 'badge-light-danger',
            default => 'badge-light-secondary',
        };
    }

    /**
     * Formatted Payment Date Accessor.
     */
    public function getFormattedPaymentDateAttribute(): string
    {
        if (empty($this->payment_date)) {
            return '--';
        }

        try {
            return Carbon::parse($this->payment_date)->format('M d, Y');
        } catch (\Throwable $e) {
            return (string) $this->payment_date;
        }
    }

    /**
     * Employee relation.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }

    /**
     * Department relation.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    /**
     * Designation relation.
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'designation_id');
    }

    /**
     * Company relation.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
