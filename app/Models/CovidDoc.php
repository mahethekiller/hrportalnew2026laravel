<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CovidDoc extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'covid_docs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userid',
        'infection_status',
        'infection_report',
        'recovered_status',
        'recovery_report',
        'infection_date',
        'recovery_date',
        'vaccine_status',
        'vaccine_name',
        'dose1_date',
        'dose2_date',
        'remarks',
        'show_status',
        'dose1_doc',
        'dose2_doc',
        'updated_date'
    ];
}
