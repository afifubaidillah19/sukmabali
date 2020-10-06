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
use GH;
use App\User;
use Admin;

class DepositBupdaController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Deposit BUPDA';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DepositBupda);
        if(Admin::user()->isRole('bupda')){
            $bupda = Bupda::where('user_id', Admin::user()->id)->first();
            $grid->model()->where('bupda_id', $bupda->id)->select('deposit_bupda.*')
                    ->orderBy('deposit_bupda.id','asc');
            $grid->disableCreateButton();
            $grid->disableActions();
        }
        
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->rows(function (Grid\Row $row) {
            $row->column('no', $row->number + 1);
        });
        $grid->column('no');

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
        $show = new Show(DepositBupda::findOrFail($id));
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DepositBupda);
        return $form;
    }
}
