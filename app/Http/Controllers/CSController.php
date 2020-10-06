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
use App\ProductHistory;
use App\Bupda;
use App\DesaAdat;
use GH;

class CSController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function addCS(Request $request){
        $data = $request->all();
        $result = CustomerService::create($data);

        return response()->json($result);
    }

    public function batalCS($cs_id){
        $result = CustomerService::where('id', $cs_id)->update(['status' => 2]);

        return response()->json($result);
    }

    public function detailCS($cs_id){
        $result = CustomerService::where('id', $cs_id)
                ->first();
        
    
        return response()->json($result);
    }

    public function homeData($cs_id){
        $result = [];

        $cs = CustomerService::where('id', $cs_id)->first();
        $allTransaction = Transaction::where('bupda_id', $cs->bupda_id)->get();


        $totalTransaksi['name'] = "Total Transaksi";
        $totalTransaksi['count'] = 0;
        $totalTransaksi['type'] = 0;
        $totalTransaksi['list'] = [];

        $totalTransaksiPending['name'] = "Total Transaksi Pending";
        $totalTransaksiPending['count'] = 0;
        $totalTransaksiPending['type'] = 0;
        $totalTransaksiPending['list'] = [];

        $totalTransaksiSedangDiproses['name'] = "Total Transaksi Sedang Diproses";
        $totalTransaksiSedangDiproses['count'] = 0;
        $totalTransaksiSedangDiproses['type'] = 0;
        $totalTransaksiSedangDiproses['list'] = [];

        $totalTransaksiSelesai['name'] = "Total Transaksi Selesai";
        $totalTransaksiSelesai['count'] = 0;
        $totalTransaksiSelesai['type'] = 0;
        $totalTransaksiSelesai['list'] = [];

        $totalTransaksiBatal['name'] = "Total Transaksi Batal";
        $totalTransaksiBatal['count'] = 0;
        $totalTransaksiBatal['type'] = 0;
        $totalTransaksiBatal['list'] = [];


        
        foreach($allTransaction as $t){
            $totalTransaksi['count']++;
            array_push($totalTransaksi['list'], $t);
            
            $bupda = Bupda::find($t->bupda_id);
            $transactionItem = TransactionItem::where('transaction_id', $t->transaction_id)
                        ->with('product','product.produsen')
                        ->get()->toArray();
            $produsen_list = [];
            foreach($transactionItem as $i){
                $produsen = $i['product']['produsen'];
                $ti = TransactionItem::join('product','product.id','transaction_item.product_id')
                            ->join('produsen','produsen.id','product.produsen_id')
                            ->where('produsen.id',$produsen['id'])
                            ->where('transaction_item.transaction_item_id', $i['transaction_item_id'])
                            ->select('transaction_item.*')
                            ->with('product')
                            ->get();
                $produsen['transaction_item'] = $ti;
                array_push($produsen_list, $produsen);
            }
            $bupda['produsen_list'] = $produsen_list;
            $t['bupda'] = $bupda;
            if($t['transaction_status'] == 0){
                $totalTransaksiPending['count']++;
                array_push($totalTransaksiPending['list'], $t);
            }else if($t['transaction_status'] == 1){
                $totalTransaksiSedangDiproses['count']++;
                array_push($totalTransaksiSedangDiproses['list'], $t);
            }else if($t['transaction_status'] == 2){
                $totalTransaksiSelesai['count']++;
                array_push($totalTransaksiSelesai['list'], $t);
            }else {
                $totalTransaksiBatal['count']++;
                array_push($totalTransaksiBatal['list'], $t);
            }
        }

        $totalTransaksi['list'] = json_encode($totalTransaksi['list']);
        array_push($result, $totalTransaksi);
        $totalTransaksiPending['list'] = json_encode($totalTransaksiPending['list']);
        array_push($result, $totalTransaksiPending);
        $totalTransaksiSedangDiproses['list'] = json_encode($totalTransaksiSedangDiproses['list']);
        array_push($result, $totalTransaksiSedangDiproses);
        $totalTransaksiSelesai['list'] = json_encode($totalTransaksiSelesai['list']);
        array_push($result, $totalTransaksiSelesai);
        $totalTransaksiBatal['list'] = json_encode($totalTransaksiBatal['list']);
        array_push($result, $totalTransaksiBatal);

        return $result;
    }
}
