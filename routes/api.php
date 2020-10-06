<?php

use Illuminate\Http\Request;
// use GH;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/hello', function(){
    return "Hello World";
});

Route::post('/test', function(){
    // $data_array =  array(
    //   "id" => 1
    // );
    // $response = GH::callAPI('POST', 'https://sukmabali-app.herokuapp.com/sendNotification', json_encode($data_array));
    // return $response;
});
$router->group(['middleware' => 'api'], function() use ($router) {
    $router->get('/test', function () use ($router) {
        return GH::$PRODUSEN_STATUS_PENDING;
    });

    $router->post('/get_home_data','HomeController@getHomeData');
    $router->post('/get_merchant_home_data','HomeController@getMerchantHomeData');
    
    //User
    $router->post('/add_user','UserController@addUser');
    $router->post('/cek_user','UserController@cekUser');
    $router->get('/user/{id}','UserController@getUserDetail');

    //Produsen
    $router->post('/add_produsen','ProdusenController@addProdusen');
    
    //BUPDA
    $router->post('/get_bupda_list','BupdaController@getBupdaList');

    //Product
    $router->post('/add_product','ProductController@addProduct');
    $router->get('/get_product_category','ProductController@getProductCategory');
    $router->get('/get_product_list/{user_id}','ProductController@getProductList');
    $router->get('/get_product_list_by_bupda/{bupda_id}','ProductController@getProductListByBupda');
    $router->get('/get_product_list_by_category/{product_category_id}','ProductController@getProductListByCategory');
    $router->get('/get_product_list_by_produsen/{produsen_id}','ProductController@getProductListByProdusen');
    $router->get('/get_satuan_produk_list','ProductController@getSatuanProduklist');
    $router->get('/get_product_history_list/{produsen_id}','ProductController@getProductHistoryList');
    $router->post('/upload_bukti_transfer', 'ProductController@uploadBuktiTransfer');
    $router->post('/change_status_product','ProductController@changeStatusProduct');
    $router->post('/get_other_products/{user_id}', 'ProductController@getOtherProducts');
    $router->get('/product/get_product_list_by_produsen/{produsen_id}','ProductController@getDetailProductListByProdusen');
    //Cart
    $router->post('/get_cart_data','CartController@getCartData');
    $router->post('/add_cart','CartController@addCart');
    $router->post('/update_cart_status','CartController@updateCartStatus');
    $router->post('/update_cart_quantity','CartController@updateCartQuantity');
    //Transaction
    $router->post('/add_transaction','TransactionController@addTransaction');
    $router->post('/get_transaction','TransactionController@getTransaction');
    $router->post('/update_transaction_status','TransactionController@updateTransactionStatus');
    $router->post('/get_transaction_by_status_and_produsen_id','TransactionController@getTransactionByStatusAndProdusenId');
    $router->post('/update_transaction_item_status','TransactionController@updateTransactionItemStatus');
    //Search
    $router->post('/general_search','SearchController@generalSearch');
    $router->post('/merchant_search','SearchController@merchantSearch');

    //Review
    $router->post('/give_review','ReviewController@giveReview');
    $router->post('/get_product_list_for_review','ReviewController@getProductListForReview');
    $router->get('/get_review_list_by_product_id/{product_id}','ReviewController@getReviewListByProductId');

    //Profile
    $router->post('/update_profile','ProfileController@updateProfile');
    $router->post("/update_delivery_fee","UserController@updateDeliveryFee");

    //Desa Adat
    $router->get('/get_desa_adat','DesaAdatController@getDesaAdat');
    $router->post('/tambah_desa_adat','DesaAdatController@tambahDesaAdat');

    //CS
    $router->post('/add_cs', 'CSController@addCS');
    $router->get('/batal_cs/{cs_id}','CSController@batalCS');
    $router->get('/detail_cs/{cs_id}','CSController@detailCS');
    $router->get('/home_data/{cs_id}','CSController@homeData');

    //Deposit produsen
    $router->get('/deposit_produsen/{produsen_id}', 'DepositProdusenController@depositProdusen');
    $router->post('/deposit/tambah_deposit','DepositProdusenController@tambahDeposit');

    //Deposit CS
    $router->get('/get_deposit_cs/{cs_id}','DepositCSController@getDepositList');
    $router->post('/penarikan_deposit','DepositCSController@penarikanDeposit');
    $router->get('/penarikan_deposit_list/{cs_id}','DepositCSController@penarikanDepositList');

    //Notification
    $router->post('/get_notification','NotificationController@getNotification');
    $router->post('/set_notification_received','NotificationController@setNotificationReceived');

    //App Info
    $router->post('/get_app_info','ProfileController@getAppInfo');

    $router->get('/jam_buka/get/{produsen_id}', 'JamBukaController@get');
    $router->post('/jam_buka/update','JamBukaController@update');

    //Penarikan bonus
    $router->post('penarikan_bonus','PenarikanBonusController@penarikanBonus');
    $router->get('penarikan_bonus/{user_id}','PenarikanBonusController@getByUserId');


});
