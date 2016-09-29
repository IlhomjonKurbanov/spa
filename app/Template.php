<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    //
    protected $fillable = [
        'name',
        'image_preview',
        'data',
        'status',
    ];

    protected $dates = ['created_at', 'updated_at'];
}
