<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeContract extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_employee_contract';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'contract_id';

    public $timestamps = false;

    /**
     * Default model attributes for MySQL legacy non-null columns.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'contract_type_id' => 1,
        'designation_id' => 1,
        'title' => '',
        'description' => '',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'contract_type_id',
        'from_date',
        'designation_id',
        'title',
        'to_date',
        'description',
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

    public function contractType()
    {
        return $this->belongsTo(ContractType::class, 'contract_type_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
}
