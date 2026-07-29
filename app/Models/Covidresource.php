<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Covidresource extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'covidresources';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'contact_no',
        'resource_type',
        'verified_date',
        'verified_time',
        'status',
        'added_by',
        'last_updated',
        'description',
        'location',
        'show_status'
    ];
}
