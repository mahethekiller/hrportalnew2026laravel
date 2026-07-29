<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpVerify extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'emp_verifies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userid',
        'emp_code',
        'designation',
        'organization',
        'manager_name',
        'manager_email',
        'manager_phone',
        'hr_name',
        'hr_email',
        'hr_phone',
        'organization2',
        'manager_name2',
        'manager_email2',
        'manager_phone2',
        'hr_name2',
        'hr_email2',
        'date_of_leaving',
        'date_of_joining',
        'hr_phone2',
        'reason_to_leave',
        'time_duration',
        'exit_formalities',
        'exit_formalities_desc',
        'offer_letter',
        'relieving_letter',
        'increment_letter',
        'experience_letter',
        'designation2',
        'emp_code2',
        'date_of_leaving2',
        'date_of_joining2',
        'reason_to_leave2',
        'exit_formalities2',
        'exit_formalities_desc2',
        'letter_of_authentication',
        'comments',
        'verification_status',
        'added_by',
        'show_status'
    ];
}
