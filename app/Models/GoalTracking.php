<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalTracking extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'goal_trackings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'tracking_type_id',
        'start_date',
        'end_date',
        'subject',
        'target_achiement',
        'description',
        'goal_progress',
        'goal_status'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
