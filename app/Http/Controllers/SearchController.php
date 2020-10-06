<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\ProductCategory;
use App\Bupda;
use App\Review;
use App\User;
use App\SearchHistory;
use App\Produsen;
use GH;
use App\Helpers\GlobalHelper;
use DB;
class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function generalSearch(Request $request){
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $keyword = $request->keyword;
        $user = User::where('id', $request->user_id)->first();
        $response['product_list'] = [];
        $response['product_category_list'] = [];
        $response['produsen_list'] = [];
        if($user){
            $response['product_list'] = Product::join('produsen','produsen.id','product.produsen_id')
                            ->join('bupda','bupda.id','produsen.bupda_id')
                            ->join('product_history','product_history.product_id','product.id')
                            ->where([
                                ['product_status',GH::$PRODUCT_STATUS_ENABLED],
                                ['bupda.desa_adat_id',$user->desa_adat_id],
                                ['product.stok','>',0], 
                                ['product.name','like','%'.$keyword.'%'],
                            ])
                            ->orWhere([
                                ['product_status',GH::$PRODUCT_STATUS_ENABLED],
                                ['bupda.desa_adat_id',$user->desa_adat_id],
                                ['product.stok','>',0], 
                                ['product.description','like','%'.$keyword.'%'],
                            ])
                            ->select('product.*')
                            ->groupBy('product.id')->get()->toArray();

            $response['produsen_list'] = Produsen::join('bupda','bupda.id','produsen.bupda_id')
                            ->Where([
                                ['bupda.desa_adat_id',$user->desa_adat_id],
                                ['produsen.produsen_name', 'like','%'.$keyword.'%']
                            ])
                            ->select('produsen.*')
                            ->groupBy('produsen.id')->get()->toArray();

            $otherProduct = Product::join('produsen','produsen.id','product.produsen_id')
                        ->join('bupda','bupda.id','produsen.bupda_id')
                        ->join('product_history','product_history.product_id','product.id')
                        ->where([
                            ['product_status',GH::$PRODUCT_STATUS_ENABLED],
                            ['bupda.desa_adat_id','!=',$user->desa_adat_id],
                            ['product.stok','>',0], 
                            ['product.name','like','%'.$keyword.'%'],
                        ])
                        ->orWhere([
                            ['product_status',GH::$PRODUCT_STATUS_ENABLED],
                            ['bupda.desa_adat_id','!=',$user->desa_adat_id],
                            ['product.stok','>',0], 
                            ['product.description','like','%'.$keyword.'%'],
                        ])
                        ->select('product.*')
                        ->groupBy('product.id')
                        ->get()->toArray();
            
            $otherWarungList = Produsen::join('bupda','bupda.id','produsen.bupda_id')
                    ->Where([
                        ['bupda.desa_adat_id','!=',$user->desa_adat_id],
                        ['produsen.produsen_name', 'like','%'.$keyword.'%']
                    ])
                    ->select('produsen.*')
                    ->groupBy('produsen.id')->get()->toArray();
            foreach($otherProduct as $p){
                array_push($response['product_list'], $p);
            }

            foreach($otherWarungList as $w){
                array_push($response['produsen_list'], $w);
            }
        }else {
            $response['product_list'] = Product::join('produsen','produsen.id','product.produsen_id')
                            ->join('bupda','bupda.id','produsen.bupda_id')
                            ->join('product_history','product_history.product_id','product.id')
                            ->where([
                                ['product_status',GH::$PRODUCT_STATUS_ENABLED],
                                ['product.stok','>',0], 
                                ['product.name','like','%'.$keyword.'%'],
                            ])
                            ->orWhere([
                                ['product_status',GH::$PRODUCT_STATUS_ENABLED],
                                ['product.stok','>',0], 
                                ['product.description','like','%'.$keyword.'%'],
                            ])
                            ->select('product.*')
                            ->groupBy('product.id')->get()->toArray();

            $response['produsen_list'] = Produsen::Where([
                            ['produsen.produsen_name','like','%'.$keyword.'%'],
                        ])->groupBy('produsen.id')
                        ->get()->toArray();
        }
        $response['product_category_list'] = ProductCategory::where('name','like','%'.$keyword.'%')
                    ->orWhere('description','like','%'.$keyword.'%')
                    ->get();
        
        for($i=0; $i<sizeOf($response['product_list']); $i++){
            $produsen = Produsen::where('id', $response['product_list'][$i]['produsen_id'])
                        ->first()->toArray();
            $response['product_list'][$i]['bupda'] = Bupda::where('id',$produsen['bupda_id'])->first();
            $response['product_list'][$i]['produsen'] = $produsen;
            // for($i=0; $i<sizeOf($response['produsen_list']); $i++){
            //     // if($response['produsen_list'][$i]['id'] == $produsen['id']){
            //     //     array_push($response['produsen_list'], $produsen);
            //     //     break;
            //     // }
            // }
        }
        
        foreach($response['produsen_list'] as $p){
            $productList = Product::join('produsen','produsen.id','product.produsen_id')
                        ->join('bupda','bupda.id','produsen.bupda_id')
                        ->join('product_history','product_history.product_id','product.id')
                        ->where([
                            ['product_status',GH::$PRODUCT_STATUS_ENABLED],
                            ['product.stok','>',0], 
                            ['produsen.id',$p['id']],
                        ])
                        ->select('product.*')
                        ->groupBy('product.id')
                        ->with('produsen')->get()->toArray();

            foreach($productList as $p2){
                $found = false;
                foreach($response['product_list'] as $p3){
                    if($p2['id'] == $p3['id']){
                        $found = true;
                    }
                }
                if(!$found){
                    $bupda = Bupda::where('id', $p2['produsen']['bupda_id'])->first();
                    $p2['bupda'] = $bupda;
                    array_push($response['product_list'], $p2);
                }
            }
        }

        $bupda = Bupda::where('bupda_name','like','%'.$keyword.'%')
                            ->orWhere('description','like','%'.$keyword.'%')
                            ->get();

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
                                            
        $data['user_id'] = $request->user_id;
        $data['keyword'] = $keyword;
        $data['keyword_category'] = 'general';
        $data['latitude'] = $request->latitude;
        $data['longitude'] = $request->longitude;
        SearchHistory::insert($data);
        return response()->json($response);
    }

    public function merchantSearch(Request $request){
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $keyword = $request->keyword;
        $bupda = Bupda::where('bupda_name','like','%'.$keyword.'%')
                            ->orWhere('description','like','%'.$keyword.'%')
                            ->get();

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

        $data['user_id'] = $request->user_id;
        $data['keyword'] = $keyword;
        $data['keyword_category'] = 'general';
        $data['latitude'] = $request->latitude;
        $data['longitude'] = $request->longitude;
        SearchHistory::insert($data);
        return response()->json($bupdaTemp);
    }
}
