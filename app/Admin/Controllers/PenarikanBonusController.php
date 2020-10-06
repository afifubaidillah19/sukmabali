<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\SatuanProduk;
use App\Bonus;
use App\PenarikanBonus;

class PenarikanBonusController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Penarikan Bonus';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PenarikanBonus);

        $grid->column('id', __('ID'))->sortable();
        $grid->column('user.name');
        $grid->column('amount','Total');
        $grid->column('status')->display(function(){
            if($this->status == 0){
                return "<span class='btn btn-xs btn-warning'>Pending</span>";
            }else if($this->status == 1){
                return "<span class='btn btn-xs btn-success'>Berhasil</span>";
            }else if($this->status == 2){
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
        $show = new Show(PenarikanBonus::findOrFail($id));

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
        $form = new Form(new PenarikanBonus);

        $form->display('id', __('ID'));
        $form->display('user.name');
        $form->display('amount',__('Total'));
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));
        $form->select('status')->options([
            0 => 'Pending',
            1 => 'Berhasil',
            2 => 'Ditolak'
        ]);
        $form->image('foto_bukti_transfer');
        return $form;
    }
}
