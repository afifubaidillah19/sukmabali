<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Bupda;
use App\Produsen;
use App\Review;
use App\Product;
use App\ProductCategory;
use App\Transaction;
use App\TransactionItem;
use App\ProductHistory;
use App\User;
use App\Slider;
use GH;
use Illuminate\Http\Request;
use DB;
use App\JamBuka;
use App\Notification;

class JamBukaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function get($produsen_id){
        $jamBukaList = JamBuka::where('produsen_id', $produsen_id)->get();
        
        if(sizeOf($jamBukaList) == 0){
            $senin['produsen_id'] = $produsen_id;
            $senin['hari'] = "senin";
            $senin['status'] = 0;
            $senin['jam_buka'] = "07:00";
            $senin['jam_tutup'] = "16:00";
            JamBuka::create($senin);

            $selasa['produsen_id'] = $produsen_id;
            $selasa['hari'] = "selasa";
            $selasa['status'] = 0;
            $selasa['jam_buka'] = "07:00";
            $selasa['jam_tutup'] = "16:00";
            JamBuka::create($selasa);

            $rabu['produsen_id'] = $produsen_id;
            $rabu['hari'] = "rabu";
            $rabu['status'] = 0;
            $rabu['jam_buka'] = "07:00";
            $rabu['jam_tutup'] = "16:00";
            JamBuka::create($rabu);

            $kamis['produsen_id'] = $produsen_id;
            $kamis['hari'] = "kamis";
            $kamis['status'] = 0;
            $kamis['jam_buka'] = "07:00";
            $kamis['jam_tutup'] = "16:00";
            JamBuka::create($kamis);

            $jumat['produsen_id'] = $produsen_id;
            $jumat['hari'] = "jumat";
            $jumat['status'] = 0;
            $jumat['jam_buka'] = "07:00";
            $jumat['jam_tutup'] = "16:00";
            JamBuka::create($jumat);

            $sabtu['produsen_id'] = $produsen_id;
            $sabtu['hari'] = "sabtu";
            $sabtu['status'] = 0;
            $sabtu['jam_buka'] = "07:00";
            $sabtu['jam_tutup'] = "16:00";
            JamBuka::create($sabtu);

            $minggu['produsen_id'] = $produsen_id;
            $minggu['hari'] = "minggu";
            $minggu['status'] = 0;
            $minggu['jam_buka'] = "07:00";
            $minggu['jam_tutup'] = "16:00";
            JamBuka::create($minggu);
            
            $jamBukaList = JamBuka::where('produsen_id', $produsen_id)->get();
        }
        return response()->json($jamBukaList);
    }

    public function update(Request $request){
        $jamBukaList = json_decode($request->jamBukaList);
        $produsenId = $request->produsen_id;
        foreach($jamBukaList as $j){
            JamBuka::where('produsen_id',$produsenId)
                ->where('id', $j->id)
                ->update([
                    'status' => $j->status,
                    'jam_buka' => $j->jam_buka,
                    'jam_tutup' => $j->jam_tutup
                ]);
        }
        return 1;
    }
}
