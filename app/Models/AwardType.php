<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'award_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'award_type'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function awards()
    {
        return $this->hasMany(Award::class, 'award_type_id');
    }
}
