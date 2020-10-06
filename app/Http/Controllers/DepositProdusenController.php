<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\User;
use Illuminate\Http\Request;
use App\Produsen;
use App\TempDesaAdat;
use App\CustomerService;
use App\Transaction;
use App\TransactionItem;
use App\Product;
use App\DepositProdusen;
use App\ProductHistory;
use App\DepositBupda;
use App\DepositCS;
use App\DepositSukmabali;
use App\Bupda;
use App\DesaAdat;
use GH;

class DepositProdusenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function depositProdusen($produsen_id){
        $result['deposit_list'] = DepositProdusen::where('produsen_id', $produsen_id)
                    ->select('deposit_produsen.*')
                    ->get();
        $result['deposit'] = DepositProdusen::where('produsen_id', $produsen_id)
                    ->select('deposit_produsen.*')
                    ->sum('total_deposit');
        return response()->json($result);
    }

    public function tambahDeposit(Request $request){
        $data['produsen_id'] = $request->produsen_id;
        $data['status'] = 0;
        $data['foto_bukti_transfer'] =  GH::uploadFile($request->file('file'), 'product');

        return DepositProdusen::create($data);
    }
}
