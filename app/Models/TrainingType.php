<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingType extends Model
{
    use HasFactory;

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_training_types';

    /**
     * Primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'training_type_id';

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
        return $this->attributes['training_type_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'type',
        'status',
        'created_at',
    ];

    /**
     * Trainings relation.
     */
    public function trainings(): HasMany
    {
        return $this->hasMany(TrainingSession::class, 'training_type_id', 'training_type_id');
    }
}
