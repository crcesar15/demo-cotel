<?php

namespace App\Http\Controllers;

use App\Device;
use App\DeviceType;
use Illuminate\Http\Request;

class devicesController extends Controller
{
    public function getDevices(){
        $data = Device::with('device_type')->get();
        return response()->json($data);
    }

    public function getDevicesByType($_device_type_id){
        $data = Device::where('device_type_id', $_device_type_id)->get();
        return response()->json($data);
    }

    public function getDevicesNear(Request $request){
        $distance = $request->input('radio');
        $lat_min = $request->input('lat') - ($distance/111110);
        $lat_max = $request->input('lat') + ($distance/111110);
        $lng_min = $request->input('lng') - ($distance/111110);
        $lng_max = $request->input('lng') + ($distance/111110);
        $data = Device::with('device_type')
            ->whereBetween('lat',[$lat_min,$lat_max])
            ->whereBetween('lng',[$lng_min,$lng_max])
            ->where(function($q) use ($request){
                $q->when($request->input('terminal') == 1, function ($q){
                    return $q->orWhere('device_type_id',DeviceType::TERMINAL);
                });
                $q->when($request->input('tab') == 1, function ($q){
                    return $q->orWhere('device_type_id',DeviceType::TAB);
                });
            })
            ->get();

        return response()->json($data);
    }
}
