<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackQuestion extends Model
{
    use HasFactory;

    protected $table = 'xin_feedback_form_questions';
    protected $primaryKey = 'question_id';
    public $timestamps = false;

    public function getTable()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('feedback_form_questions')) {
            return 'feedback_form_questions';
        }
        return parent::getTable();
    }

    protected $fillable = [
        'form_id',
        'question',
        'type'
    ];
}
