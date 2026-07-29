<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavigationMenu extends Model
{
    use HasFactory;

    protected $table = 'xin_navigation_menus';
    protected $primaryKey = 'menu_id';
    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'title',
        'icon',
        'route_name',
        'resource_key',
        'sort_order',
        'is_active',
    ];

    /**
     * Parent relationship.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'menu_id');
    }

    /**
     * Children relationship.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'menu_id')->orderBy('sort_order');
    }
}
