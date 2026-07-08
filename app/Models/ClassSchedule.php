<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassSchedule extends Model
{
    protected $fillable = [
        'training_class_id', 'date', 'start_time', 'end_time',
        'location', 'quota', 'booked_count',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function trainingClass(): BelongsTo
    {
        return $this->belongsTo(TrainingClass::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function remainingSlots(): int
    {
        return max(0, $this->quota - $this->booked_count);
    }

    public function isFull(): bool
    {
        return $this->remainingSlots() <= 0;
    }
}