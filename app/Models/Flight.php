<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'day_id',
        'flight_number',
        'airline',
        'locator',
        'departure_airport',
        'departure_city',
        'arrival_airport',
        'arrival_city',
        'departure_time',
        'arrival_time',
        'duration_minutes',
        'seat',
        'cabin_class',
        'notes',
    ];

    public function day()
    {
        return $this->belongsTo(Day::class);
    }
}
