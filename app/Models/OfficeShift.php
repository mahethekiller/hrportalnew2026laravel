<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficeShift extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_office_shift';

    /**
     * Primary key column name.
     *
     * @var string
     */
    protected $primaryKey = 'office_shift_id';

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
        return $this->attributes['office_shift_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'shift_name',
        'default_shift',
        'monday_in_time',
        'monday_out_time',
        'tuesday_in_time',
        'tuesday_out_time',
        'wednesday_in_time',
        'wednesday_out_time',
        'thursday_in_time',
        'thursday_out_time',
        'friday_in_time',
        'friday_out_time',
        'saturday_in_time',
        'saturday_out_time',
        'sunday_in_time',
        'sunday_out_time',
        'created_at',
    ];

    /**
     * Company relation.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
