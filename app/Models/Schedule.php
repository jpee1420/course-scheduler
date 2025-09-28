<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'room_id',
        'professor_id',
        'course_id',
        'start_time',
        'end_time',
        'day',
        'professor_status',
    ];

    protected $appends = ['start_time_formatted', 'end_time_formatted'];

    public function getStartTimeFormattedAttribute()
    {
        return $this->start_time ? date('H:i', strtotime($this->start_time)) : '';
    }

    public function getEndTimeFormattedAttribute()
    {
        return $this->end_time ? date('H:i', strtotime($this->end_time)) : '';
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(Professor::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
