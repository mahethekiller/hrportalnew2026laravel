<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiAccessToken extends Model
{
    use HasFactory;

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_api_access_tokens';

    /**
     * Primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Disable model timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'accessToken',
        'status',
        'added_date',
    ];

    /**
     * Masked Access Token Accessor.
     */
    public function getMaskedTokenAttribute(): string
    {
        $token = $this->accessToken ?? '';
        if (strlen($token) <= 8) {
            return '********';
        }
        return substr($token, 0, 6) . '...' . substr($token, -4);
    }

    /**
     * Status Badge Class Accessor.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $status = strtolower((string) ($this->status ?? 'active'));
        return match ($status) {
            'active', '1' => 'badge-light-success',
            'revoked', '0', 'inactive' => 'badge-light-danger',
            default => 'badge-light-secondary',
        };
    }
}
