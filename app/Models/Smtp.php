<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Smtp extends Model
{
    protected $fillable = [
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
    ];

    protected $casts = [
        'mail_port' => 'string',
    ];
}
