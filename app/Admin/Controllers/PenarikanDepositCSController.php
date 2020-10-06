<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\PenarikanDepositCS;
use App\CustomerService;
use App\Bupda;
use App\DepositCS;
use Admin;
class PenarikanDepositCSController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Penarikan Deposit Dari CS';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PenarikanDepositCS);
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
            $grid->disableCreateButton();
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableDelete();
                $actions->disableView();
            });
            $bupda = Bupda::where('user_id', Admin::user()->id)->first();
            $grid->model()->join('customer_service','customer_service.id','penarikan_deposit_cs.cs_id')
                    ->where('customer_service.bupda_id', $bupda->id)
                    ->select('penarikan_deposit_cs.*');
        }
        $grid->column('cs.nama','Nama CS');
        $grid->column('status')->display(function(){
            if($this->status == 0)
                return "<span class='btn btn-xs btn-warning'>Pending</span>";
            if($this->status == 1)
                return "<span class='btn btn-xs btn-success'>Diterima</span>";
            if($this->status == 2)
                return "<span class='btn btn-xs btn-danger'>Ditolak</span>";
        });
        $grid->column('total')->display(function(){
            $d = $this->detail_penarikan_deposit_cs_list;
            $total = 0;
            foreach($d as $i){
                $total+=$i->deposit_cs->total_deposit;
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
        $show = new Show(PenarikanDepositCS::findOrFail($id));

        $show->field('id', __('ID'));
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
        $form = new Form(new PenarikanDepositCS);
        $form->disableEditingCheck();
        $form->disableCreatingCheck();
        $form->disableViewCheck();
        $form->disableReset();
        if(Admin::user()->isRole('bupda')){
            $form->tools(function (Form\Tools $tools) {
                $tools->disableDelete();
                $tools->disableView();
            });
        }
        $id = request()->route()->parameter('penarikan_deposit_c');
        $penarikanDepositCs = PenarikanDepositCS::where('id', $id)
                    ->with('detail_penarikan_deposit_cs_list',
                            'detail_penarikan_deposit_cs_list.deposit_cs')
                    ->first();
        
        $cs = CustomerService::find($penarikanDepositCs->cs_id);
        $total = 0;
        foreach($penarikanDepositCs->detail_penarikan_deposit_cs_list as $d){
            $total+=$d->deposit_cs->total_deposit;
        }
        $form->display('nama')->default($cs->nama);
        $form->display('total')->default($total);
        $options = [
            1 => 'Terima',
            2 => 'Tolak'
        ];

        if($penarikanDepositCs->status == 0){
            $form->select('status')->options($options);
        }else {
            $form->select('status')->options($options)->disable();
        }
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));

        $form->saved(function(Form $form){
            if($form->model()->status == 1){
                foreach($form->model()->detail_penarikan_deposit_cs_list as $d){
                    DepositCS::where('id', $d->deposit_cs_id)->update(['status' => 2]);
                }
            }
        });
        return $form;
    }
}
