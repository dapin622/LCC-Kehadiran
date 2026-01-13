<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'team_id',
        'school_id',
        'start_date',
        'end_date',

        'attendance_token',
        'attendance_start',
        'attendance_end',
        'is_attendance_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',

        'attendance_start' => 'datetime',
        'attendance_end' => 'datetime',
        'is_attendance_active' => 'boolean',
    ];
    
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }


}
