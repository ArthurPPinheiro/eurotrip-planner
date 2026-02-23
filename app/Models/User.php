<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'avatar_color'];
    protected $hidden = ['password', 'remember_token'];

    public function trips() {
        return $this->belongsToMany(Trip::class)->withPivot('role')->withTimestamps();
    }

    public function createdTrips() {
        return $this->hasMany(Trip::class, 'created_by');
    }

    public function initials(): string {
        return collect(explode(' ', $this->name))
            ->map(fn($w) => strtoupper($w[0]))
            ->take(2)
            ->implode('');
    }
}
