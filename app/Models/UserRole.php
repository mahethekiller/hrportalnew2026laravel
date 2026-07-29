<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'portal_roles';

    /**
     * Primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Enable model timestamps.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Primary Key Accessor.
     */
    public function getIdAttribute()
    {
        return $this->attributes['id'] ?? null;
    }

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'role_name',
        'role_access',
        'role_resources',
        'created_at',
    ];

    /**
     * Role resources accessor (convert comma separated string or json into array).
     */
    public function getResourceListAttribute(): array
    {
        $raw = $this->role_resources ?? '';
        if (empty($raw)) {
            return [];
        }
        if (str_starts_with($raw, '[')) {
            return json_decode($raw, true) ?? [];
        }
        return array_map('trim', explode(',', $raw));
    }
}
