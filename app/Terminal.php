<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    protected $fillable = ['name' , 'lat' , 'lng' , 'connections' , 'busy'];
    //
}
