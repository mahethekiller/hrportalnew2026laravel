<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SupportTicket extends Model
{
    use HasFactory, \App\Traits\HasCleanContent;

    protected $table = 'xin_support_tickets';
    protected $primaryKey = 'ticket_id';
    public $timestamps = false;

    public function getTable()
    {
        if (Schema::hasTable('support_tickets')) {
            return 'support_tickets';
        }
        return parent::getTable();
    }

    public function getKeyName()
    {
        $table = $this->getTable();
        if (Schema::hasColumn($table, 'id')) {
            return 'id';
        }
        return parent::getKeyName();
    }

    protected $fillable = [
        'company_id',
        'ticket_code',
        'subject',
        'employee_id',
        'ticket_priority',
        'department_id',
        'assigned_to',
        'message',
        'description',
        'ticket_remarks',
        'ticket_status',
        'ticket_note',
        'created_at'
    ];

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
            '1', 'low' => '<span class="badge bg-info-subtle text-info">Low</span>',
            '2', 'medium' => '<span class="badge bg-warning-subtle text-warning">Medium</span>',
            '3', 'high' => '<span class="badge bg-danger-subtle text-danger">High</span>',
            '4', 'critical' => '<span class="badge bg-danger text-white">Critical</span>',
            default => '<span class="badge bg-secondary-subtle text-secondary">' . e($this->priority_label) . '</span>',
        };
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class, 'ticket_id', 'ticket_id');
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class, 'ticket_id', 'ticket_id');
    }
}
