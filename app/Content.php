<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    //
    protected $fillable = ['menu', 'menu_type', 'content', 'icon', 'image', 'main', 'description', 'status'];

    protected $dates = ['created_at', 'updated_at'];
}
