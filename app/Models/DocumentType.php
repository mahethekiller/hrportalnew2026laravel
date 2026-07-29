<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'document_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'document_type'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employeeDocuments()
    {
        return $this->hasMany(EmployeeDocument::class, 'document_type_id');
    }

    public function employeeDocumentLogs()
    {
        return $this->hasMany(EmployeeDocumentLog::class, 'document_type_id');
    }

    public function employeeImmigrations()
    {
        return $this->hasMany(EmployeeImmigration::class, 'document_type_id');
    }

    public function employeeImmigrationLogs()
    {
        return $this->hasMany(EmployeeImmigrationLog::class, 'document_type_id');
    }
}
