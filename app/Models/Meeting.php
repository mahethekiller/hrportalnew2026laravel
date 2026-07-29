<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $table = 'xin_meetings';
    protected $primaryKey = 'meeting_id';
    public $timestamps = false;

    public function getTable()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('meetings')) {
            return 'meetings';
        }
        return parent::getTable();
    }

    protected $fillable = [
        'company_id',
        'employee_id',
        'meeting_title',
        'meeting_date',
        'meeting_time',
        'room_name',
        'note',
        'meeting_note',
        'created_at'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
