<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Meeting extends Model
{
    use HasFactory;

    protected $table = 'xin_meetings';
    protected $primaryKey = 'meeting_id';
    public $timestamps = false;

    public function getTable()
    {
        if (Schema::hasTable('meetings')) {
            return 'meetings';
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
