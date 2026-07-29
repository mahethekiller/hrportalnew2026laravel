<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_email_template';

    /**
     * Primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'template_id';

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
        return $this->attributes['template_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'template_code',
        'name',
        'subject',
        'message',
        'status',
    ];
}
