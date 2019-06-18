<?php

namespace App\Http\Controllers;

use App\Device;
use App\DeviceType;
use Illuminate\Http\Request;

class devicesController extends Controller
{
    public function getDevices(){
        $data = [];
        $data[0] = \DB::table('ea_armarios')->get();
        $data[1] = \DB::table('ea_cajas_terminal')->get();
        $data[2] = \DB::table('ea_camaras')->get();
        $data[3] = \DB::table('ea_postes_secundario')->get();
        $data[4] = \DB::table('ea_catv_tap')->get();
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
                $q->when($request->input('tap') == 1, function ($q){
                    return $q->orWhere('device_type_id',DeviceType::TAP);
                });
            })
            ->get();

        return response()->json($data);
    }
}
