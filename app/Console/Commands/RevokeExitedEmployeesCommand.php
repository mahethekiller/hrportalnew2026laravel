<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\EmployeeResignation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RevokeExitedEmployeesCommand extends Command
{
    protected $signature = 'portal:revoke-exited-employees';
    protected $description = 'Automatically deactivate portal login for employees whose confirmed Last Working Day (LWD) has passed.';

    public function handle(): int
    {
        $today = Carbon::today()->format('Y-m-d');

        $exitedResignations = EmployeeResignation::where('resignation_date', '<=', $today)
            ->where(function ($q) {
                $q->where('hr_status', 1)
                  ->orWhere('status', 'Completed')
                  ->orWhere('status', 'Relieved')
                  ->orWhere('status', 'Approved');
            })
            ->with('employee')
            ->get();

        $revokedCount = 0;

        foreach ($exitedResignations as $resignation) {
            $employee = $resignation->employee;
            if ($employee && (int) $employee->is_active === 1) {
                $employee->is_active = 0;
                $employee->save();
                $revokedCount++;

                Log::info("Portal login access automatically revoked for exited employee [ID: {$employee->user_id}, Name: {$employee->first_name} {$employee->last_name}] post LWD [{$resignation->resignation_date}].");
            }
        }

        $this->info("Successfully revoked portal login for {$revokedCount} exited employee(s).");
        return Command::SUCCESS;
    }
}
