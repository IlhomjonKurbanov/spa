<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
    protected $fillable = [
        'name',
        'icon_sidebar',
        'icon_sidebar_hover',
        'main',
        'thumbnail',
        'description',
        'order'
    ];

    protected $dates = ['created_at', 'updated_at'];
}
