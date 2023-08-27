<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function update(Request $request){
        $all = $request->all();
        unset($all['_token']);
        if(!isset($all['stream_message'])){
            $all['stream_message'] = 0;
        }
        foreach ($all as $key => $value) {
            $setting = Setting::where('key',$key)->first();
            if($setting == null){
                Setting::create([
                    'key' => $key,
                    'value' => $value
                ]);
            }else{
                $setting->update([
                    'value' => $value
                ]);
            }
        }
        return redirect()->back();
    }
}
