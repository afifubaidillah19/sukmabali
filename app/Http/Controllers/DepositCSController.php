<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Transaction;
use App\Cart;
use App\TransactionItem;
use Illuminate\Http\Request;
use App\Produsen;
use App\Product;
use App\User;
use App\Bupda;
use App\ProductHistory;
use App\DepositCS;
use App\DepositSukmabali;
use App\CustomerService;
use App\DepositBupda;
use App\PenarikanDepositCS;
use App\DetailPenarikanDepositCS;
use App\Http\Controllers\ProductController;
use GH;

class DepositCSController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function getDepositList($cs_id){
        $deposit = DepositCS::where('cs_id', $cs_id)->get();
        return response()->json($deposit);
    }

    public function penarikanDeposit(Request $request){
        $data = $request->all();
        $penarikan['cs_id'] = $data['cs_id'];
        $penarikan['status'] = 0;

        $penarikanDepositCs = PenarikanDepositCS::create($penarikan);

        $data['deposit_list'] = json_decode($data['deposit_list']);
        foreach($data['deposit_list'] as $d){
            $detail = new DetailPenarikanDepositCS();
            $detail->penarikan_deposit_cs_id = $penarikanDepositCs->id;
            $detail->deposit_cs_id = $d->id;
            $detail->save();

            DepositCS::where('id', $d->id)->update(['status' => 1]);
        }

        return response()->json($penarikanDepositCs);
    }

    public function penarikanDepositList($cs_id){
        $penarikanDepositCS = PenarikanDepositCS::where('cs_id', $cs_id)
                ->with('detail_penarikan_deposit_cs_list',
                    'detail_penarikan_deposit_cs_list.deposit_cs')
                ->orderBy('penarikan_deposit_cs.id','desc')
                ->get();
        
        return response()->json($penarikanDepositCS);
    }
}
