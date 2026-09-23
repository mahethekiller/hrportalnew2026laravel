<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBankaccount extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_employee_bankaccount';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'bankaccount_id';

    public $timestamps = false;

    /**
     * Default model attributes for MySQL legacy non-null columns.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_primary' => 1,
        'account_title' => '',
        'account_number' => '',
        'bank_name' => '',
        'bank_code' => '',
        'bank_branch' => '',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'is_primary',
        'account_title',
        'account_number',
        'bank_name',
        'bank_code',
        'bank_branch',
        'created_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->created_at)) {
                $model->created_at = date('d-m-Y h:i:s');
            }
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}

