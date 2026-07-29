<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $table = 'xin_referrals';
    protected $primaryKey = 'referral_id';
    public $timestamps = false;

    public function getTable()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('referrals')) {
            return 'referrals';
        }
        return parent::getTable();
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
        'description',
        'remarks',
        'status',
        'created_at'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function job()
    {
        return $this->belongsTo(JobPost::class, 'job_id');
    }
}
