<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WfhClocking extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_clocking';

    /**
     * Primary key column name.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Disable model timestamps since table uses legacy column names.
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
        'userid',
        'clock_in',
        'clock_out',
        'description',
        'created_at',
        'show_status',
    ];

    /**
     * Status Badge Class Accessor.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $status = (int) $this->show_status;
        if ($status === 1 || (!empty($this->clock_in) && empty($this->clock_out))) {
            return 'badge-light-warning';
        }
        if ($status === 2 || (!empty($this->clock_in) && !empty($this->clock_out))) {
            return 'badge-light-success';
        }

        return 'badge-light-secondary';
    }

    /**
     * Clean Description Accessor (strips HTML tags and decodes entities).
     */
    public function getCleanDescriptionAttribute(): string
    {
        $desc = trim(strip_tags(html_entity_decode($this->description ?? '')));
        return $desc !== '' ? $desc : 'WFH Session';
    }

    /**
     * Status Label Accessor.
     */
    public function getStatusLabelAttribute(): string
    {
        $status = (int) $this->show_status;
        return match ($status) {
            1 => 'WFH Active',
            2 => 'WFH Completed',
            default => (!empty($this->clock_out) ? 'WFH Completed' : 'WFH Active'),
        };
    }

    /**
     * Formatted Clock In Accessor.
     */
    public function getFormattedClockInAttribute(): string
    {
        if (empty($this->clock_in)) {
            return '--:--';
        }

        try {
            return Carbon::parse($this->clock_in)->format('h:i A');
        } catch (\Throwable $e) {
            return (string) $this->clock_in;
        }
    }

    /**
     * Formatted Clock Out Accessor.
     */
    public function getFormattedClockOutAttribute(): string
    {
        if (empty($this->clock_out)) {
            return '--:--';
        }

        try {
            return Carbon::parse($this->clock_out)->format('h:i A');
        } catch (\Throwable $e) {
            return (string) $this->clock_out;
        }
    }

    /**
     * Total Work Hours Accessor.
     */
    public function getTotalHoursAttribute(): string
    {
        if (empty($this->clock_in) || empty($this->clock_out)) {
            return '--';
        }

        try {
            $in = Carbon::parse($this->clock_in);
            $out = Carbon::parse($this->clock_out);
            $mins = $in->diffInMinutes($out);
            $hours = floor($mins / 60);
            $remainingMins = $mins % 60;
            return "{$hours}h {$remainingMins}m";
        } catch (\Throwable $e) {
            return '--';
        }
    }

    /**
     * Employee relation.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'userid', 'user_id');
    }
}
