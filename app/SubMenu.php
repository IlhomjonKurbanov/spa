<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    //
    protected $fillable = ['name', 'icon', 'icon_hover', 'main', 'description', 'order'];

    protected $dates = ['created_at', 'updated_at'];
}
