<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'xin_announcements';
    protected $primaryKey = 'announcement_id';
    public $timestamps = false;

    public function getTable()
    {
        if (Schema::hasTable('announcements')) {
            return 'announcements';
        }
        return parent::getTable();
    }

    public function getKeyName()
    {
        $table = $this->getTable();
        if (Schema::hasColumn($table, 'id')) {
            return 'id';
        }
        return parent::getKeyName();
    }

    public function getCreatedAtColumn()
    {
        $table = $this->getTable();
        if (Schema::hasColumn($table, 'created_at')) {
            return 'created_at';
        }
        return $this->getKeyName();
    }

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
        'is_active',
        'created_at'
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
