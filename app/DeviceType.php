<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\DeviceType
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeviceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeviceType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeviceType query()
 * @mixin \Eloquent
 */

class DeviceType extends Model
{
    CONST TERMINAL = 1;
    CONST TAP = 2;

    public function devices(){
        return $this->hasMany(Device::class);
    }
}
