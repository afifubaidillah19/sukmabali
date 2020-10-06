<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\TempDesaAdat;
use App\Bupda;
use App\User;
use App\DesaAdat;
class TempDesaAdatController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Example controller';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TempDesaAdat);

        $grid->column('id', __('ID'))->sortable();
        $grid->column('nama',__('Nama Desa Adat'));
        $grid->column('user_id',__('Nama User'))->display(function(){
            if($this->type_user == 0){
                $user = User::find($this->user_id);
                return $user->name;
            }else if($this->type_user == 1){
                $bupda = Bupda::find($this->user_id);
                return $bupda->bupda_name;
            }
        });
        $grid->column('type_user',__('Tipe User'))->display(function(){
            if($this->type_user == 0){
                return "Konsumen";
            }else if($this->type_user == 1){
                return "BUPDA";
            }
        });
        $grid->column('desa_adat_id',__('Final Desa Adat'))->display(function(){
            if($this->desa_adat_id == 0){
                return "<button class='btn btn-xs btn-warning'>Pending</button>";
            }else {
                $desaAdat = DesaAdat::find($this->desa_adat_id);
                return "<button class='btn btn-xs btn-success'>".$desaAdat->nama."</button>";
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
        $show = new Show(TempDesaAdat::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('nama',__('Nama Desa Adat'));
        $show->field('user_id',__('Nama User'))->as(function(){
            if($this->type_user == 0){
                $user = User::find($this->user_id);
                return $user->name;
            }else if($this->type_user == 1){
                $bupda = Bupda::find($this->user_id);
                return "$bupda->bupda_name";
            }
        });
        
        $show->field('type_user',__('Tipe User'))->as(function(){
            if($this->type_user == 0){
                $type = 0;
                return "Konsumen";
            }else if($this->type_user == 1){
                $type = 1;
                return "BUPDA";
            }
        });

        $id = request()->route()->parameter('temp_desa_adat'); 
        $type_id = TempDesaAdat::find($id)->type_id;

        if($type_id == 0){
            $show->users('Konsumen', function($user){
                $user->setResource('/admin/user');
            });
        }else if($type_id == 1){
            $show->bupda('BUPDA', function($bupda){
                $bupda->setResource('/admin/bupda');
            });
        }
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
        $form = new Form(new TempDesaAdat);
        $id = request()->route()->parameter('temp_desa_adat'); 
        $tempDesaAdat = TempDesaAdat::find($id);
        
        $form->display('id', __('ID'));
        $form->display('nama',__('Nama Desa Adat'));
        if($tempDesaAdat->type_user == 0){
            $form->display('users.name',__('Nama User'));
        }else {
            $form->display('bupda.bupda_name',__('Nama BUPDA'));
        }
        $form->select('type_user','Tipe User')->options([
            0 => 'Konsumen',
            1 => 'BUPDA'
        ])->readonly();

        $desaAdat = [];
        if($tempDesaAdat->type_user == 0){
            $desaAdat = DesaAdat::all();
        }else if($tempDesaAdat->type_user == 1){
            $desaAdat = DesaAdat::whereNotIn('id',function($query){
                $query->select('desa_adat_id')
                ->from('bupda');
            })
            ->get();
        }
        $options = [];
        foreach($desaAdat as $d){
            $options[$d->id] = $d->nama;
        }
        $form->select('desa_adat_id','Final Desa Adat')->options($options);
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));
        $form->saved(function($form){
            $user_id = $form->model()->user_id;
            $type_user = $form->model()->type_user;
            $desa_adat_id = $form->model()->desa_adat_id;
            if($type_user == 0){
                User::where('id', $user_id)->update(['desa_adat_id' => $desa_adat_id]);
            }else if($type_user == 1){
                Bupda::where('id', $user_id)->update(['desa_adat_id' => $desa_adat_id]);
            }
       });
        return $form;
    }
}
