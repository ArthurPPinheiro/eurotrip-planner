<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    protected $fillable = ['trip_id', 'date', 'day_number', 'title'];
    protected $casts = ['date' => 'date'];

    public function trip() { return $this->belongsTo(Trip::class); }
    public function destinations() { return $this->hasMany(Destination::class)->orderBy('order'); }
    public function route() { return $this->hasOne(DayRoute::class); }
}
