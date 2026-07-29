<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeImmigrationLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'employee_immigration_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'immigration_id',
        'employee_id',
        'document_type_id',
        'document_number',
        'document_file',
        'issue_date',
        'expiry_date',
        'country_id',
        'eligible_review_date',
        'comments',
        'updated_by',
        'updated_date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
