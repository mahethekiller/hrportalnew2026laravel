<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCode extends Model
{
    use HasFactory;

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_job_codes';

    /**
     * Primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'job_code_id';

    /**
     * Disable default timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Primary Key Accessor.
     */
    public function getIdAttribute()
    {
        return $this->attributes['job_code_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'job_code',
        'position',
        'added_by',
        'added_date',
        'updated_by',
        'updated_date',
        'status',
    ];

    /**
     * Status Label Accessor.
     */
    public function getStatusLabelAttribute(): string
    {
        $status = strtolower((string) ($this->status ?? 'active'));
        return match ($status) {
            'active', '1' => 'Active',
            'inactive', '0' => 'Inactive',
            default => ucfirst((string) $this->status),
        };
    }

    /**
     * Status Badge Class Accessor.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $status = strtolower((string) ($this->status ?? 'active'));
        return match ($status) {
            'active', '1' => 'badge-light-success',
            'inactive', '0' => 'badge-light-danger',
            default => 'badge-light-secondary',
        };
    }
}
