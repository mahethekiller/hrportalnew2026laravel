<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subject',
        'message',
        'from_email',
        'to_emails',
        'sent_date',
        'mail_type',
        'mail_type_id',
        'user_id',
        'show_status'
    ];

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'user_id');
    }
}
