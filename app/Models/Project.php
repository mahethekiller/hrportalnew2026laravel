<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'client_id',
        'start_date',
        'end_date',
        'company_id',
        'assigned_to',
        'priority',
        'summary',
        'description',
        'added_by',
        'project_progress',
        'project_note',
        'status'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function hrsaleInvoices()
    {
        return $this->hasMany(HrsaleInvoice::class, 'project_id');
    }

    public function hrsaleInvoiceItems()
    {
        return $this->hasMany(HrsaleInvoiceItem::class, 'project_id');
    }

    public function projectAttachments()
    {
        return $this->hasMany(ProjectAttachment::class, 'project_id');
    }

    public function projectBugs()
    {
        return $this->hasMany(ProjectBug::class, 'project_id');
    }

    public function projectDiscussions()
    {
        return $this->hasMany(ProjectDiscussion::class, 'project_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }
}
