<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Referral extends Model
{
    use HasFactory, \App\Traits\HasCleanContent;

    protected $table = 'xin_referrals';
    protected $primaryKey = 'referral_id';
    public $timestamps = false;

    public function getTable()
    {
        if (Schema::hasTable('referrals')) {
            return 'referrals';
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
        'job_id',
        'name',
        'email',
        'contact_number',
        'contact_no',
        'resume',
        'assigned_to',
        'added_by',
        'added_date',
        'description',
        'remarks',
        'status',
        'show_status',
        'created_at'
    ];

    public function employee()
    {
        $table = $this->getTable();
        if (Schema::hasColumn($table, 'added_by')) {
            return $this->belongsTo(Employee::class, 'added_by', 'user_id');
        }
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function job()
    {
        $table = $this->getTable();
        if (Schema::hasColumn($table, 'job_id')) {
            return $this->belongsTo(JobPost::class, 'job_id');
        }
        return null;
    }
}
