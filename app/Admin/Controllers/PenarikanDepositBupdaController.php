<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\PenarikanDepositCS;
use App\PenarikanDepositBupda;
use App\DepositBupda;
use App\DetailPenarikanDepositBupda;
use App\CustomerService;
use App\Bupda;
use App\DepositCS;
use Admin;
class PenarikanDepositBupdaController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Penarikan Deposit BUPDA';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PenarikanDepositBupda);
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
            $grid->disableActions();
            $bupda = Bupda::where('user_id', Admin::user()->id)->first();
            $grid->model()->where('bupda_id', $bupda->id);
        }
        $grid->column('total')->display(function(){
            $deposit = PenarikanDepositBupda::where('id', $this->id)
            ->with('detail_penarikan_deposit_bupda_list','detail_penarikan_deposit_bupda_list.deposit_bupda')
            ->first()->toArray();

            $total = 0;
            foreach($deposit['detail_penarikan_deposit_bupda_list'] as $d){
                $total+=$d['deposit_bupda']['total_deposit'];
            }
            return $total;
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
        $show = new Show(PenarikanDepositBupda::findOrFail($id));
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PenarikanDepositBupda);
        $form->disableEditingCheck();
        $form->disableCreatingCheck();
        $form->disableViewCheck();
        $form->disableReset();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });

        $id = Admin::user()->id;
        $bupda = Bupda::where('user_id', $id)->first();
        $deposit = DepositBupda::where('bupda_id', $bupda->id)->where('status',0)->get();

        $form->text('bupda_id')->value($bupda->id)->readonly();
        
        $multi = [];
        foreach($deposit as $d){
            $multi[$d->id] = $d->total_deposit;
        }
        $form->listbox('tmp', 'Deposit')->options($multi)->required();;
    
        $form->saved(function(Form $form){
            $tmp = $form->model()->tmp;
            foreach($tmp as $t){
                DepositBupda::where('id', $t)->update(['status' => 1]);
                DetailPenarikanDepositBupda::create([
                    'penarikan_deposit_bupda_id' => $form->model()->id,
                    'deposit_bupda_id' => $t
                ]);
            }
        });
        return $form;
    }
}
