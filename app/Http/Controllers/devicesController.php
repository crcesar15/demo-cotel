<?php

namespace App\Http\Controllers;

use App\Device;
use App\DeviceType;
use Illuminate\Http\Request;

class devicesController extends Controller
{
    public function getDevices(){
        $data = [];
        $data[0] = \DB::table('ea_armarios')->limit(50)->get();
        $data[1] = \DB::table('ea_cajas_terminal')->limit(50)->get();
        $data[2] = \DB::table('ea_camaras')->limit(50)->get();
        $data[3] = \DB::table('ea_postes_secundario')->limit(50)->get();
        $data[4] = \DB::table('ea_catv_tap')->limit(50)->get();
        return response()->json($data);
    }

    public function getDevicesByType($device_type_id){
        switch ($device_type_id) {
            case 0:
                $data = \DB::table('ea_armarios')->limit(50)->get();
                break;
            case 1:
                $data = \DB::table('ea_cajas_terminal')->limit(50)->get();
                break;
            case 2:
                $data = \DB::table('ea_camaras')->limit(50)->get();
                break;
            case 3:
                $data = \DB::table('ea_postes_secundario')->limit(50)->get();
                break;
            case 4:
                $data = \DB::table('ea_catv_tap')->get();
                break;
            default:
                $data = [];
        }
        return response()->json($data);
    }

    public function getDevicesNear(Request $request){
        $data = [];
        $distance = $request->input('radio');
        $lat_min = $request->input('lat') - ($distance/111110);
        $lat_max = $request->input('lat') + ($distance/111110);
        $lng_min = $request->input('lng') - ($distance/111110);
        $lng_max = $request->input('lng') + ($distance/111110);
        if ($request->input('armario') == 1){
            $data[0] = \DB::table('ea_armarios')
                ->whereBetween('lat',[$lat_min,$lat_max])
                ->whereBetween('lng',[$lng_min,$lng_max])
                ->limit(50)
                ->get();
        }else{
            $data[0] = [];
        }

        if ($request->input('terminal') == 1){
            $data[1] = \DB::table('ea_cajas_terminal')
                ->whereBetween('lat',[$lat_min,$lat_max])
                ->whereBetween('lng',[$lng_min,$lng_max])
                ->limit(50)
                ->get();
        }else{
            $data[1] = [];
        }

        if ($request->input('camara') == 1){
            $data[2] = \DB::table('ea_camaras')
                ->whereBetween('lat',[$lat_min,$lat_max])
                ->whereBetween('lng',[$lng_min,$lng_max])
                ->limit(50)
                ->get();
        }else{
            $data[2] = [];
        }

        if ($request->input('poste') == 1){
            $data[3] = \DB::table('ea_postes_secundarios')
                ->whereBetween('lat',[$lat_min,$lat_max])
                ->whereBetween('lng',[$lng_min,$lng_max])
                ->limit(50)
                ->get();
        }else{
            $data[3] = [];
        }

        if ($request->input('tap') == 1){
            $data[4] = \DB::table('ea_catv_tap')
                ->whereBetween('lat',[$lat_min,$lat_max])
                ->whereBetween('lng',[$lng_min,$lng_max])
                ->limit(50)
                ->get();
        }else{
            $data[4] = [];
        }
        return response()->json($data);
    }
}
