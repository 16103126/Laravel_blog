<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tagimage extends Model
{
    protected $fillable = [
        'tag_id', 'image'
    ];
}
