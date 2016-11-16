<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intro extends Model
{
    //

    protected $fillable = ['content', 'icon', 'image', 'main', 'description', 'status', 'name'];

    protected $dates = ['created_at', 'updated_at'];

    public function getImageAttribute()
    {
        $imagesJson = $this->attributes['image'];

        $images = json_decode($imagesJson, true);

        return $images;
    }

}
