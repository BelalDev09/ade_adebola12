<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'review_id',
        'user_id',
        'reason_code',
        'description',
        'medias',
        'status'
    ];

    protected $casts = [
        'medias' => 'array'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // web
    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
