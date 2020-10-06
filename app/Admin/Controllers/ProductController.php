<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Product;
use App\ProductHistory;
use App\SatuanProduk;
use App\DepositProdusen;
use App\Produsen;
use App\Admin\Actions\ProductVerifikasi;
use GH;
use Admin;
class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Produk';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product);
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->rows(function (Grid\Row $row) {
            $row->column('no', $row->number + 1);
        });
        $grid->column('no');

        if(Admin::user()->isRole('bupda')){
            $grid->model()->join('produsen','produsen.id','product.produsen_id')
            ->join('bupda','bupda.id','produsen.bupda_id')
            ->where('bupda.user_id', Admin::user()->id)
            ->select('product.*');
        }
        $grid->column('id');
        $grid->column('Nama Produsen')->display(function(){
            return $this->produsen != null && $this->produsen->produsen_name != null? $this->produsen->produsen_name : "-";
        });
        $grid->column('name',__('Nama Produk'));
        $grid->column('Kategori Produk')->display(function(){
            return $this->product_category->name;
        });
        $grid->column('Satuan Produk')->display(function(){
            return $this->satuan_produk->nama;
        });
        $grid->column('stok');
        $grid->column('price',__('Harga'));
        $grid->column('Status Produk')->display(function(){
            switch($this->product_status){
                case 0:
                    return "<span class='btn btn-xs btn-warning'>Pending</span>";
                break;
                case 1:
                    return "<span class='btn btn-xs btn-danger'>Ditolak</span>";
                break;
                case 2:
                    return "<span class='btn btn-xs btn-success'>Terverifikasi</span>";
                break;
                case 3:
                    return "<span class='btn btn-xs btn-danger'>Dinonaktifkan</span>";
                break;
                case 4:
                    return "<span class='btn btn-xs btn-danger'>Dihapus</span>";
                break;
            }
        });
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            $actions->disableDelete();
        });
        $grid->disableCreateButton();
        $grid->column('created_at', __('Created at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(ProductHistory::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('product.name',__('Nama Produk'));
        $show->field('product.satuan_produk_id',__('Satuan Produk'))->as(function(){
            return $this->satuan_produk->nama;
        });
        $show->field('stok');
        $show->field('price',__('Harga'));
        $show->field('product_status','Status Produk')->as(function(){
            switch($this->product_status){
                case GH::$PRODUCT_STATUS_PENDING:
                    return "Pending";
                break;
                case GH::$PRODUCT_STATUS_REJECTED:
                    return "Ditolak";
                break;
                case GH::$PRODUCT_STATUS_ENABLED:
                    return "Aktif";
                break;
                case GH::$PRODUCT_STATUS_DISABLED:
                    return "Dinonaktifkan";
                case GH::$PRODUCT_STATUS_DELETED:
                    return "Dihapus";
                break;

            }
        });
        $show->field('photo_url',__('Foto'))->image();
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        
        $form = new Form(new Product);
        $form->disableEditingCheck();
        $form->disableCreatingCheck();
        $form->disableViewCheck();
        $form->disableReset();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

        $id = request()->route()->parameter('product'); 
        $product = Product::where('id', $id)->with('produsen','satuan_produk')->first();

        $form->display('id', __('ID'));
        $form->display('name','Nama Produk');
        $form->display('Nama Produsen')->default($product->produsen != null && $product->produsen->produsen_name != null ? $product->produsen->produsen_name : "-");
        $form->display('Satuan Produk')->default($product->satuan_produk->nama);
        $form->display('price', 'Harga');
        $form->display('stok');
        $form->display('Foto Produk')->default("<img src='../../../uploads/".$product->photo_url."'/>");
    
        
        $form->select('product_status','Status Product')->options([
            0 => 'Pending',
            1 => 'Ditolak',
            2 => 'Terverifikasi',
            3 => 'Dinonaktifkan',
            4 => 'Dihapus'
        ]);
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));
        return $form;
    }
}
