<?php

namespace App\Http\Controllers;

use App\Produsen;
use App\Product;
use App\Review;
use GH;
use Illuminate\Http\Request;

class ProdusenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function addProdusen(Request $request){
        $data = $request->all();
        $data['produsen_status'] = GH::$PRODUSEN_STATUS_PENDING;
        $data['verification_photo_path'] = GH::uploadFile($data['file'], 'merchant');
        $response = Produsen::create($data);
        $response = Produsen::where('id',$response->id)->first();
        if($response){
            $response['response_status'] = 1;
            $response['response_message'] = 'Success';
            return response()->json($response);                
        }else {
            $response['response_status'] = 0;
            $response['response_message'] = 'Failed add merchant data';
            return response()->json($response);       
        }
    }
}
