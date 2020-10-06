<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Bupda;
use App\DesaAdat;
use GH;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;

class BupdaController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Bupda';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Bupda);

        $grid->column('id', __('ID'))->sortable();
        $grid->column('bupda_name', __('Nama Bupda'));
        $grid->column('phone_number',__('Nomor Telepon'));
        $grid->column('desa_adat.nama',__('Desa Adat'));
        $grid->column('status')->display(function(){
            switch($this->status){
                case GH::$BUPDA_ON_PENDING:
                    return "Pending";
                break;
                case GH::$BUPDA_ON_REJECTED:
                    return "Ditolak";
                break;
                case GH::$BUPDA_ON_ENABLED:
                    return "Aktif";
                break;
                case GH::$BUPDA_ON_DISABLED:
                    return "Dinonaktifkan";
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
        $show = new Show(Bupda::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('user_id',__('Id User'));
        $show->field('bupda_name',__('Nama Bupda'));
        $show->field('phone_number',__('Nomor Telepon'));
        $show->field('address',__('Alamat'));
        $show->field('latitude',__('Latitude'));
        $show->field('longitude',__('Longitude'));
        $show->field('photo_path',__('Foto'))->image();
        $show->field('verification_photo',__('Foto Identitas'))->image();
        $show->field('status')->as(function(){
            switch($this->status){
                case GH::$BUPDA_ON_PENDING:
                    return "Pending";
                break;
                case GH::$BUPDA_ON_REJECTED:
                    return "Ditolak";
                break;
                case GH::$BUPDA_ON_ENABLED:
                    return "Aktif";
                break;
                case GH::$BUPDA_ON_DISABLED:
                    return "Dinonaktifkan";
                break;

            }
        });
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
        $form = new Form(new Bupda);
        $form->display('id', __('ID'));
        $form->display('user_id',__('Id User'));
        $form->text('bupda_name',__('Nama Bupda'));
        $form->text('phone_number',__('Nomor Telepon'));
        $form->text('address',__('Alamat'));
        $form->text('latitude',__('Latitude'));
        $form->text('longitude',__('Longitude'));
        $form->image('photo_path',__('Foto'));
        $form->image('verification_photo',__('Foto Identitas'));
        $form->select('status')->options([
            GH::$BUPDA_ON_PENDING => 'Pending',
            GH::$BUPDA_ON_REJECTED => 'Ditolak',
            GH::$BUPDA_ON_ENABLED => 'Setujui / Aktifkan',
            GH::$BUPDA_ON_DISABLED => 'Nonaktifkan'
        ]);
        $desaAdat = DesaAdat::whereNotIn('id',function($query){
                            $query->select('desa_adat_id')
                            ->from('bupda');
                        })
                        ->get();
        $options = [];
        foreach($desaAdat as $d){
            $options[$d->id] = $d->nama;
        }
        if ($form->isEditing()) {
             $id = request()->route()->parameter('bupda'); 
             $bupda = Bupda::find($id);
             $currentDesaAdat = DesaAdat::find($bupda->desa_adat_id);
             if($currentDesaAdat){
                 $options[$currentDesaAdat->id] = $currentDesaAdat->nama;
             }
        }

        $form->select('desa_adat_id',__('Desa Adat'))->options($options);
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));
        $form->saved(function($form){
             $user_id = $form->model()->user_id;
             $status = $form->model()->status;

             DB::table('admin_role_users')->where('user_id', $user_id)->delete();
             if($status == GH::$BUPDA_ON_ENABLED){
                DB::table('admin_role_users')->insert([
                    ['role_id' => 3, 'user_id' => $user_id],
                ]);
             }

             DB::table('admin_user_permissions')->where('user_id', $user_id)->delete();
             if($status == GH::$BUPDA_ON_ENABLED){
                DB::table('admin_user_permissions')->insert([
                    ['user_id' => $user_id, 'permission_id' => 1],
                    ['user_id' => $user_id, 'permission_id' => 7],
                ]);
             }
        });
        return $form;
    }
}
