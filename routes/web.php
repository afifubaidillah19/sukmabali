<?php



/*

|--------------------------------------------------------------------------

| Web Routes

|--------------------------------------------------------------------------

|

| Here is where you can register web routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| contains the "web" middleware group. Now create something great!

|

*/



Route::get('/', function () {

    return view('welcome');

});



Route::get('/register', 'BupdaController@index');

Route::post('/register/store','BupdaController@store');

Route::get('/register_success',function(){

    return view('register_succes');

});

Route::post('/set_auto_verification_order','BupdaController@setAutoVerificationOrder');
// use App\Product;
// use App\ProductHistory;

// Route::get('/move_product', function(){
//     $products = Product::all();
//     foreach($products as $p){
//         ProductHistory::insert([
//             'product_id' => $p->id,
//             'stok' => $p->stok,
//             'type' => 1,
//             'status' => 1,
//             'photo_url' => "",
//         ]);
//     }

//     $productHistories = ProductHistory::all();
//     return $productHistories;
// });



