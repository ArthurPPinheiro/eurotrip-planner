<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['trip_id', 'user_id', 'type', 'title', 'file_path', 'original_name', 'mime_type', 'size', 'expires_at', 'notes'];

    public function trip() { return $this->belongsTo(Trip::class); }
    public function owner() { return $this->belongsTo(User::class, 'user_id'); }

    public function typeIcon(): string {
        return match($this->type) {
            'passport' => '🛂',
            'visa' => '📋',
            'insurance' => '🏥',
            'ticket' => '✈️',
            default => '📄',
        };
    }

    public function formattedSize(): string {
        if (!$this->size) return '';
        if ($this->size >= 1048576) return round($this->size / 1048576, 1) . ' MB';
        return round($this->size / 1024, 1) . ' KB';
    }
}
