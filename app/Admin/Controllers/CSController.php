<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\User;
use App\DesaAdat;
use App\Bupda;
use App\CustomerService;
use Admin;
class CSController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Customer Service';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CustomerService);
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
            $grid->model()->where('bupda_id',$bupda->id);
        }
        $grid->column('nama');
        $grid->column('status')->display(function(){
            switch($this->status){
                case 0:
                    return "<span class='btn btn-xs btn-warning'>Pending</span>";
                break;
                case 1:
                    return "<span class='btn btn-xs btn-success'>Terverifikasi / Aktif</span>";
                break;
                case 2:
                    return "<span class='btn btn-xs btn-danger'>Dibatalkan</span>";
                break;
                case 3:
                    return "<span class='btn btn-xs btn-danger'>Tidak Aktif</span>";
                break;
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
        $show = new Show(CustomerService::findOrFail($id));

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
        $form = new Form(new CustomerService); 
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

        $id = request()->route()->parameter('customer_service');
        $cs = CustomerService::find($id);
        $form->display('id', __('ID'));
        $form->display('nama');
        $form->display('email');
        $form->display('no_telepon');
        $form->display('alamat');
        $form->display('Nam Desa Adat')->default($cs->desa_adat->nama);
        $form->display('Nama BUPDA')->default($cs->bupda->bupda_name);
        $form->display('Foto KTP')->default("<img style='width: 100%' src='../../../uploads/".$cs['photo_ktp_path']."'/>");

        $options = [];
        if($cs->status == 1 || $cs->status == 3){
            $options[1] = "Terverifikasi / Aktif";
            $options[3] = "Nonaktifkan";
        }else {
            $options[0] = "Pending";
            $options[1] = "Terverifikasi / Aktif";
            $options[2] = "Dibatalkan";
            $options[3] = "Nonaktifkan";
        }
        $form->select('status')->options($options);
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));
        $form->saving(function (Form $form) {
            $data = $form->model();
            $cs = CustomerService::where('desa_adat_id', $data->desa_adat_id)
                    ->where('status', 1)
                    ->first();
            if($cs && $form->status == 1)
                throw new \Exception("Hanya diperbolehkan mengaktifkan satu CS dalam satu desa");
        });
        return $form;
    }
}
