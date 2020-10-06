<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resources([
        'produsen'         => ProdusenController::class,
        'product'          => ProductController::class,
        'bupda'            => BupdaController::class,
        'transaction'      => TransactionController::class,
        'desa_adat'        => DesaAdatController::class,
        'user'             => UserController::class,
        'temp_desa_adat'   => TempDesaAdatController::class,
        'slider'           => SliderController::class,
        'satuan_produk'    => SatuanProdukController::class,
        'customer_service' => CSController::class,
        'deposit_bupda'    => DepositBupdaController::class,
        'penarikan_deposit_cs' => PenarikanDepositCSController::class,
        'penarikan_deposit_bupda' => PenarikanDepositBupdaController::class,
        'deposit_sukmabali' => DepositSukmabaliController::class,
        'penarikan_deposit_sukmabali' => PenarikanDepositSukmabaliController::class,
        'product_category'          => ProductCategoryController::class,
        'location/kabupaten'        => KabupatenController::class,
        'location/kecamatan'        => KecamatanController::class,
        'penarikan_bonus'           => PenarikanBonusController::class,
        'deposit_produsen'         => DepositProdusenController::class,
        'kupon/group'           => KuponGroupController::class
    ]);
});
