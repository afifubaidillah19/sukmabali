<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Slider;
use Admin;

class SliderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Slider / Iklan';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Slider);
        if(Admin::user()->isRole('administrator')){
            $grid->model()->where('type_user', 'administrator');
        }else if(Admin::user()->isRole('bupda')){
            $grid->model()->where('type_user','bupda')->where('user_id', Admin::user()->id);
        }else if(Admin::user()->isRole('pemprov')){
            $grid->model()->where('type_user','pemprov');
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
        $grid->column('nama');
        $grid->column('type_user', __('By'));
        $grid->column('status')->display(function(){
            if($this->status == 0) return "Tidak Tampil";
            else return "Tampil";
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
        $show = new Show(Slider::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('nama');
        $show->field('type_user', __('By'));
        $show->field('photo_path',__('Foto Banner'))->image();
        $show->field('status')->as(function(){
            if($this->status == 0) return "Tidak Tampil";
            else return "Tampil";
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
        $form = new Form(new Slider);
        $form->display('id', __('ID'));
        $form->text('nama');
        $form->textarea('deskripsi');
        $form->image('photo_path',__('Foto Banner'));
        $form->select('status')->options([
            0 => "Tidak Tampil",
            1 => "Tampil"
        ]);
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));
        $form->saved(function($form){
            $id = $form->model()->id;
            $user_id = Admin::user()->id;
            if(Admin::user()->isRole('administrator')){
                Slider::where('id',$id)->update([
                    'user_id' => $user_id,
                    'type_user' => 'administrator'
                ]);
            }else if(Admin::user()->isRole('pemprov')){
                Slider::where('id',$id)->update([
                    'user_id' => $user_id,
                    'type_user' => 'pemprov'
                ]);
            }else if(Admin::user()->isRole('bupda')){
                Slider::where('id',$id)->update([
                    'user_id' => $user_id,
                    'type_user' => 'bupda'
                ]);
            }
        });
        return $form;
    }
}
