<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpTodayAttendanceOld extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'emp_today_attendance_olds';

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
        'show_status'
    ];
}
