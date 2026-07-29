<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookTrigger extends Model
{
    use HasFactory;

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_webhook_triggers';

    /**
     * Primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'webhook_id';

    /**
     * Disable model timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Primary Key Accessor.
     */
    public function getIdAttribute()
    {
        return $this->attributes['webhook_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_name',
        'target_url',
        'secret_key',
        'status',
        'created_at',
    ];

    /**
     * Status Badge Class Accessor.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $status = strtolower((string) ($this->status ?? 'active'));
        return match ($status) {
            'active', '1' => 'badge-light-success',
            'disabled', '0', 'inactive' => 'badge-light-danger',
            default => 'badge-light-secondary',
        };
    }
}
