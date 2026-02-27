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
            'hotel' => __('trips.activity_type.hotel'),
            'poi' => __('trips.activity_type.poi'),
            'reservation' => __('trips.activity_type.reservation'),
            'comment' => __('trips.activity_type.comment'),
            default => __('trips.activity_type.poi'),
        };
    }
}
