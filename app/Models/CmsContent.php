<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsContent extends Model
{
  
    protected $table = 'cms_contents';

    protected $fillable = [
        'page_slug',
        'section',
        'type',
        'title',
        'description',
        'content',
        'image_path',
        'btn_text',
        'btn_link',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'order'  => 'integer',
    ];
}
