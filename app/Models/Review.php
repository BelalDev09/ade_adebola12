<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'parent_id',
        'location_id',
        'user_id',
        'rating',
        'role',
        'description',
        'messages',
        'medias',
        'likes',
    ];

    protected $casts = [
        'medias' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(ReviewLike::class);
    }

    public function replies()
    {
        return $this->hasMany(Review::class, 'parent_id');
    }

    // public function locations()
    // {
    //     return $this->belongsToMany(Location::class, 'review_location', 'review_id', 'location_id');
    // }
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'location_review');
    }
    // web reltion
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
