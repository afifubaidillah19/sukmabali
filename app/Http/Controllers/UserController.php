<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\User;
use Illuminate\Http\Request;
use App\Produsen;
use App\TempDesaAdat;
use App\Bupda;
use App\DesaAdat;
use App\CustomerService;
use App\Bonus;
use GH;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function test(Request $request){
        return "hello";
    }

    function generateKodeUpline($digits = 5){
        $i = 0; //counter
        $kode = ""; //our default pin is blank.
        while($i < $digits){
            //generate a random number between 0 and 9.
            $kode .= mt_rand(0, 9);
            $i++;
        }
        return $kode;
    }

    public function addUser(Request $request){
        $temp = $request->all();
        
        $kodeUpline = $temp['kode_upline'];
        if($kodeUpline){
            $parent = User::where('kode_upline', $kodeUpline)->first();
            if($parent){
                $data['gen_no'] = $parent->gen_no + 1;
                $data['parent_id'] = $parent->id;
            }else {
                $data['gen_no'] = 1;    
            }
        }else {
            $data['gen_no'] = 1;
        }

        $data['kode_upline'] = $this->generateKodeUpline(6);
        //$data['firebase_user_id'] = $temp['firebase_user_id'];
        $data['name'] = $temp['name'];
        $data['email'] = $temp['email'];
        $data['ktp_path'] = GH::uploadFile($temp['file'], '');
        $data['phone_number'] = $temp['phone_number'];
        $data['desa_adat_id'] = $temp['desa_adat_id'];

        $temp_desa_adat = $temp['temp_desa_adat_name'];
        $user = User::create($data);
        if($user->desa_adat_id == 0){
            $desa['nama'] = $temp_desa_adat;
            $desa['user_id'] = $user->id;
            $desa['type_user'] = 0;
            TempDesaAdat::insert($desa);
        }else {
            $desaAdat = DesaAdat::find($user->desa_adat_id);
            $user['desa_adat'] = $desaAdat;
            $user['bupda'] = Bupda::join('desa_adat','desa_adat.id','bupda.desa_adat_id')
                                ->where('desa_adat.id', $user->desa_adat_id)
                                ->select('bupda.*')
                                ->first();
        }
        return response()->json($user);
    }

    public function cekUser(Request $request){
        $phone_number = $request->phone_number;
        $response = User::where('phone_number',$phone_number)->first();
        if($response['desa_adat_id'] != 0){
            $desaAdat = DesaAdat::find($response['desa_adat_id']);
            $response['desa_adat'] = $desaAdat;
        }

        $statusKodeUpline = 0;
        $kodeUpline = $request['kode_upline'];
        if($kodeUpline){
            $parent = User::where('kode_upline', $kodeUpline)->first();
            if($parent){
                $statusKodeUpline = 1;
            }
        }else if($kodeUpline == "TOPGEN"){
            $statusKodeUpline = 1;
        }

        if($statusKodeUpline == 0){
            $response['response_status'] = 2;
        }else {
            $response['response_status'] = 1;
        }
        return response()->json($response);
    }

    public function getUserDetail($id){
        $response = User::where('id',$id)->first();
        if($response['desa_adat_id'] != 0){
            $desaAdat = DesaAdat::find($response['desa_adat_id']);
            $response['desa_adat'] = $desaAdat;
            $response['bupda'] = Bupda::join('desa_adat','desa_adat.id','bupda.desa_adat_id')
                                ->where('desa_adat.id', $desaAdat->id)
                                ->select('bupda.*')
                                ->first();
        }
        $response['customer_service'] = CustomerService::where('user_id', $response['id'])
                                            ->where('status', '!=', 2)
                                            ->first();
        $cs = CustomerService::where('desa_adat_id', $response['desa_adat_id'])
                    ->where('status', 1)
                    ->first();

        $response['has_cs'] = ($cs != null);
        $response['produsen'] = Produsen::where([
            ['user_id', $response['id']]
        ])->first();
        $bonuses = Bonus::where('user_id', $id)->where('status', 0)->get();
        $response['bonuses'] = $bonuses;
        $jumlahBonus = 0;
        foreach($bonuses as $b){
            $jumlahBonus+=$b->amount;
        }
        $response['jumlah_bonus'] = $jumlahBonus;
        if(!$response->kode_upline){
            $kodeUpline = $this->generateKodeUpline(6);
            User::where('id', $id)->update(['kode_upline' => $kodeUpline]);
            $response['kode_upline'] = $kodeUpline;

        }
        return response()->json($response);
    }

    public function updateDeliveryFee(Request $request){
        $id = $request->id;
        $deliveryFee = $request->delivery_fee;

        $produsen = Produsen::where('user_id',$id)->update([
            'delivery_fee_per_km' => $deliveryFee
        ]);
        
        if($produsen){
            return response()->json(1);
        }else {
            return response()->json(0);
        }
    }
}
