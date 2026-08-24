<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminTicket extends Model
{
    use HasFactory, \App\Traits\HasCleanContent;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_admin_tickets';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ticket_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_code',
        'ticket_priority',
        'company_id',
        'subject',
        'employee_id',
        'description',
        'remarks',
        'ticket_status',
        'created_by',
        'created_at',
        'updated_date',
        'show_status',
        'updated_by'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
