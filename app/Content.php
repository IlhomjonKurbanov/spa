<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    //
    protected $fillable = ['menu', 'menu_type', 'content', 'icon', 'image', 'main', 'description', 'status', 'name', 'path', 'rank'];

    protected $dates = ['created_at', 'updated_at'];

    public function getImageAttribute()
    {
        $imagesJson = $this->attributes['image'];

        $images = json_decode($imagesJson, true);

        return $images;
    }

    public function getParentMenuAttribute()
    {
        $menuType = $this->attributes['menu_type'];

        $returnArr = [];

        if($menuType == 1)
        {
            $returnArr['menu_type'] = 'Menu chính';
            $returnArr['menu'] = Menu::find($this->attributes['menu']);
        } else {
            $returnArr['menu_type'] = 'Menu con';
            $returnArr['menu'] = SubMenu::find($this->attributes['menu']);
        }

        return $returnArr;
    }
}
