<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'project_id',
        'created_by',
        'task_name',
        'assigned_to',
        'start_date',
        'end_date',
        'task_hour',
        'task_progress',
        'description',
        'task_status',
        'task_note'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function taskAttachments()
    {
        return $this->hasMany(TaskAttachment::class, 'task_id');
    }

    public function taskComments()
    {
        return $this->hasMany(TaskComment::class, 'task_id');
    }
}
