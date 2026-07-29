<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class EmpTodayAttendance extends Model
{
    use HasFactory;

    protected $table = 'xin_emp_today_attendance';
    public $timestamps = false;

    public function getTable()
    {
        if (Schema::hasTable('xin_emp_today_attendance')) {
            return 'xin_emp_today_attendance';
        }
        if (Schema::hasTable('emp_today_attendances')) {
            return 'emp_today_attendances';
        }
        return parent::getTable();
    }

    protected $fillable = [
        'card_no',
        'punch_date',
        'check_in_datetime',
        'check_out_datetime',
        'badgenumber',
        'check_in_time',
        'check_out_time',
        'show_status'
    ];
}
