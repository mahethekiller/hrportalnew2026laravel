<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
