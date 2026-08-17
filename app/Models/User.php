<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_role',
        'first_name',
        'last_name',
        'company_name',
        'company_logo',
        'user_type',
        'email',
        'username',
        'password',
        'profile_photo',
        'profile_background',
        'contact_number',
        'gender',
        'address_1',
        'address_2',
        'city',
        'state',
        'zipcode',
        'country',
        'last_login_date',
        'last_login_ip',
        'is_logged_in',
        'is_active'
    ];

    public function roleRelation()
    {
        $key = \Illuminate\Support\Facades\Schema::hasColumn((new UserRole)->getTable(), 'role_id') ? 'role_id' : 'id';
        return $this->belongsTo(UserRole::class, 'user_role', $key);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'user_id');
    }

    public function employeeBkp3723s()
    {
        return $this->hasMany(EmployeeBkp3723::class, 'user_id');
    }

    public function interns()
    {
        return $this->hasMany(Intern::class, 'user_id');
    }
}
