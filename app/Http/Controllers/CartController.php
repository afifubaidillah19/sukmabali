<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Cart;
use App\Produsen;
use GH;
use DB;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function getCartData(Request $request){
        $user_id = $request->user_id;

        $cart = Cart::join('product','product.id','cart.product_id')
                    ->join('produsen','produsen.id','product.produsen_id')
                    ->join('bupda','bupda.id','produsen.bupda_id')
                    ->where('cart.cart_status',GH::$CART_STATUS_ON_CART)
                    ->where('cart.user_id',$user_id)
                    ->groupBy('bupda.id')
                    ->select('bupda.*')
                    ->get();

        
        for($i=0; $i<sizeOf($cart); $i++){
            $cart[$i]['cart'] = Cart::join('product','product.id','=','cart.product_id')
                                    ->join('produsen','produsen.id','product.produsen_id')
                                    ->where('produsen.bupda_id',$cart[$i]['id'])
                                    ->where('cart.user_id',$user_id)
                                    ->where('cart.cart_status',GH::$CART_STATUS_ON_CART)
                                    ->select('cart.*')
                                    ->get();
            for($j=0; $j<sizeOf($cart[$i]['cart']); $j++){
                $cart[$i]['cart'][$j]['product'] = Cart::join('product','product.id','cart.product_id')
                                        ->where([
                                            ['product_status',GH::$PRODUCT_STATUS_ENABLED],
                                            ['cart.product_id',$cart[$i]['cart'][$j]->product_id],
                                        ])
                                        ->groupBy('product.id')->first();
                $produsen = Produsen::where('id', $cart[$i]['cart'][$j]['product']['produsen_id'])->first();
                $cart[$i]['cart'][$j]['product']['produsen'] = $produsen;
            }

            $cart[$i]['produsen_list'] = Cart::join('product','product.id','=','cart.product_id')
                                ->join('produsen','produsen.id','product.produsen_id')
                                ->where('produsen.bupda_id',$cart[$i]['id'])
                                ->where('cart.user_id',$user_id)
                                ->where('cart.cart_status',GH::$CART_STATUS_ON_CART)
                                ->select('produsen.*')
                                ->groupBy('produsen.id')
                                ->get();
        }
        return response()->json($cart);
    }

    public function addCart(Request $request){
        $data = $request->all();
        $cart = Cart::where('product_id', $data['product_id'])
                    ->where('user_id', $data['user_id'])
                    ->where('cart_status', 0)->first();

        $response = null;
        if($cart){
            $data['quantity'] = ($cart->quantity + $data['quantity']);
            $cart = Cart::where('product_id', $data['product_id'])
                    ->where('user_id', $data['user_id'])
                    ->where('cart_status', 0)
                    ->first();
            $cart->quantity = $data['quantity'];
            if($cart->save()) $response = $cart;
            else $response = null;

        }else {
            $data['cart_status'] = 0;
            $response = Cart::create($data);
        }
        
        if($response){
            $response['response_status'] = 1;
            $response['response_message'] = 'Success';
            return response()->json($response);                
        }else {
            $response['response_status'] = 0;
            $response['response_message'] = 'Failed add user data';
            return response()->json($response);       
        }
    }

    public function updateCartQuantity(Request $request){
        $cart = Cart::where('cart_id',$request->cart_id)->first();
        $cart->quantity = $request->quantity;

        if($cart->save()){
            $response['response_status'] = 1;
            $response['response_message'] = 'Success';
            return response()->json($response);                
        }else {
            $response['response_status'] = 0;
            $response['response_message'] = 'Failed update data';
            return response()->json($response);       
        }
    }

    public function updateCartStatus(Request $request){
        $cart = Cart::where('cart_id',$request->cart_id)->first();
        $cart->cart_status = $request->cart_status;

        if($cart->save()){
            $response['response_status'] = 1;
            $response['response_message'] = 'Success';
            return response()->json($response);                
        }else {
            $response['response_status'] = 0;
            $response['response_message'] = 'Failed update data';
            return response()->json($response);       
        }
    }
}
