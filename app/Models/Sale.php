<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'user_id',
        'property_type',
        'location',
        'property_size',
        'number_of_bedrooms'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
