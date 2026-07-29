<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_assets';

    /**
     * Primary key column name.
     *
     * @var string
     */
    protected $primaryKey = 'assets_id';

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
        return $this->attributes['assets_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'assets_category_id',
        'company_id',
        'employee_id',
        'company_asset_code',
        'name',
        'purchase_date',
        'invoice_number',
        'manufacturer',
        'serial_number',
        'warranty_end_date',
        'asset_note',
        'asset_image',
        'is_working',
        'created_at',
    ];

    /**
     * Status Label Accessor.
     */
    public function getStatusLabelAttribute(): string
    {
        $status = (int) ($this->is_working ?? 1);
        return match ($status) {
            1 => 'In Use / Working',
            0 => 'Under Maintenance',
            default => 'In Stock',
        };
    }

    /**
     * Status Badge Class Accessor.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $status = (int) ($this->is_working ?? 1);
        return match ($status) {
            1 => 'badge-light-success',
            0 => 'badge-light-warning',
            default => 'badge-light-info',
        };
    }

    /**
     * Formatted Purchase Date Accessor.
     */
    public function getFormattedPurchaseDateAttribute(): string
    {
        if (empty($this->purchase_date)) {
            return '--';
        }

        try {
            return Carbon::parse($this->purchase_date)->format('M d, Y');
        } catch (\Throwable $e) {
            return (string) $this->purchase_date;
        }
    }

    /**
     * Formatted Warranty Date Accessor.
     */
    public function getFormattedWarrantyDateAttribute(): string
    {
        if (empty($this->warranty_end_date)) {
            return 'No Warranty';
        }

        try {
            return Carbon::parse($this->warranty_end_date)->format('M d, Y');
        } catch (\Throwable $e) {
            return (string) $this->warranty_end_date;
        }
    }

    /**
     * Employee relation.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }

    /**
     * Company relation.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
