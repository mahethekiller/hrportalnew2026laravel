<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackForm extends Model
{
    use HasFactory;

    protected $table = 'xin_feedback_forms';
    protected $primaryKey = 'form_id';
    public $timestamps = false;

    public function getTable()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('feedback_forms')) {
            return 'feedback_forms';
        }
        return parent::getTable();
    }

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'created_at'
    ];

    public function questions()
    {
        return $this->hasMany(FeedbackQuestion::class, 'form_id');
    }
}
