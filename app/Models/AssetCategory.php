<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetCategory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assets_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'category_name'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'assets_category_id');
    }
}
