<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrTicket extends Model
{
    use HasFactory, \App\Traits\HasCleanContent;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_hr_tickets';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ticket_id';

    /**
     * Indicates if the model should be timestamped.
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
        'ticket_code',
        'ticket_priority',
        'company_id',
        'subject',
        'employee_id',
        'description',
        'remarks',
        'ticket_status',
        'created_by',
        'created_at',
        'updated_date',
        'show_status',
        'updated_by'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getPriorityLabelAttribute(): string
    {
        $priority = strtolower((string)$this->ticket_priority);
        return match ($priority) {
            '1', 'low' => 'Low',
            '2', 'medium' => 'Medium',
            '3', 'high' => 'High',
            '4', 'critical' => 'Critical',
            default => !empty($priority) ? ucfirst($priority) : 'Normal',
        };
    }

    public function getPriorityBadgeAttribute(): string
    {
        $priority = strtolower((string)$this->ticket_priority);
        return match ($priority) {
            '1', 'low' => '<span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-0.5 rounded-pill fs-9 fw-semibold"><i class="fa-solid fa-arrow-down me-1"></i> Low</span>',
            '2', 'medium' => '<span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-0.5 rounded-pill fs-9 fw-semibold"><i class="fa-solid fa-minus me-1"></i> Medium</span>',
            '3', 'high' => '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5 rounded-pill fs-9 fw-semibold"><i class="fa-solid fa-arrow-up me-1"></i> High</span>',
            '4', 'critical' => '<span class="badge bg-danger text-white border border-danger px-2 py-0.5 rounded-pill fs-9 fw-semibold shadow-xs"><i class="fa-solid fa-triangle-exclamation me-1"></i> Critical</span>',
            default => '<span class="badge bg-secondary-subtle text-secondary border px-2 py-0.5 rounded-pill fs-9">' . e($this->priority_label) . '</span>',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match (strval($this->ticket_status)) {
            '2' => 'Closed',
            '3' => 'On Hold',
            default => 'Open',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match (strval($this->ticket_status)) {
            '2' => '<span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fs-9 fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> Closed</span>',
            '3' => '<span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill fs-9 fw-semibold"><i class="fa-solid fa-pause me-1"></i> On Hold</span>',
            default => '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fs-9 fw-semibold"><i class="fa-solid fa-circle-dot me-1"></i> Open</span>',
        };
    }
}
