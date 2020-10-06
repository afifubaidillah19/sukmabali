<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Produsen;
use App\Bupda;
use GH;
use Admin;
class ProdusenController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Produsen';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Produsen);
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        $grid->rows(function (Grid\Row $row) {
            $row->column('no', $row->number + 1);
        });
        $grid->column('no');
        if(Admin::user()->isRole("bupda")){
            $bupda = Bupda::where('user_id', Admin::user()->id)->first();
            $grid->model()->where('bupda_id', $bupda->id);
            $grid->disableCreateButton();
            $grid->actions(function(Grid\Displayers\Actions $actions){
                $actions->disableDelete();
                $actions->disableView();
            });
        }
        $grid->column('produsen_name',__('Nama Produsen'));
        $grid->column('phone_number',__('Nomor Telepon'));
        $grid->column('email',__('Email'));
        $grid->column('produsen_status',__('Status Produsen'))->display(function(){
            switch($this->produsen_status){
                case GH::$PRODUSEN_STATUS_PENDING:
                    return "Pending";
                break;
                case GH::$PRODUSEN_STATUS_REJECTED:
                    return "Ditolak";
                break;
                case GH::$PRODUSEN_STATUS_ENABLED:
                    return "Aktif";
                break;
                case GH::$PRODUSEN_STATUS_DISABLED:
                    return "Dinonaktifkan";
                break;
            }
        });
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
        redirect('/produsen');
        $show = new Show(Produsen::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('email',__('Email'));
        $show->field('phone_number',__('Nomor Telepon'));
        $show->field('address',__('Alamat'));
        $show->field('latitude');
        $show->field('longitude');
        $show->field('description',__('Deskripsi'));
        $show->field('verification_photo_path',__('Foto Identitas'))->image();
        $show->field('produsen_status',__('Status Produsen'))->as(function(){
            switch($this->produsen_status){
                case GH::$PRODUSEN_STATUS_PENDING:
                    return "Pending";
                break;
                case GH::$PRODUSEN_STATUS_REJECTED:
                    return "Ditolak";
                break;
                case GH::$PRODUSEN_STATUS_ENABLED:
                    return "Aktif";
                break;
                case GH::$PRODUSEN_STATUS_DISABLED:
                    return "Dinonaktifkan";
                break;
            }
        });
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
        $form = new Form(new Produsen);
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

        $form->display('id', __('ID'));
        $form->display('produsen_name',__('Nama Produsen'));
        $form->display('email',__('Email'));
        $form->display('phone_number',__('Nomor Telepon'));
        $form->display('address',__('Alamat'));
        $form->display('latitude');
        $form->display('longitude');
        $form->display('description',__('Deskripsi'));
        $form->image('verification_photo_path',__('Foto Identitas'))->disable();

        $id = request()->route()->parameter('produsen');
        $produsen = Produsen::find($id);
        $options = [];
        if($produsen->produsen_status > 1){
            $options[2] = "Setuju / Aktifkan";
            $options[3] = "Nonaktifkan";
        }else {
            $options[0] = "Pending";
            $options[1] = "Ditolak";
            $options[2] = "Setuju / Aktifkan";
            $options[3] = "Nonaktifkan";
        }
        $form->select('produsen_status')->options($options);
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));

        return $form;
    }
}
