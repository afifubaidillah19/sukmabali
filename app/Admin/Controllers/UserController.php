<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\User;
use App\DesaAdat;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);

        $grid->column('id', __('ID'))->sortable();
        $grid->column('name',__('Nama'));
        $grid->column('email');
        $grid->column('phone_number', __('No Telepon'));
        $grid->column('desa_adat.nama',__('Desa Adat'));
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('name',__('Nama'));
        $show->field('email',__('Email'));
        $show->field('ktp_path',__('Foto KTP'))->image();
        $show->field('phone_number',__('No telepon'));
        $show->field('address',__('Alamat'));
        $show->field('latitude');
        $show->field('longitude');
        $show->field('firebase_user_id');
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->desa_adat('Desa Adat', function($desaAdat){
            $desaAdat->setResource('/admin/desa_adat');
            $desaAdat->id();
            $desaAdat->nama();
            $desaAdat->alamat();
            $desaAdat->no_telp();
            $desaAdat->created_at();
            $desaAdat->updated_at();
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
        $form = new Form(new User);

        $form->display('id', __('ID'));
        $form->text('name',__('Nama'));
        $form->text('email',__('Email'));
        $form->text('phone_number',__('No telepon'));
        $form->text('address',__('Alamat'));
        $form->text('latitude');
        $form->text('longitude');
        $form->text('firebase_user_id');
        $desaAdat = DesaAdat::all();
        $options = [];
        foreach($desaAdat as $d){
            $options[$d->id] = $d->nama;
        }
        if ($form->isEditing()) {
            $id = request()->route()->parameter('user'); 
                $user = User::find($id);
                $currentDesaAdat = DesaAdat::find($user->desa_adat_id);
                if($currentDesaAdat){
                $options[$currentDesaAdat->id] = $currentDesaAdat->nama;
            }
        }
        $form->select('desa_adat_id',__('Desa Adat'))->options($options);
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));

        return $form;
    }
}
