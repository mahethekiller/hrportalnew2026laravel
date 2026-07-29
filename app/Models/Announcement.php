<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'announcements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'announcement_type',
        'acceptance_message',
        'start_date',
        'end_date',
        'company_id',
        'department_id',
        'published_by',
        'summary',
        'description',
        'image',
        'is_active'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function announcementSubmissions()
    {
        return $this->hasMany(AnnouncementSubmission::class, 'announcement_id');
    }
}
