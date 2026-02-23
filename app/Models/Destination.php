<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = ['day_id', 'trip_id', 'city', 'country', 'emoji', 'order'];

    public function day() { return $this->belongsTo(Day::class); }
    public function activities() { return $this->hasMany(Activity::class)->orderBy('order'); }

    public function hotels() { return $this->activities()->where('type', 'hotel'); }
    public function pois() { return $this->activities()->where('type', 'poi'); }
    public function reservations() { return $this->activities()->where('type', 'reservation'); }
    public function comments() { return $this->activities()->where('type', 'comment'); }
}
