<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    public const LEAVE_TYPES = [
        1 => ['type_name' => 'Casual Leave', 'days_per_year' => 12],
        2 => ['type_name' => 'Earned Leave', 'days_per_year' => 15],
        3 => ['type_name' => 'RH', 'days_per_year' => 2],
        4 => ['type_name' => 'COMP OFF', 'days_per_year' => 0],
        5 => ['type_name' => 'Maternity', 'days_per_year' => 180],
        6 => ['type_name' => 'Paternity', 'days_per_year' => 4],
        7 => ['type_name' => 'Marriage', 'days_per_year' => 7],
        8 => ['type_name' => 'WORK FROM HOME', 'days_per_year' => 0],
        9 => ['type_name' => 'Covid leave', 'days_per_year' => 14],
        10 => ['type_name' => 'Bereavement Leave', 'days_per_year' => 5],
        11 => ['type_name' => 'LWP', 'days_per_year' => 0],
    ];

    public function run(): void
    {
        foreach (self::LEAVE_TYPES as $id => $data) {
            DB::table('xin_leave_type')->updateOrInsert(
                ['leave_type_id' => $id],
                [
                    'type_name' => $data['type_name'],
                    'days_per_year' => $data['days_per_year'],
                    'status' => 1,
                    'company_id' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ]
            );
        }
    }
}
