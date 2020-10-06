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

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function getHomeData(Request $request){
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $user = User::find($request->user_id);
        $response['slider_list'] = [];
        $response['bupda_anda'] = null;
        if($user == null || $user->desa_adat_id == 0){
            //bupda_product_list
            $response['bupda_product_list'] = [];

            //other products
            $response['other_product_list'] = Bupda::join('desa_adat','desa_adat.id','bupda.desa_adat_id')
                            ->join('produsen','produsen.bupda_id','bupda.id')
                            ->join('product','product.produsen_id','produsen.id')
                            ->where('product_status',GH::$PRODUCT_STATUS_ENABLED)
                            ->select('product.*')
                            ->limit(5)
                            ->get();
            //slider_list
            $response['slider_list'] = Slider::where([
                ['status', 1],
                ['type_user','administrator']
            ])->limit(5)->get();
        }else{
            //bupda_anda
            $response['bupda_anda'] = Bupda::join('desa_adat', 'desa_adat.id','bupda.desa_adat_id')
                                ->where('bupda.desa_adat_id', $user->desa_adat_id)
                                ->select('bupda.*')->first();
            //bupda_product_list
            $response['bupda_product_list'] = Bupda::join('desa_adat','desa_adat.id','bupda.desa_adat_id')
                                ->join('produsen','produsen.bupda_id','bupda.id')
                                ->join('product','product.produsen_id','produsen.id')
                                ->where('bupda.desa_adat_id', $user->desa_adat_id)
                                ->where('product_status',GH::$PRODUCT_STATUS_ENABLED)
                                ->select('product.*')
                                ->get();

            //other product
            $response['other_product_list'] = Bupda::join('desa_adat','desa_adat.id','bupda.desa_adat_id')
                                ->join('produsen','produsen.bupda_id','bupda.id')
                                ->join('product','product.produsen_id','produsen.id')
                                ->where('bupda.desa_adat_id','!=', $user->desa_adat_id)
                                ->where('product_status',GH::$PRODUCT_STATUS_ENABLED)
                                ->select('product.*')
                                ->limit(5)
                                ->get();
    
            //slider_list
            $free = 2;
            $sliderBupda = Slider::where([
                ['status',1],
                ['type_user','bupda']
            ])->limit(2)->get();
            
            if(sizeOf($sliderBupda) == 2) $free = 0;
            else if(sizeOf($sliderBupda) == 1) $free = 1;
            else $free = 2;

            $sliderPemprov = Slider::where([
                ['status',1],
                ['type_user','pemprov']
            ])->limit(2)->get();

            if(sizeOf($sliderPemprov) == 2) $free += 0;
            else if(sizeOf($sliderPemprov) == 1) $free += 1;
            else $free += 2;

            $sliderAdmin = Slider::where([
                ['status',1],
                ['type_user','administrator']
            ])->limit($free+1)->get();
            
            foreach($sliderAdmin as $s){
                array_push($response['slider_list'], $s);
            }
            foreach($sliderPemprov as $s){
                array_push($response['slider_list'], $s);
            }
            foreach($sliderBupda as $s){
                array_push($response['slider_list'], $s);
            }
        }
        for($i=0; $i<sizeOf($response['bupda_product_list']); $i++){
            $bupda = Bupda::join('produsen','produsen.bupda_id','bupda.id')
                        ->join('product','product.produsen_id','produsen.id')
                        ->where('produsen.id', $response['bupda_product_list'][$i]['produsen_id'])
                        ->select('bupda.*')
                        ->first();
            $bupda['distance'] = floatval(number_format((float)GlobalHelper::haversineGreatCircleDistance($latitude, $longitude,
                doubleval($bupda->latitude), doubleval($bupda->longitude)), 2, '.', ''));
            $response['bupda_product_list'][$i]['bupda'] = $bupda;
            $response['bupda_product_list'][$i]['produsen'] = Produsen::find($response['bupda_product_list'][$i]['produsen_id']);
        }

        for($i=0; $i<sizeOf($response['other_product_list']); $i++){
            $bupda = Bupda::join('produsen','produsen.bupda_id','bupda.id')
                        ->join('product','product.produsen_id','produsen.id')
                        ->where('produsen.id', $response['other_product_list'][$i]['produsen_id'])
                        ->select('bupda.*')
                        ->first();
            $bupda['distance'] = floatval(number_format((float)GlobalHelper::haversineGreatCircleDistance($latitude, $longitude,
                doubleval($bupda->latitude), doubleval($bupda->longitude)), 2, '.', ''));
            $response['other_product_list'][$i]['bupda'] = $bupda;
            $response['other_product_list'][$i]['produsen'] = Produsen::find($response['other_product_list'][$i]['produsen_id']);
        }


        //bupda_list
        $bupda = Bupda::all();
        $bupdaTemp = [];

        for($i=0; $i<sizeOf($bupda); $i++){
            $rating = Review::join('transaction_item','transaction_item.transaction_item_id','review.transaction_item_id')
                      ->join('transaction','transaction.transaction_id','transaction_item.transaction_id')
                      ->where('bupda_id',$bupda[$i]['id'])
                      ->get();
            
            $countRating = sizeOf($rating);
            $sumRating = $rating->sum('rating');
            $resultRating = $countRating == 0 ? 0 : $sumRating / $countRating;
            $resultRating = floatval(number_format((float)($resultRating), 1, '.', ''));
            if($resultRating == 0) $resultRating = 0;
            $bupda[$i]['rating'] = $resultRating;
            if($latitude == null || $longitude == null){
                $bupda[$i]['distance'] = 0.0;
            }else {
                $bupda[$i]['distance'] = floatval(number_format((float)GlobalHelper::haversineGreatCircleDistance($latitude, $longitude,
                    doubleval($bupda[$i]->latitude), doubleval($bupda[$i]->longitude)), 2, '.', ''));
            }
            array_push($bupdaTemp, $bupda[$i]);
        }
        usort($bupdaTemp, function($a, $b) {
            if(  $a->distance ==  $b->distance ){ return 0 ; } 
            return ($a->distance < $b->distance) ? -1 : 1;
        });

        $response['bupda_list'] = $bupdaTemp;

        //product_category_list
        $response['product_category_list'] = ProductCategory::limit(6)->get();

        return response()->json($response);
    }

    public function getMerchantHomeData(Request $request){
        $produsen_id = $request->produsen_id;

        $transactionItem = TransactionItem::join('product','product.id','transaction_item.product_id')
                                ->join('produsen','produsen.id','product.produsen_id')
                                ->join('transaction','transaction.transaction_id','transaction_item.transaction_id')
                                ->where('transaction_item.transaction_status', 3)
                                ->where('transaction.transaction_status', 2)
                                ->where('produsen.id', $produsen_id)
                                ->select('transaction_item.*')
                                ->get();

        $response['pendapatan'] = 0;
        $response['jumlah_barang_terjual'] = 0;
        foreach($transactionItem as $item){
            $response['pendapatan'] += ($item->price * $item->quantity);
            $response['jumlah_barang_terjual']+= $item->quantity;
        }

        $transaction = TransactionItem::join('product','product.id','transaction_item.product_id')
                ->join('produsen','produsen.id','product.produsen_id')
                ->join('transaction','transaction.transaction_id', 'transaction_item.transaction_id')
                ->groupBy('transaction.transaction_id')
                ->where('transaction.transaction_status', 2)
                ->where('produsen.id', $produsen_id)->get();

        $response['total_transaksi'] = sizeOf($transaction);


        $response['jumlah_barang_belum_terjual'] = $response['total_stok_barang_pending'] = ProductHistory::join('product','product.id','product_history.product_id')
                                        ->where('product.produsen_id', $produsen_id)
                                        ->where('product_history.status', 1)
                                        ->where('product.product_status', '!=', 3)
                                        ->sum('product_history.stok');

        $response['total_stok_barang'] = ProductHistory::join('product','product.id','product_history.product_id')
                                    ->where('product.produsen_id', $produsen_id)
                                    ->where('product.product_status', '!=', 3)
                                    ->sum('product_history.stok');

        $produkPending = Product::where('produsen_id', $produsen_id)
                            ->where('product_status', GH::$PRODUCT_STATUS_PENDING)->get();
        
        $response['total_stok_barang_pending'] = ProductHistory::join('product','product.id','product_history.product_id')
                                                    ->where('product.produsen_id', $produsen_id)
                                                    ->where('product_history.status', 0)
                                                    ->where('product_history.type', 1)
                                                    ->sum('product_history.stok');
        return response()->json($response);
    }
    
}
