<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollCustomField extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payroll_custom_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'allow_custom_1',
        'is_active_allow_1',
        'allow_custom_2',
        'is_active_allow_2',
        'allow_custom_3',
        'is_active_allow_3',
        'allow_custom_4',
        'is_active_allow_4',
        'allow_custom_5',
        'is_active_allow_5',
        'deduct_custom_1',
        'is_active_deduct_1',
        'deduct_custom_2',
        'is_active_deduct_2',
        'deduct_custom_3',
        'is_active_deduct_3',
        'deduct_custom_4',
        'is_active_deduct_4',
        'deduct_custom_5',
        'is_active_deduct_5'
    ];
}
