<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Kabupaten;

class KabupatenController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Kabupaten';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Kabupaten);
        $grid->quickSearch('nama');
        $grid->column('id', __('ID'))->sortable();
        $grid->column('nama');
        $grid->column('status')->display(function(){
            if($this->status == 1){
                return "<span class='btn btn-xs btn-success'>Aktif</span>";
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
        $show = new Show(Kabupaten::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('nama');
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
        $form = new Form(new Kabupaten);

        $form->display('id', __('ID'));
        $form->text('nama');
        $form->select('status')->options([
            0 => 'Tidak Aktif',
            1 => 'Aktif'
        ]);
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));

        return $form;
    }
}
