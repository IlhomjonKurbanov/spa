<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    protected $fillable = [
        'name', 'link', 'image'
    ];

    protected $dates = ['created_at', 'updated_at'];
}
