<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\ProductCategory;
use App\Merchant;
use App\SearchHistory;
use App\Review;
use App\Transaction;
use App\TransactionItem;
use App\User;
class ReviewController extends Controller
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

    public function giveReview(Request $request){
        $data['transaction_item_id'] = $request->transaction_item_id;
        $data['rating'] = $request->rating;
        $data['review'] = "-";
        if($request->review != null && $request->review != ""){
            $data['review'] = $request->review;
        }
        $data['review_status'] = 'active';
        
        Review::create($data);
        $response['response_status'] = 1;
        $response['response_message'] = 'success';

        return response()->json($response);
    }

    public function getProductListForReview(Request $request){
        $data = TransactionItem::where('transaction_id',$request->transaction_id)->get();
        for($i=0; $i<sizeOf($data); $i++){
            $data[$i]['review'] = Review::where('transaction_item_id', $data[$i]->transaction_item_id)->first();
            $data[$i]['product'] = Product::where('id',$data[$i]->product_id)->first();
            $data[$i]['user'] = null;
        }

        return response()->json($data);
    }

    public function getReviewListByProductId($product_id){
        $data = Review::join('transaction_item','transaction_item.transaction_item_id','review.transaction_item_id')
                ->join('transaction','transaction.transaction_id','transaction_item.transaction_id')
                ->where('product_id',$product_id)
                ->select('review.*','user_id')
                ->get();

        for($i=0; $i<sizeOf($data); $i++){
            $data[$i]['user'] = User::where('id',$data[$i]->user_id)->first();
        }

        return response()->json($data);
    }
}
