<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_emp_today_attendance';

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
        'card_no',
        'punch_date',
        'check_in_datetime',
        'check_out_datetime',
        'badgenumber',
        'check_in_time',
        'check_out_time',
        'show_status',
    ];

    /**
     * Status Label Accessor.
     */
    public function getStatusLabelAttribute(): string
    {
        $val = (string) ($this->show_status ?? '1');
        if ($val === '1' || strtolower($val) === 'present') {
            return 'Present';
        }
        return ucfirst($val);
    }

    /**
     * Status Badge Class Accessor.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $status = strtolower((string) ($this->show_status ?? '1'));

        if ($status === '1' || str_contains($status, 'present') || $status === 'p') {
            return 'badge-light-success';
        }
        if (str_contains($status, 'late')) {
            return 'badge-light-warning';
        }
        if (str_contains($status, 'absent') || $status === 'a') {
            return 'badge-light-danger';
        }
        if (str_contains($status, 'leave')) {
            return 'badge-light-info';
        }

        return 'badge-light-primary';
    }

    /**
     * Formatted Check In Time Accessor.
     */
    public function getFormattedCheckInAttribute(): string
    {
        $time = $this->check_in_time ?? $this->check_in_datetime;
        if (empty($time)) {
            return '--:--';
        }

        try {
            return Carbon::parse($time)->format('h:i A');
        } catch (\Throwable $e) {
            return (string) $time;
        }
    }

    /**
     * Formatted Check Out Time Accessor.
     */
    public function getFormattedCheckOutAttribute(): string
    {
        $time = $this->check_out_time ?? $this->check_out_datetime;
        if (empty($time)) {
            return '--:--';
        }

        try {
            return Carbon::parse($time)->format('h:i A');
        } catch (\Throwable $e) {
            return (string) $time;
        }
    }

    /**
     * Employee relation.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'card_no', 'card_no');
    }
}
