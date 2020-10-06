<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\User;
use Illuminate\Http\Request;
use App\Setting;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function updateProfile(Request $request){
        $user_id = $request->user_id;
        $data['email'] = $request->email;
        $data['name'] = $request->full_name;
        User::where('id',$user_id)->update($data);
        $response['response_status'] = 1;
        $response['response_message'] = "success";

        return response()->json($response);
    }

    public function getAppInfo(Request $request){
        $setting = Setting::all()->toArray();
        $user = User::find($request->user_id);
        
        $session = [];
        $session['name'] = "Session";
        $session['attr'] = "session";
        if($user != null){
            $session['value'] = 1;
        }else {
            $session['value'] = 0;
        }
        array_push($setting, $session);

        return response()->json($setting);
    }
}
