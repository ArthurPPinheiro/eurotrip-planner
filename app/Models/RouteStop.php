<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteStop extends Model
{
    protected $fillable = ['day_route_id', 'city', 'country', 'latitude', 'longitude', 'order'];

    public function route()
    {
        return $this->belongsTo(DayRoute::class, 'day_route_id');
    }
}
