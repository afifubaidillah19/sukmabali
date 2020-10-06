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
use App\ProductCategory;

class ProductCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Kategori Produk';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProductCategory);
        $grid->rows(function (Grid\Row $row) {
            $row->column('no', $row->number + 1);
        });
        $grid->column('no');
        $grid->column('name');
        $grid->column('description');
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
        $show = new Show(ProductCategory::findOrFail($id));

        $show->field('id', __('ID'));
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
        
        $form = new Form(new ProductCategory);
        $form->text('name');
        $form->textarea('description');
        $form->image('photo_url');
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));
        return $form;
    }
}
