<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasRoleScopes
{
    /**
     * Scope query to only include records for the currently authenticated employee.
     */
    public function scopeSelfOnly(Builder $query, string $employeeColumn = 'employee_id'): Builder
    {
        $user = Auth::user();
        if (!$user) {
            return $query;
        }

        $employeeId = $user->employee_id ?? $user->id;
        return $query->where($employeeColumn, $employeeId);
    }

    /**
     * Scope query to include records of direct reports for managers.
     */
    public function scopeTeamOnly(Builder $query, string $employeeColumn = 'employee_id'): Builder
    {
        $user = Auth::user();
        if (!$user) {
            return $query;
        }

        $managerId = $user->employee_id ?? $user->id;

        // Get IDs of employees reporting to this manager
        $reportIds = \App\Models\Employee::where('manager_id', $managerId)
            ->orWhere('employee_id', $managerId)
            ->pluck('employee_id')
            ->toArray();

        return $query->whereIn($employeeColumn, $reportIds);
    }

    /**
     * Scope query to include records within the user's department.
     */
    public function scopeDepartmentOnly(Builder $query, string $departmentColumn = 'department_id'): Builder
    {
        $user = Auth::user();
        if (!$user || !isset($user->department_id)) {
            return $query;
        }

        return $query->where($departmentColumn, $user->department_id);
    }
}
