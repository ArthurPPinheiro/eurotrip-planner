<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayRoute extends Model
{
    protected $fillable = ['day_id', 'transport_mode', 'total_distance_km', 'total_duration_minutes'];

    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function stops()
    {
        return $this->hasMany(RouteStop::class)->orderBy('order');
    }
}
