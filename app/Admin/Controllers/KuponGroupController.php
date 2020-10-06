<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\KuponGroup;
use App\Produsen;
use App\Kupon;
use GH;

class KuponGroupController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Master Kupon';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new KuponGroup);

        $grid->column('id', __('ID'))->sortable();
        $grid->column('name',__('Nama'));
        $grid->column('total',__('Jumlah Kupon'));
        $grid->column('amount_per_kupon',__('Nilai Per Kupon'));
        $grid->column('expired',__('Kedaluwarsa'));
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
        $show = new Show(KuponGroup::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('name',__('Nama'));
        $show->field('total',__('Jumlah Kupon'));
        $show->field('amount_per_kupon',__('Nilai Per Kupon'));
        $show->field('expired',__('Kedaluwarsa'));
        $show->field('description','Deskripsi');
        $show->field('foto_path')->image();
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        $show->kupon('Daftar Kupon', function($k){
            $k->id();
            $k->code('Kode Kupon');
            $k->status()->display(function(){
                if($this->status == 1){
                    return "<span class='btn btn-xs btn-success'>Sudah digunakan</span>";
                }else {
                    return "";
                }
            });
            $k->user_id()->display(function(){
                if($this->user_id){
                    return $this->user->name;
                }else {
                    return "";
                }
            });
        });
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new KuponGroup);

        $form->display('id', __('ID'));
        $form->text('name',__('Nama Kupon'));
        $form->number('total',__('Jumlah Kupon'));
        $form->text('amount_per_kupon',__('Nilai Per Kupon'));
        $form->textarea('description',__('Deskripsi'));
        $form->date('expired',__('Tanggal Kedaluwarsa'));
        $form->image('foto_path',__('Foto'));

        $produsens = Produsen::all();
        $optionsPK = [];
        foreach($produsens as $p){
            $optionsPK[$p->id] = "[".$p->bupda->desa_adat->nama."] ".$p->produsen_name;
        }
        $form->listbox('produsens','Produsen')->options($optionsPK);
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));

        $form->saved(function (Form $form) {
            for($i=0; $i<$form->model()->total; $i++){
                $code = GH::generateUniqueCode(10);
                $kupon = new Kupon();
                $kupon->code = $code;
                $kupon->kupon_group_id = $form->model()->id;
                $kupon->status = 0;
                $kupon->user_id = null;
                $kupon->save();
            }
        });
        return $form;
    }
}
