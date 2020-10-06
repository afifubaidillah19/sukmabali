<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Transaction;
use App\TransactionItem;
use App\ProductHistory;
use App\Product;
use App\DepositBupda;
use App\Bupda;
use App\DepositSukmabali;
use GH;
use App\User;
use Admin;

class DepositSukmabaliController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Example controller';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DepositSukmabali);
        $grid->disableCreateButton();
        $grid->disableActions();
        $grid->column('id');
        $grid->column('persen')->display(function(){
            $persen = $this->persen;
            $persen = $persen * 100;
            return $persen.'%';
        });
        $grid->column('total_deposit');
        $grid->column('status')->display(function(){
            if($this->status == 1){
                return "<span class='btn btn-xs btn-success'>Sudah Ditarik</span>";
            }
        });
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(DepositSukmabali::findOrFail($id));
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DepositSukmabali);
        return $form;
    }
}
