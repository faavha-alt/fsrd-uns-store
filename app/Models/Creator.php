<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Creator extends Model
{
    protected $fillable = [
        'name', 'photo', 'type', 'department', 'bio',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function trainingClasses(): HasMany
    {
        return $this->hasMany(TrainingClass::class);
    }
}