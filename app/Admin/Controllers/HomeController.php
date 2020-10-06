<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\InfoBox;
use App\Produsen;
use App\Product;
use App\User;
use App\Transaction;
use App\Bupda;
use App\Bonus;
use Admin;
class HomeController extends Controller
{
    public function index(Content $content)
    {
        if(Admin::user()->isRole('administrator')){
            return $content
            ->header('Dashboard')
            ->description('List')
            ->row(function(Row $row) {
                $row->column(3, new InfoBox('User', 'user-plus', 'blue', '/admin/user', User::count()));
                $row->column(3, new InfoBox('Produsen', 'group', 'green', '/admin/produsen', Produsen::count()));
                $row->column(3, new InfoBox('Produk', 'cube', 'aqua', '/admin/product', Product::count()));
                $row->column(3, new InfoBox('Transaksi', 'shopping-cart', 'yellow', '/admin/transaction', Transaction::count()));
                
                $podusenMin=Produsen::where('produsen_status', 0)->get()->count();
                $row->column(3, new InfoBox('Produsen Belum Diverifikasi', 'users', 'orange', '/admin/produsen', $podusenMin));

                $podusenNon=Produsen::where('produsen_status', 3)->get()->count();
                $row->column(3, new InfoBox('Produsen Dinonaktifkan', 'times-circle', 'red', '/admin/produsen', $podusenNon));
                
                $productMin=Product::where('product_status', 0)->get()->count();
                $row->column(3, new InfoBox('Produk Belum Diverifikasi', 'cube', 'orange', '/admin/product', $productMin));

                $productNon=Product::where('product_status', 3)->get()->count();
                $row->column(3, new InfoBox('Produk Dinonaktifkan', 'times-rectangle', 'red', '/admin/product', $productNon));

                $income = Bonus::where('user_id', 0)->sum('amount');
                $row->column(3, new InfoBox('Income', 'usd', 'green', '/admin/produsen', $income));
            });
        }else if(Admin::user()->isRole('bupda')){
            return $content
            ->header('Dashboard')
            ->description('Description...')
            ->row(function(Row $row) {
                $bupda = Bupda::where('user_id', Admin::user()->id)->first();
                $produsenCount = Produsen::where('bupda_id', $bupda->id)->count();
                $productCount = Product::join('produsen','produsen.id','product.produsen_id')
                        ->where('produsen.bupda_id', $bupda->id)->count();
                $row->column(3, new InfoBox('Produsen', 'users', 'green', '/admin/produsen', $produsenCount));
                $row->column(3, new InfoBox('Produk', 'cube', 'orange', '/admin/product', $productCount));
            });
        }else if(Admin::user()->isRole('pemprov')){
            return $content
            ->header('Dashboard')
            ->description('Description...')
            ->row(function(Row $row) {
                $row->column(3, new InfoBox('Bupda', 'users', 'blue', '/admin/bupda', Bupda::count()));
            });
        }
    }
}
