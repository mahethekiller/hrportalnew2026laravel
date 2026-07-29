<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpCertificateClaim extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'emp_certificate_claims';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userid',
        'certificate_name',
        'certificate_doc',
        'from_date',
        'to_date',
        'institute',
        'amount',
        'reimburse_amount_req',
        'approved_amt',
        'issued_date',
        'remarks',
        'added_by',
        'last_updated',
        'updated_by',
        'show_status'
    ];
}
