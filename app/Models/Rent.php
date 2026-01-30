<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    protected $fillable = [
        'user_id',
        'property_type',
        'location',
        'number_of_bedrooms'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
