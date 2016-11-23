<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntroOutside extends Model
{
    //
    protected $table = 'intros_outside';

    protected $fillable = ['name', 'icon', 'main'];

    protected $dates = ['created_at', 'updated_at'];
}
