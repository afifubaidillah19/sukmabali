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
use App\DepositProdusen;
use App\Bupda;
use GH;
use App\User;
use Admin;

class DepositProdusenController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Deposit Produsen';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DepositProdusen);
        $bupda = Bupda::where('user_id', Admin::user()->id)->first();
        $grid->model()->where('transaction_item_id',null);
        $grid->disableCreateButton();
        
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->rows(function (Grid\Row $row) {
            $row->column('no', $row->number + 1);
        });
        $grid->column('no');
        
        $grid->column('produsen.produsen_name',__('Nama'));
        $grid->column('total_deposit');
        $grid->column('status')->display(function(){
            if($this->status == 1){
                return "<span class='btn btn-xs btn-success'>Terverifikasi</span>";
            }else if($this->status == 0){
                return "<span class='btn btn-xs btn-warning'>Pending</span>";
            }else {
                return "<span class='btn btn-xs btn-danger'>Ditolak</span>";
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
        $show = new Show(DepositProdusen::findOrFail($id));
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DepositProdusen);
        $form->image('foto_bukti_transfer');
        $form->text('total_deposit');
        $form->select('status')->options([
            0 => 'Pending',
            1 => 'Terverifikasi',
            2 => 'Ditolak'
        ]);
        return $form;
    }
}
