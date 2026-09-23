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
    protected $table = 'xin_document_type';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'document_type_id';

    public $timestamps = false;

    public function getIdAttribute()
    {
        return $this->attributes['document_type_id'] ?? $this->attributes['id'] ?? null;
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['document_type'] ?? '';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'document_type',
        'created_at'
    ];


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    /**
     * Name with Company Accessor (Rule 9 Compliance).
     */
    public function getNameWithCompanyAttribute(): string
    {
        $comp = $this->company ? ($this->company->name ?? $this->company->trading_name ?? '') : '';
        return $this->document_type . ($comp ? " (Company: {$comp})" : '');
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
