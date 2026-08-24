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
    protected $table = 'xin_email_history';

    public $timestamps = false;

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

    /**
     * Formatted sent date accessor supporting legacy UNIX timestamps and date strings.
     */
    public function getFormattedSentDateAttribute(): string
    {
        if (empty($this->sent_date)) {
            return '-';
        }

        try {
            if (is_numeric($this->sent_date)) {
                return \Carbon\Carbon::createFromTimestamp((int) $this->sent_date)->format('d M Y, h:i A');
            }
            return \Carbon\Carbon::parse($this->sent_date)->format('d M Y, h:i A');
        } catch (\Throwable $e) {
            return (string) $this->sent_date;
        }
    }
}
