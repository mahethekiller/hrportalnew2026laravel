<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeDocument extends Model
{
    use HasFactory;

    protected $table = 'xin_income_documents';
    protected $primaryKey = 'document_id';
    public $timestamps = false;

    public function getTable()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('income_documents')) {
            return 'income_documents';
        }
        return parent::getTable();
    }

    protected $fillable = [
        'company_id',
        'employee_id',
        'doc_type',
        'document_type',
        'title',
        'description',
        'file',
        'file_name',
        'file_size',
        'financial_year',
        'status',
        'added_by',
        'added_date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
