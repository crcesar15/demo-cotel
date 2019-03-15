<?php

namespace App\Http\Controllers;

use App\Device;
use Illuminate\Http\Request;

class devicesController extends Controller
{
    public function getDevices(){
        $data = Device::with('device_type')->get();
        return response()->json($data);
    }
}
