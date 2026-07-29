<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'expenses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'company_id',
        'expense_name',
        'billcopy_file',
        'outgoing_amount',
        'incoming_amount',
        'balance',
        'purchase_date',
        'remarks',
        'status',
        'status_remarks'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function financeExpenses()
    {
        return $this->hasMany(FinanceExpense::class, 'expense_id');
    }

    public function financeTransactions()
    {
        return $this->hasMany(FinanceTransaction::class, 'expense_id');
    }
}
