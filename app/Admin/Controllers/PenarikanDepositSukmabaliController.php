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
use App\DepositSukmabali;
use App\PenarikanDepositSukmabali;
use App\DetailPenarikanDepositSukmabali;
use Admin;
class PenarikanDepositSukmabaliController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Penarikan Deposit Dari SukmaBali';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PenarikanDepositSukmabali);
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
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
                $actions->disableDelete();
            });
        }else if(Admin::user()->isRole('administrator')){
            $grid->disableActions();
        }
        $grid->column('total')->display(function(){
            $deposit = PenarikanDepositSukmabali::where('id', $this->id)
            ->with('detail_penarikan_deposit_sukmabali_list','detail_penarikan_deposit_sukmabali_list.deposit_sukmabali')
            ->first()->toArray();

            $total = 0;
            foreach($deposit['detail_penarikan_deposit_sukmabali_list'] as $d){
                $total+=$d['deposit_sukmabali']['total_deposit'];
            }
            return $total;
        });
        $grid->column('status')->display(function(){
            if($this->status == 0){
                return "<span class='btn btn-xs btn-warning'>Pending</span>";
            }else if($this->status == 1){
                return "<span class='btn btn-xs btn-success'>Sukses</span>";
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
        $show = new Show(PenarikanDepositSukmabali::findOrFail($id));
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PenarikanDepositSukmabali);
        $form->disableEditingCheck();
        $form->disableCreatingCheck();
        $form->disableViewCheck();
        $form->disableReset();

        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });

       
        $deposit = DepositSukmabali::where('status',0)->get();
        
        if(Admin::user()->isRole('administrator')){
            $multi = [];
            foreach($deposit as $d){
                $multi[$d->id] = $d->total_deposit;
            }
            $form->listbox('tmp', 'Deposit')->options($multi)->required();;
        
            $form->saved(function(Form $form){
                $tmp = $form->model()->tmp;
                foreach($tmp as $t){
                    DetailPenarikanDepositSukmabali::create([
                        'penarikan_deposit_sukmabali_id' => $form->model()->id,
                        'deposit_sukmabali_id' => $t
                    ]);
                }
            });
        }else if(Admin::user()->isRole('bupda')){
            $id = request()->route()->parameter('penarikan_deposit_sukmabali');
            $penarikan = PenarikanDepositSukmabali::find($id);
            $options = [];
            if($penarikan->status == 0){
                $options[0] = "Pending";
                $options[1] = "Verifikasi";
                $options[2] = "Ditolak";
            }else if($penarikan->status == 1){
                $options[1] = "Verifikasi";
            }else if($penarikan->status == 2){
                $options[2] = "Ditolak";
            }

            $form->select('status')->options($options);
            $form->saved(function(Form $form){
                $tmp = $form->model()->tmp;
                foreach($tmp as $t){
                    DepositSukmabali::where('id', $t)->update(['status' => 1]);
                }
            });
        }
        return $form;
    }
}
