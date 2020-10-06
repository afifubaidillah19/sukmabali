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
use App\DepositProdusen;
use App\Notification;
use App\Bonus;
use App\Http\Controllers\ProductController;
use GH;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function addTransaction(Request $request){
        $data['user_id'] = $request->user_id;
        $data['delivery_address'] = $request->delivery_address;
        $data['delivery_latitude'] = $request->delivery_latitude;
        $data['delivery_longitude'] = $request->delivery_longitude;
        $data['transaction_status'] = GH::$TRANSACTION_STATUS_ON_PROCCESS;
        $data['delivery_address_spesific'] = "";
        if($request->delivery_address_spesific){
            $data['delivery_address_spesific'] = $request->delivery_address_spesific;
        }
        $deliveryData = json_decode($request->delivery_data);
        foreach($deliveryData as $item){
            Cart::where('cart_id',$item->cart_id)->update([
                'delivery_fee' => $item->delivery_fee
            ]);
        }

        $transactionData = Cart::join('product','product.id','cart.product_id')
                    ->join('produsen','produsen.id','product.produsen_id')
                    ->where('cart.user_id', $request->user_id)
                    ->where('cart.cart_status',GH::$CART_STATUS_ON_CART)
                    ->groupBy('bupda_id')
                    ->select('cart.*', 'produsen.bupda_id')
                    ->get();
        
        $user = User::find($request->user_id);
        for($i=0; $i<sizeOf($transactionData); $i++){
            $data['bupda_id'] = $transactionData[$i]->bupda_id;
            $bupda = Bupda::find($data['bupda_id']);
            if($bupda->auto_verification_order == 1){
                $data['transaction_status'] = 1;
                Notification::create([
                    'notification_type' => 1,
                    'sender_id' => 0,
                    'receiver_id' => $user->id,
                    'title' => 'Pesanan Diterima',
                    'message' => 'Pesanan anda diterima oleh BUPDA '.$bupda->bupda_name,
                    'notification_status' => 0
                ]);
            }
            $data['delivery_fee'] = json_decode($request->all()[$data['bupda_id']])->delivery_fee;
            $data['total_price'] = json_decode($request->all()[$data['bupda_id']])->total_price;
            $data['total_payment'] = json_decode($request->all()[$data['bupda_id']])->total_payment;
            $resultTransaction = Transaction::create($data);
            $dataItem['transaction_id'] = $resultTransaction->transaction_id;
            $cartData = Cart::join('product','product.id','cart.product_id')
                        ->join('produsen','produsen.id','product.produsen_id')
                        ->where('cart.user_id', $request->user_id)
                        ->where('produsen.bupda_id',$data['bupda_id'])
                        ->where('cart_status',GH::$CART_STATUS_ON_CART)
                        ->select('cart.*','product.price')
                        ->get();

            for($c=0; $c<sizeOf($cartData); $c++){
                $dataItem['product_id'] = $cartData[$c]->product_id;
                $dataItem['user_id'] = $cartData[$c]->user_id;
                $dataItem['quantity'] = $cartData[$c]->quantity;
                $dataItem['price'] = $cartData[$c]->price;
                $dataItem['message'] = $cartData[$c]->message;
                $dataItem['delivery_fee'] = $cartData[$c]->delivery_fee;
                $dataItem['transaction_status'] = GH::$TRANSACTION_ITEM_STATUS_ON_PROCCESS;                
                $updateCart['cart_status'] = GH::$CART_STATUS_MOVED;
                Cart::where('cart_id', $cartData[$c]->cart_id)->update($updateCart);
                $ti = TransactionItem::create($dataItem);

                $h['product_id'] = $cartData[$c]->product_id;
                $h['stok'] = $cartData[$c]->quantity * -1;
                $h['type'] = 0;
                $h['photo_url'] = "";
                $h['transaction_item_id'] = $ti->transaction_item_id;
                $h['status'] = 1;
                ProductHistory::create($h);
                if($bupda->auto_verification_order == 1){
                    $product = Product::where('id', $h['product_id'])->first();
                    GH::sendNotification([
                        'notification_type' => 2,
                        'sender_id' => 0,
                        'receiver_id' => $product->produsen_id,
                        'title' => 'Pesanan Baru',
                        'message' => 'Pesanan baru dari '.$user->name,
                        'notification_status' => 0,
                        'priority' => 1
                        ]);
                }
            }
        }       

        $response['response_status'] = 1;
        $response['response_message'] = "Success";
        return response()->json($response); 
    }

    public function getTransaction(Request $request){
        $response = Transaction::where('user_id', $request->id)
            ->orderBy('transaction_id','desc')
            ->get();
        
        for($t=0; $t<sizeOf($response); $t++){
            $response[$t]['bupda'] = Bupda::where('id',$response[$t]['bupda_id'])->first();
            $response[$t]['bupda']['produsen_list'] 
                    = TransactionItem::select('produsen.*')
                                    ->join('product','product.id','transaction_item.product_id')
                                    ->join('produsen','produsen.id','product.produsen_id')
                                    ->where('transaction_id', $response[$t]['transaction_id'])
                                    ->groupBy('produsen.id')
                                    ->get();

            for($p=0; $p<sizeOf($response[$t]['bupda']['produsen_list']); $p++){
                $transactionItem = Transactionitem::select('transaction_item.*')
                                ->join('product','product.id','transaction_item.product_id')
                                ->join('produsen','produsen.id','product.produsen_id')
                                ->where('produsen.id', $response[$t]['bupda']['produsen_list'][$p]['id'])
                                ->where('transaction_id',$response[$t]['transaction_id'])
                                ->get();
                for($ti=0; $ti<sizeOf($transactionItem); $ti++){
                    $product = Product::where('id',$transactionItem[$ti]['product_id'])->first();
                    $transactionItem[$ti]['product'] = $product;
                }

                $response[$t]['bupda']['produsen_list'][$p]['transaction_item'] = $transactionItem;
            }
        }

        return response()->json($response);
    }
    public function getTransactionByStatusAndProdusenId(Request $request){
        $id = $request->id;
        $status = $request->status;

        $transaction = TransactionItem::join('transaction','transaction.transaction_id','transaction_item.transaction_id')
                                    ->join('product','product.id','transaction_item.product_id')
                                    ->join('produsen','produsen.id','product.produsen_id')
                                    ->where([
                                        ['produsen.id',$id],
                                        ['transaction_item.transaction_status',$status],
                                        ['transaction.transaction_status','>',0]
                                    ])
                                    ->select('transaction.*')
                                    ->groupBy('transaction.transaction_id')
                                    ->orderBy('transaction.transaction_id','desc')
                                    ->get();

        for($t=0; $t<sizeOf($transaction); $t++){
            $transactionItem = TransactionItem::join('product','product.id','transaction_item.product_id')
                                    ->where('transaction_item.transaction_id', $transaction[$t]['transaction_id'])  
                                    ->where('product.produsen_id', $id)
                                    ->get();
            $user = User::where('id', $transaction[$t]->user_id)->first();
            for($ti=0; $ti<sizeOf($transactionItem); $ti++){
                $product = Product::where('id', $transactionItem[$ti]['product_id'])->first();
                $transactionItem[$ti]['product'] = $product;
            }
            
            $transaction[$t]['transaction_item_list'] = $transactionItem;
            $transaction[$t]['user'] = $user;
        }
        
        return response()->json($transaction);
    }
    public function updateTransactionStatus(Request $request){
        $transaction_id = $request->transaction_id;
        $data['transaction_status'] = $request->transaction_status;

        Transaction::where('transaction_id',$transaction_id)->update($data);
        
        if($request->transaction_status == 3){
            TransactionItem::where('transaction_id', $transaction_id)
                    ->update([
                        'transaction_status' => 4
                    ]);
            $transactionItem = TransactionItem::where('transaction_id', $transaction_id)->get();
            foreach($transactionItem as $ti){
                ProductHistory::where('transaction_item_id', $ti->transaction_item_id)->delete();
            }
        }

        $response['response_status'] = 1;
        $response['response_message'] = "success";

        return response()->json($response);
    }

    public function updateTransactionItemStatus(Request $request){
        $produsenId = $request->produsen_id;
        $transactionId = $request->transaction_id;
        $status = $request->status;
        $transaction = Transaction::where('transaction_id', $transactionId)->first();
        $resp = TransactionItem::join('product','product.id','transaction_item.product_id')
                                ->join('produsen','produsen.id','product.produsen_id')
                                ->where([
                                    ['produsen.id',$produsenId],
                                    ['transaction_id', $transactionId]
                                ])->select('transaction_item.*')->get();

        $depositProdusen = 0;
        $needDeposit = 0;
        if($status == 1){
            $depositProdusen = DepositProdusen::where('produsen_id', $produsenId)->sum('total_deposit');
            foreach($resp as $item){
                $needDeposit += (($item->price * $item->quantity) * 0.01);
            }
        }

        if($status == 1 && $depositProdusen < $needDeposit){
            return response()->json(2);
        }else {
            $count = 0;
            $produsen = Produsen::find($produsenId);
            foreach($resp as $item){
                if($count == 0){
                    if($status == 1){
                        Notification::create([
                            'notification_type' => 1,
                            'sender_id' => $produsenId,
                            'receiver_id' => $transaction->user_id,
                            'title' => 'Pesanan Dipacking',
                            'message' => 'Pesanan anda sedang dipacking oleh Produsen '.$produsen->produsen_name,
                            'notification_status' => 0
                        ]);
                    }else if($status == 2){
                        Notification::create([
                            'notification_type' => 1,
                            'sender_id' => $produsenId,
                            'receiver_id' => $transaction->user_id,
                            'title' => 'Pesanan Dikirim',
                            'message' => 'Pesanan anda sedang dikirim oleh Produsen '.$produsen->produsen_name,
                            'notification_status' => 0
                        ]);
                    }else if($status == 3){
                        Notification::create([
                            'notification_type' => 1,
                            'sender_id' => 0,
                            'receiver_id' => $transaction->user_id,
                            'title' => 'Pesanan Selesai',
                            'message' => 'Proses transaksi telah selesai. Terimakasih telah menggunakan SukmaBali',
                            'notification_status' => 0
                        ]);
                    }else if($status == 4){
                        Notification::create([
                            'notification_type' => 1,
                            'sender_id' => $produsenId,
                            'receiver_id' => $transaction->user_id,
                            'title' => 'Pesanan Dibatalkan',
                            'message' => 'Pesanan anda dibatalkan oleh Produsen '.$produsen->produsen_name,
                            'notification_status' => 0
                        ]);
                    }
                }
                $result = TransactionItem::where('transaction_item_id', $item->transaction_item_id)
                            ->update(['transaction_status' => $status]);
                if($status == 4){
                    ProductHistory::where('transaction_item_id', $item->transaction_item_id)
                        ->delete();
                }
                if($result) $count++;
            }
            if($count = sizeOf($resp)){
                $resp = TransactionItem::where('transaction_id',$transactionId)->get();
                $countDone = 0;
                $forDeposit = [];
                foreach($resp as $item){
                    if($item->transaction_status == GH::$TRANSACTION_ITEM_STATUS_DONE){
                        $countDone++;
                        array_push($forDeposit, $item);
                    }else if($item->transaction_status == GH::$TRANSACTION_ITEM_STATUS_CANCELED) {
                        $countDone++;
                    }
                }
    
                $isSuccess = true;
                if($countDone == sizeOf($resp)){
                    $resp = Transaction::where('transaction_id', $transactionId)
                                ->update(['transaction_status' => GH::$TRANSACTION_STATUS_DONE]);
    
                    $bonus['user_id'] = 0;
                    $bonus['transaction_id'] = $transactionId;
                    $bonus['percentage'] = 0.5;
                    $bonus['amount'] = $transaction->total_price * 0.005;
                    Bonus::create($bonus);
    
    
                    $user = User::where('id', $transaction->user_id)->first();
                    $bonus['user_id'] = $user->id;
                    $bonus['transaction_id'] = $transactionId;
                    $bonus['percentage'] = 0.1;
                    $bonus['amount'] = $transaction->total_price * 0.001;
                    Bonus::create($bonus);
                    $sukmaBaliBonusCount = 5 - $user->gen_no;
                    for($i=1; $i<=$sukmaBaliBonusCount; $i++){
                        $bonus['user_id'] = 0;
                        $bonus['transaction_id'] = $transactionId;
                        $bonus['percentage'] = 0.1;
                        $bonus['amount'] = $transaction->total_price * 0.001;
                        Bonus::create($bonus);
                    }
    
    
    
                    $startGenNo = $user->gen_no - 1;
                    $endGenNo = $startGenNo - 4;
                    if($endGenNo <= 0){
                        $endGenNo = 1;
                    }
                    for($i=$startGenNo; $i>=$endGenNo; $i--){
                        $user = User::where('id', $user->parent_id)->first();
                        $bonus['user_id'] = $user->id;
                        $bonus['transaction_id'] = $transactionId;
                        $bonus['percentage'] = 0.1;
                        $bonus['amount'] = $transaction->total_price * 0.001;
                        Bonus::create($bonus);
                    }
                    $transactionItems = TransactionItem::join('product','product.id','transaction_item.product_id')
                                ->join('produsen','produsen.id','product.produsen_id')
                                ->where([
                                    ['produsen.id',$produsenId],
                                    ['transaction_id', $transactionId]
                                ])->select('transaction_item.*')->get();
                    foreach($transactionItems as $item){
                        $decDeposit['produsen_id'] = $produsenId;
                        $decDeposit['transaction_item_id'] = $item->transaction_item_id;
                        $decDeposit['total_deposit'] = -1 * (($item->price * $item->quantity) * 0.01);
                        $decDeposit['status'] = 1;
                        DepositProdusen::create($decDeposit);
                    }
                    if(!$resp) $isSuccess = false;
                }
    
                if($isSuccess){
                    // $cs = CustomerService::join('bupda', 'bupda.id', 'customer_service.bupda_id')
                    //                 ->join('transaction','transaction.bupda_id','bupda.id')
                    //                 ->where('transaction.transaction_id', $transactionId)
                    //                 ->select('customer_service.*')
                    //                 ->first();
                    // foreach($forDeposit as $d){
                    //     if($cs){
                    //         $dCS = new DepositCS();
                    //         $dCS->cs_id = $cs->id;
                    //         $dCS->transaction_item_id = $d->transaction_item_id;
                    //         $dCS->persen = GH::$DEPOSIT_CS;
                    //         $dCS->total_deposit = $dCS->persen * $d->price * $d->quantity;
                    //         $dCS->status = 0;
                    //         $dCS->save();
                    //     }
                    //     $dBupda = new DepositBupda();
                    //     $dBupda->bupda_id = $transaction->bupda_id;
                    //     $dBupda->transaction_item_id = $d->transaction_item_id;
                    //     $dBupda->persen = GH::$DEPOSIT_BUPDA;
                    //     $dBupda->total_deposit = $dBupda->persen * $d->price * $d->quantity;
                    //     $dBupda->status = 0;
                    //     $dBupda->save();
    
                    //     $dSukmabali = new DepositSukmabali();
                    //     $dSukmabali->transaction_item_id = $d->transaction_item_id;
                    //     $dSukmabali->persen = GH::$DEPOSIT_SUKMABALI;
                    //     $dSukmabali->total_deposit = $dSukmabali->persen * $d->price * $d->quantity;
                    //     $dSukmabali->status = 0;
                    //     $dSukmabali->save();
                    // }
                    
                    return response()->json(1);
                }else {
                    return response()->json(0);
                }
            }else {
                return response()->json(0);
            }
        }
    }
}
