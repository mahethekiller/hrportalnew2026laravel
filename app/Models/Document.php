<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Document extends Model
{
    use HasFactory;

    protected $table = 'xin_documents';
    protected $primaryKey = 'file_id';
    public $timestamps = false;

    public function getTable()
    {
        if (Schema::hasTable('xin_documents')) {
            return 'xin_documents';
        }
        if (Schema::hasTable('documents')) {
            return 'documents';
        }
        return parent::getTable();
    }

    public function getKeyName()
    {
        $table = $this->getTable();
        if (Schema::hasColumn($table, 'file_id')) {
            return 'file_id';
        }
        if (Schema::hasColumn($table, 'document_id')) {
            return 'document_id';
        }
        return 'id';
    }

    protected $fillable = [
        'company_id',
        'file_type',
        'file_desc',
        'user_id',
        'file_name',
        'file_extension',
        'file_size',
        'added_by',
        'active'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'user_id');
    }

    public function employeeDocuments()
    {
        return $this->hasMany(EmployeeDocument::class, 'document_id');
    }

    public function employeeDocumentLogs()
    {
        return $this->hasMany(EmployeeDocumentLog::class, 'document_id');
    }
}
