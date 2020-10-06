<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Product;
use App\ProductCategory;
use App\ProductHistory;
use App\Bupda;
use App\SatuanProduk;
use App\Produsen;
use App\User;
use Illuminate\Http\Request;
use GH;
use DB;
class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function uploadBuktiTransfer(Request $request){
        $id = $request->id;
        $path = GH::uploadFile($request->file('file'), 'product');

        $product = ProductHistory::where('id', $id)->update([
            'photo_url' => $path
        ]);
        
        $product = ProductHistory::where('id', $id)->first();
        return response()->json($product);
    }
    public function addNewProduct($data){
        $p['photo_url'] = GH::uploadFile($data['file'], 'product');
        $p['product_status'] = 2;
        $p['produsen_id'] = $data['produsen_id'];
        $p['product_category_id'] = $data['product_category_id'];
        $p['satuan_produk_id'] = $data['satuan_produk_id'];
        $p['name'] = $data['name'];
        $p['description'] = $data['description'];
        $p['price'] = $data['price'];
        $product = Product::create($p);
        $product['stok'] = $data['stok'];
        return $this->addNewProductHistory($product, $data['type']);
    }

    public function addNewProductHistory($data, $type){
        $h['product_id'] = $data['id'];
        $h['stok'] = $data['stok'];
        $h['type'] = $type;
        $h['photo_url'] = "";
        $h['transaction_item_id'] = 0;
        if($type == 0){
            $h['status'] = 1;
        }else {
            $h['status'] = 0;
        }

        $productHistory = ProductHistory::create($h);

        return response()->json($productHistory);
    }

    public function addProduct(Request $request){
        $response = null;
        $data = $request->all();

        if($data['id'] != ""){
            if($data['file'] != ""){
                Product::where('id', $data['id'])->update([
                    'photo_url' => GH::uploadFile($data['file'], 'product'),
                    'product_category_id' => $data['product_category_id'],
                    'satuan_produk_id' => $data['satuan_produk_id'],
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'stok' => $data['stok'],
                    'real_price' => $data['real_price'],
                    'discount' => $data['discount'],
                    'price' => $data['price']
                ]);
            }else {
                Product::where('id', $data['id'])->update([
                    'product_category_id' => $data['product_category_id'],
                    'satuan_produk_id' => $data['satuan_produk_id'],
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'stok' => $data['stok'],
                    'real_price' => $data['real_price'],
                    'discount' => $data['discount'],
                    'price' => $data['price']
                ]);
            }
            $response['response_status'] = 1;
            $response['response_message'] = 'Success';
            return response()->json($response); 
        }else {
            $data['photo_url'] = GH::uploadFile($data['file'], 'product');
            $data['product_status'] = 2;
            $response = Product::create($data);
            $response = Product::where('id',$response->id)->first();
        }
        

        if($response){
            $response['response_status'] = 1;
            $response['response_message'] = 'Success';
            return response()->json($response);                
        }else {
            $response['response_status'] = 0;
            $response['response_message'] = 'Failed add product data';
            return response()->json($response);       
        }
    }

    public function getProductCategory(){
        $response['data'] = ProductCategory::all();
        return response()->json($response);                
    }

    public function getProductList($user_id){
        $user = User::where('id', $user_id)->first();
        if($user){
            $product = Product::join('produsen','produsen.id','product.produsen_id')
                            ->join('bupda','bupda.id','produsen.bupda_id')
                            ->where([
                                ['product_status',GH::$PRODUCT_STATUS_ENABLED],
                                ['bupda.desa_adat_id',$user->desa_adat_id],
                            ])
                            ->with('produsen')
                            ->select('product.*')->get()->toArray();
            $otherProduct = Product::join('produsen','produsen.id','product.produsen_id')
                            ->join('bupda','bupda.id','produsen.bupda_id')
                            ->where([
                                ['product_status',GH::$PRODUCT_STATUS_ENABLED],
                                ['bupda.desa_adat_id','!=', $user->desa_adat_id],
                            ])
                            ->with('produsen')
                            ->select('product.*')->get()->toArray();
            foreach($otherProduct as $p){
                array_push($product, $p);
            }
        }else {
            $product = Product::where([
                ['product_status',GH::$PRODUCT_STATUS_ENABLED]
            ])
            ->select('product.*')
            ->get();
        }
        for($i=0; $i<sizeOf($product); $i++){
            $bupda = Bupda::join('produsen','produsen.bupda_id','bupda.id')
                            ->where('produsen.id', $product[$i]['produsen_id'])
                            ->select('bupda.*')
                            ->first();
            $product[$i]['bupda'] = $bupda;
        }
        return response()->json($product);
    }

    public function getProductListByBupda($bupda_id){
        if($bupda_id != 0){
            $product = Bupda::join('produsen','produsen.bupda_id','bupda.id')
            ->join('product','product.produsen_id','produsen.id')
            ->where('bupda.id',$bupda_id)
            ->where('product_status',GH::$PRODUCT_STATUS_ENABLED)
            ->select('product.*')
            ->get();
        }else {
            $product = Bupda::join('produsen','produsen.bupda_id','bupda.id')
            ->join('product','product.produsen_id','produsen.id')
            ->where('product_status',GH::$PRODUCT_STATUS_ENABLED)
            ->select('product.*')
            ->get();
        }

        for($i=0; $i<sizeOf($product); $i++){
            $bupda = Bupda::join('produsen','produsen.bupda_id','bupda.id')
                            ->where('produsen.id', $product[$i]->produsen_id)
                            ->select('bupda.*')
                            ->first();
            $product[$i]['bupda'] = $bupda;
            $product[$i]['produsen'] = Produsen::find($product[$i]['produsen_id']);
        }
        return response()->json($product);
    }

    public function getProductListByProdusen($produsen_id){
        $product = Product::where('produsen_id',$produsen_id)
                ->with('produsen')
                ->select('product.*')
                ->get();

        return response()->json($product);
    }
    public function getOtherProducts($user_id){
        $product = Product::where('produsen_id',$produsen_id)
        ->with('produsen')
        ->select('product.*')
        ->get();

        return response()->json($product);
    }

    public function getProductHistoryList($produsen_id){
        $productHistoryList = ProductHistory::join('product','product.id','product_history.product_id')
                        ->where('product.produsen_id', $produsen_id)
                        ->select('product_history.*')
                        ->with('product','product.produsen','product.produsen.bupda')
                        ->orderBy('product_history.id','desc')
                        ->get();
        for($i=0; $i<sizeOf($productHistoryList); $i++){
            $productHistoryList[$i]['product'] = $productHistoryList[$i]->product;
        }
        return $productHistoryList;
    }
    
    public function getProductListByCategory($product_category_id){
        $product['data'] = Product::where('product.product_status', GH::$PRODUCT_STATUS_ENABLED)
                            ->where('product_category_id',$product_category_id)
                            ->with('produsen')
                            ->select('product.*')
                            ->get();
        for($i=0; $i<sizeOf($product['data']); $i++){
            $bupda = Bupda::join('produsen','produsen.bupda_id','bupda.id')
                            ->where('produsen.id', $product['data'][$i]->produsen_id)
                            ->select('bupda.*')
                            ->first();
            $product['data'][$i]['bupda'] = $bupda;
        }
        
        return response()->json($product);
    }
    public function getDetailProductListByProdusen($produsen_id){
        $product = Product::where('product.product_status', GH::$PRODUCT_STATUS_ENABLED)
                            ->where('product.produsen_id',$produsen_id)
                            ->with('produsen')
                            ->select('product.*')
                            ->get();
        for($i=0; $i<sizeOf($product); $i++){
            $bupda = Bupda::join('produsen','produsen.bupda_id','bupda.id')
                            ->where('produsen.id', $product[$i]->produsen_id)
                            ->select('bupda.*')
                            ->first();
            $product[$i]['bupda'] = $bupda;
        }
        
        return response()->json($product);
    }

    public function getSatuanProdukList(){
        $satuanProdukList = SatuanProduk::where('status', 1)->get();
        return response()->json($satuanProdukList);
    }

    public function changeStatusProduct(Request $request){
        $status = $request->status;
        $id = $request->id;
        $result = Product::where('id', $id)->update(['product_status' => $status]);

        return $result;
    }
}
