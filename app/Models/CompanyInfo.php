<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_infos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'logo',
        'logo_second',
        'sign_in_logo',
        'favicon',
        'website_url',
        'starting_year',
        'company_name',
        'company_email',
        'company_contact',
        'contact_person',
        'email',
        'phone',
        'address_1',
        'address_2',
        'city',
        'state',
        'zipcode',
        'country'
    ];
}
