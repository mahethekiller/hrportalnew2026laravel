<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackAnswer extends Model
{
    use HasFactory;

    protected $table = 'xin_feedback_form_answers';
    protected $primaryKey = 'answer_id';
    public $timestamps = false;

    public function getTable()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('feedback_form_answers')) {
            return 'feedback_form_answers';
        }
        return parent::getTable();
    }

    public function getKeyName()
    {
        $table = $this->getTable();
        if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'answer_id')) {
            return 'answer_id';
        }
        return 'id';
    }

    protected $fillable = [
        'form_id',
        'question_id',
        'employee_id',
        'user_id',
        'answer',
        'feedback',
        'rating',
        'added_date',
        'show_status',
        'created_at'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function question()
    {
        return $this->belongsTo(FeedbackQuestion::class, 'question_id');
    }
}
