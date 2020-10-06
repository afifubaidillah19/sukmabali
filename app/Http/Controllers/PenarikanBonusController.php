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
use App\Notification;
use App\Bonus;
use App\PenarikanBonus;
use App\Http\Controllers\ProductController;
use GH;
use DB;

class PenarikanBonusController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function penarikanBonus(Request $request){
        $user_id = $request->user_id;
        $user = User::find($user_id);

        $transaction = Transaction::select(DB::raw('SUM(total_payment) total_payment'), DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
            ->groupby('year','month')
            ->get();
        $can = true;
        foreach($transaction as $t){
            if($t->total_payment < 50000) {
                $can = false;
                break;
            }
        }
        
        
        if($can){
            $penarikanBonus['user_id'] = $user_id;
            $penarikanBonus['nama_bank'] = $request->nama_bank;
            $penarikanBonus['nama_pemilik_rekening'] = $request->nama_pemilik_rekening;
            $penarikanBonus['no_rekening'] = $request->no_rekening;

            $bonus = Bonus::where('user_id', $user_id)->where('status', 0)->get();
            $totalAmount = 0;
            foreach($bonus as $s){
                $totalAmount += $s->amount;
            }
            $penarikanBonus['amount'] = $totalAmount;
            $penarikanBonus['foto_bukti_transfer'] = "";
            
            if(PenarikanBonus::create($penarikanBonus) && 
            Bonus::where('user_id', $user_id)->where('status', 0)->update(['status' => 1])){
                $penarikanBonus['response_status'] = 1;
            }
        }else {
            $penarikanBonus['response_status'] = 0;
        }
        return $penarikanBonus;
    }
    public function getByUserId($user_id){
        $penarikanBonuses = PenarikanBonus::where('user_id', $user_id)->orderBy('id','desc')->get();
        return $penarikanBonuses;
    }
}