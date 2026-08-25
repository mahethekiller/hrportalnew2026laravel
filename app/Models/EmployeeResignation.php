<?php

namespace App\Models;

use App\Traits\HasCleanContent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class EmployeeResignation extends Model
{
    use HasFactory, HasCleanContent;

    protected $table = 'xin_employee_resignations';
    protected $primaryKey = 'resignation_id';
    public $timestamps = false;

    public function getTable()
    {
        if (Schema::hasTable('employee_resignations')) {
            return 'employee_resignations';
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
        'employee_id',
        'manager_id',
        'notice_date',
        'resignation_date',
        'requested_notice',
        'reason',
        'manager_comment',
        'manager_status',
        'it_comment',
        'it_status',
        'account_comment',
        'account_status',
        'hr_comment',
        'hr_status',
        'coo_comment',
        'sage_comment',
        'login_comment',
        'it_person',
        'account_per',
        'hr_person',
        'manager_person',
        'sage_person',
        'login_person',
        'employee_accept',
        'comments',
        'exit_form',
        'added_by',
        'status',
        'created_at'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id', 'user_id');
    }

    public function managerPerson()
    {
        return $this->belongsTo(Employee::class, 'manager_person', 'user_id');
    }

    public function itPerson()
    {
        return $this->belongsTo(Employee::class, 'it_person', 'user_id');
    }

    public function accountPerson()
    {
        return $this->belongsTo(Employee::class, 'account_per', 'user_id');
    }

    public function hrPerson()
    {
        return $this->belongsTo(Employee::class, 'hr_person', 'user_id');
    }

    // Clean Content Accessors
    public function getCleanReasonAttribute(): string
    {
        return self::sanitizeContent($this->reason ?? '', true);
    }

    public function getCleanManagerCommentAttribute(): string
    {
        return self::sanitizeContent($this->manager_comment ?? '', true);
    }

    public function getCleanItCommentAttribute(): string
    {
        return self::sanitizeContent($this->it_comment ?? '', true);
    }

    public function getCleanAccountCommentAttribute(): string
    {
        return self::sanitizeContent($this->account_comment ?? '', true);
    }

    public function getCleanHrCommentAttribute(): string
    {
        return self::sanitizeContent($this->hr_comment ?? '', true);
    }

    // Notice Period Shortfall Days Accessor
    public function getShortfallDaysAttribute(): int
    {
        if (empty($this->notice_date) || empty($this->resignation_date) || !$this->employee) {
            return 0;
        }

        $noticeDate = Carbon::parse($this->notice_date);
        $expectedLwd = $noticeDate->copy()->addMonths($this->employee->notice_period_months);
        $actualLwd = Carbon::parse($this->resignation_date);

        if ($actualLwd->lt($expectedLwd)) {
            return (int) $actualLwd->diffInDays($expectedLwd);
        }

        return 0;
    }

    // Clearance Status Badge Helpers
    public function getStageStatusHelper(int $statusVal): array
    {
        return match ($statusVal) {
            1 => ['label' => 'Cleared / No Dues', 'class' => 'badge bg-success-subtle text-success border border-success-subtle', 'icon' => 'fa-circle-check'],
            2 => ['label' => 'Pending / Dues Pending', 'class' => 'badge bg-danger-subtle text-danger border border-danger-subtle', 'icon' => 'fa-circle-exclamation'],
            default => ['label' => 'Awaiting Review', 'class' => 'badge bg-warning-subtle text-warning border border-warning-subtle', 'icon' => 'fa-clock'],
        };
    }
}
