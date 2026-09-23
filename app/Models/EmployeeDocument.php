<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_employee_documents';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'document_id';

    public $timestamps = false;

    /**
     * Default model attributes for MySQL legacy non-null columns.
      *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'document_type_id' => 1,
        'title' => '',
        'notification_email' => '',
        'is_alert' => 0,
        'description' => '',
        'document_file' => '',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'document_type_id',
        'date_of_expiry',

        'title',
        'notification_email',
        'is_alert',
        'description',
        'document_file',
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

    public function document()
    {

        return $this->belongsTo(Document::class, 'document_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }
}
