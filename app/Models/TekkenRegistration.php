<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TekkenRegistration extends Model
{
    use HasFactory;

    protected $table = 'tekken_registrations';

    protected $fillable = [
        'full_name',
        'department',
        'festive_green',
        'matches',
        'fee_paid',
        'utr_number',
        'status',
        'ip_address',
        'mac_address',
        'device_name',
        'device_hash',
        'user_agent',
    ];

    protected $casts = [
        'festive_green' => 'boolean',
        'matches' => 'integer',
        'fee_paid' => 'decimal:2',
    ];

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'playing' => 'bg-warning text-dark',
            'completed' => 'bg-success text-white',
            default => 'bg-info text-dark',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'playing' => 'PLAYING NOW',
            'completed' => 'COMPLETED',
            default => 'IN QUEUE',
        };
    }
}
