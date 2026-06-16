<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'ingredients',
        'cooking_steps',
        'cooking_time',
        'category',
        'image',
    ];

    protected $hidden = ['image'];

    protected $appends = ['image_url'];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => isset($attributes['image']) && $attributes['image']
                ? asset('storage/' . $attributes['image'])
                : null,
        );
    }
}