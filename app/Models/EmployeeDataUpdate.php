<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDataUpdate extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_employee_data_updates';

    /**
     * Disable Eloquent timestamps.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'email_personal',
        'date_of_birth',
        'date_of_birth_doc',
        'gender',
        'contact_no',
        'mother_tongue',
        'age',
        'place_of_birth',
        'blood_group',
        'marital_status',
        'pan_number',
        'aadhar_no',
        'category',
        'address',
        'address_com',
        'added_by',
        'updated_by',
        'added_date',
        'updated_date',
        'facebook_link',
        'twitter_link',
        'blogger_link',
        'linkdedin_link',
        'google_plus_link',
        'instagram_link',
        'pinterest_link',
        'youtube_link',
        'father_name',
        'father_mobile',
        'father_gender',
        'father_occupation',
        'father_age',
        'father_qualification',
        'father_address',
        'mother_name',
        'mother_mobile',
        'mother_gender',
        'mother_occupation',
        'mother_age',
        'mother_qualification',
        'mother_address',
        'brother_name',
        'brother_mobile',
        'brother_gender',
        'brother_occupation',
        'brother_age',
        'brother_qualification',
        'brother_address',
        'sister_name',
        'sister_mobile',
        'sister_gender',
        'sister_occupation',
        'sister_age',
        'sister_qualification',
        'sister_address',
        'spouse_name',
        'spouse_mobile',
        'spouse_gender',
        'spouse_occupation',
        'spouse_age',
        'spouse_qualification',
        'spouse_address',
        'child1_name',
        'child1_mobile',
        'child1_gender',
        'child1_occupation',
        'child1_age',
        'child1_qualification',
        'child1_address',
        'child2_name',
        'child2_mobile',
        'child2_gender',
        'child2_occupation',
        'child2_age',
        'child2_qualification',
        'child2_address',
        'emergency_contact_relation',
        'emergency_contact_name',
        'emergency_contact_gender',
        'emergency_contact_mobile',
        'emergency_contact_age',
        'emergency_contact_occupation',
        'emergency_contact_qualification',
        'emergency_contact_address',
        'official_contact_no',
        'vehicle_type',
        'city_temp',
        'city',
        'state_temp',
        'state',
        'pin_temp',
        'pincode',
        'health_ins_opted',
        'pf_opted',
        'vehicle_no',
        'paytm_no',
        'skype_id',
        'acceptance',
        'acceptance_name',
        'acceptance_date',
        'acceptance_basic',
        'acceptance_father',
        'acceptance_mother',
        'acceptance_emer',
        'acceptance_bro',
        'acceptance_sis',
        'acceptance_c1',
        'acceptance_c2',
        'acceptance_social',
        'acceptance_spouse',
        'emp_updated_dets'
    ];

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'user_id');
    }
}
