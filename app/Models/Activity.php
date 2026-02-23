<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['destination_id', 'added_by', 'type', 'title', 'description', 'address', 'link', 'time', 'price', 'currency', 'order'];

    public function destination() { return $this->belongsTo(Destination::class); }
    public function author() { return $this->belongsTo(User::class, 'added_by'); }

    public function typeIcon(): string {
        return match($this->type) {
            'hotel' => '🏨',
            'poi' => '📍',
            'reservation' => '🎟️',
            'comment' => '💬',
            default => '📌',
        };
    }

    public function typeLabel(): string {
        return match($this->type) {
            'hotel' => 'Hotel / Accommodation',
            'poi' => 'Point of Interest',
            'reservation' => 'Reservation',
            'comment' => 'Comment / Note',
            default => 'Other',
        };
    }
}
