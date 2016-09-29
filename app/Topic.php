<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    //
    protected $fillable = [
        'name',
        'parent_id',

        'template',

        'data',
        'status',

    ];

    protected $dates = ['created_at', 'updated_at','match_timestamp'];
}
