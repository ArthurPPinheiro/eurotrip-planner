<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Trip extends Model
{
    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'invite_code', 'created_by'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    protected static function boot() {
        parent::boot();
        static::creating(function ($trip) {
            $trip->invite_code = Str::upper(Str::random(8));
        });
    }

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function members() { return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps(); }
    public function days() { return $this->hasMany(Day::class)->orderBy('day_number'); }
    public function documents() { return $this->hasMany(Document::class); }
    public function destinations() { return $this->hasMany(Destination::class); }

    public function getDurationAttribute(): int {
        if (!$this->start_date || !$this->end_date) return 0;
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}
