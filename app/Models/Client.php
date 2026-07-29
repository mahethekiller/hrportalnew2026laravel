<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'client_username',
        'client_password',
        'client_profile',
        'contact_number',
        'company_name',
        'gender',
        'website_url',
        'address_1',
        'address_2',
        'city',
        'state',
        'zipcode',
        'country',
        'is_active',
        'last_logout_date',
        'last_login_date',
        'last_login_ip',
        'is_logged_in'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function projectDiscussions()
    {
        return $this->hasMany(ProjectDiscussion::class, 'client_id');
    }
}
