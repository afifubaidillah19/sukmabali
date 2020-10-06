<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\DesaAdat;
use App\Kecamatan;

class DesaAdatController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Desa Adat';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DesaAdat);
        $grid->quickSearch('nama');

        $grid->column('id', __('ID'))->sortable();
        $grid->column('nama',__('Desa Adat'));
        $grid->column('kecamatan.nama','Kecamatan');
        $grid->column('no_telp',__('No Telepon'));
        $grid->column('nama_kepala_desa');
        $grid->column('status')->display(function(){
            if($this->status == 0){
                return "<span class='btn btn-xs btn-danger'>Tidak Terverifikasi</span>";
            }else {
                return "<span class='btn btn-xs btn-success'>Terverifikasi</span>";
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
        $show = new Show(DesaAdat::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('nama',__('Desa Adat'));
        $show->field('no_telp',__('No Telepon'));
        $show->field('alamat');
        $show->field('status')->as(function(){
            if($this->status == 0) return "Tidak Terverifikasi";
            else return "Terverifikasi";
        });
        $show->field('nama_kepala_desa');
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
        $form = new Form(new DesaAdat);

        $form->display('id', __('ID'));
        $form->text('nama',__('Desa Adat'));
        $form->text('no_telp',__('No Telepon'));
        $form->text('nama_kepala_desa');
        $form->text('alamat');
        $form->select('status')->options([
            0 => 'Tidak Terverifikasi',
            1 => 'Terverifikasi'
        ]);

        $kecamatans = Kecamatan::where('status', 1)->get();
        $options = [];
        foreach($kecamatans as $k){
            $options[$k->id] = $k->nama;
        }
        $form->select('kecamatan_id','Kecamatan')->options($options);

        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));

        return $form;
    }
}
