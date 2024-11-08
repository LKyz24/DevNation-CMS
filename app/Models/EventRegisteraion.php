<?php

namespace App\Models;

use App\Notifications\EventNotification;
use App\Notifications\EventRegisterationStatusUpdateNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegisteraion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'attended'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Events::class);
    }

    protected $casts = [
        'attended' => 'boolean',
    ];

    protected static function booted()
    {
        static::created(function ($eventRegistration) {
            // Notify the user when a new registration is created
            if ($eventRegistration->status === 'success') {
                $eventRegistration->user->notify(new EventNotification($eventRegistration->event, $eventRegistration));
            }
        });
        static::updated(function ($eventRegistration) {
            if ($eventRegistration->isDirty('status')) {
                // Notify the user only if the status has changed
                // dd($eventRegistration->status);
                $eventRegistration->user->notify(
                    new EventRegisterationStatusUpdateNotification($eventRegistration->event, $eventRegistration->status)
                );
            }
        });
    }

}
