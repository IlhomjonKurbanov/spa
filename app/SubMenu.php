<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    //
    protected $fillable = ['name', 'icon', 'icon_hover', 'main', 'description', 'order', 'status', 'parent', 'parent_type', 'path', 'rank'];

    protected $dates = ['created_at', 'updated_at'];
}
