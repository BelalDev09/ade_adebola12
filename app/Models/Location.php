<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['title', 'latitude', 'longitude'];

    public function reviews()
    {
        return $this->belongsToMany(Review::class, 'location_review');
    }
}
