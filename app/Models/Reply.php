<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
        'parent_id',
        'user_id',
        'messages',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
