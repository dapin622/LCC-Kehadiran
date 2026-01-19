<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    use HasFactory;

      protected $fillable = [
        'event_id',
        'member_id',
        'status',
        'attended_at',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function getAttendanceStatusAttribute()
    {
        if (!$this->attended_at) {
            return 'belum_absen';
        }

        if ($this->status === 'izin') {
            return 'izin';
        }

        if ($this->event && $this->event->attendance_end) {
            if ($this->attended_at->gt($this->event->attendance_end)) {
                return 'terlambat';
            }
        }

        return 'hadir';
    }

}
