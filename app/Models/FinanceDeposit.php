<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceDeposit extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'finance_deposits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_type_id',
        'amount',
        'deposit_date',
        'category_id',
        'payer_id',
        'payment_method',
        'deposit_reference',
        'deposit_file',
        'description'
    ];
}
