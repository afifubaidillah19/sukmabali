<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Transaction;
use App\TransactionItem;
use App\Notification;
use App\ProductHistory;
use App\Product;
use GH;
use App\User;
use App\Bupda;
use Admin;

class TransactionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Transaksi';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Transaction);
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
            });
            $bupda = Bupda::where('user_id', Admin::user()->id)->first();
            $grid->model()->where('bupda_id', $bupda->id);
            $grid->header(function ($query) {
                $data['bupda'] = Bupda::where('user_id', Admin::user()->id)->first();
                $view = view('admin.header_transaction', $data);
                
                return $view;
            });
        }
        $grid->column('user_id',__('Pemesan'))->display(function(){
            $user = User::find($this->user_id);
            if($user){
                return $user->name;
            }else {
                return "";
            }
        });
        $grid->column('transaction_status','Status Transaksi')->display(function(){
            switch($this->transaction_status){
                case GH::$TRANSACTION_STATUS_ON_PROCCESS:
                    return "Sedang Diproses";
                break;
                case GH::$TRANSACTION_STATUS_ACCEPTED_BY_BUPDA:
                    return "Diterima Oleh Bupda";
                break;
                case GH::$TRANSACTION_STATUS_DONE:
                    return "Selesai";
                break;
                case GH::$TRANSACTION_STATUS_CANCELED:
                    return "Dibatalkan";
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
        $show = new Show(Transaction::findOrFail($id));
        if(Admin::user()->isRole('bupda')){
            $show->panel()
                ->tools(function ($tools) {
                    $tools->disableDelete();
                });;
        }
        $show->field('transaction_id', __('ID'));
        $show->field('transaction_status')->as(function(){
            switch($this->transaction_status){
                case GH::$TRANSACTION_STATUS_ON_PROCCESS:
                    return "Sedang Diproses";
                break;
                case GH::$TRANSACTION_STATUS_ACCEPTED_BY_BUPDA:
                    return "Diterima Oleh Bupda";
                break;
                case GH::$TRANSACTION_STATUS_DONE:
                    return "Selesai";
                break;
                case GH::$TRANSACTION_STATUS_CANCELED:
                    return "Dibatalkan";
                break;

            }
        });
        $show->field('bupda_id',__('Nama Bupda'))->as(function(){
            return $this->bupda->bupda_name;
        });
        $show->field('delivery_address',__('Alamat Antar'));
        $show->field('delivery_fee',__('Total Biaya Antar'));
        $show->field('total_payment',__('Total Pembayaran'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        $show->transaction_item('Produk', function($ti){
            if(Admin::user()->isRole('bupda')){
                $ti->disableCreateButton();
                $ti->disableActions();
            }
            $ti->transaction_item_id('ID');
            $ti->produsen_name('Produsen')->display(function(){
                return $this->product->produsen->produsen_name;
            });
            $ti->product_name('Produk')->display(function(){
                return $this->product->name;
            });
            $ti->price('Harga');
            $ti->quantity();
            $ti->total_harga()->display(function(){
                return $this->quantity * $this->price;
            });
            $ti->delivery_fee('Biaya Antar');
        });

        $show->users('Pembeli', function($user){
            if(Admin::user()->isRole('bupda')){
                $user->panel()
                    ->tools(function ($tools) {
                        $tools->disableEdit();
                        $tools->disableList();
                        $tools->disableDelete();
                    });;
            }
            $user->name('Nama');
            $user->phone_number('No Telepon');
            $user->email();
            $user->desa_adat('Desa Adat')->as(function(){
                return $this->desa_adat['nama'];
            });
            $user->ktp_path()->image();
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
        $form = new Form(new Transaction);
        $bupda = Bupda::where('user_id', Admin::user()->id)->first();
        if(Admin::user()->isRole('bupda')){
            $form->tools(function (Form\Tools $tools) {
                $tools->disableDelete();
            });
        }
        if($bupda->auto_verification_order == 1){
            $form->disableSubmit();
        }
        $form->disableEditingCheck();
        $form->disableCreatingCheck();
        $form->disableViewCheck();
        $form->disableReset();
        $form->display('transaction_id', __('ID'));
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));
        
        $id = request()->route()->parameter('transaction');
        $transaction = Transaction::where('transaction_id', $id)->first();

        $options = [];
        if($transaction->transaction_status == 0){
            $options[0] = 'Sedang Diproses';
            $options[1] = 'Diterima Oleh BUPDA';
            $options[3] = 'Dibatalkan';
        }else if($transaction->transaction_status == 1){
            $options[1] = 'Diterima Oleh BUPDA';
        }else if($transaction->transaction_status == 2){
            $options[2] = 'Selesai';
        }else if($transaction->transaction_status == 3){
            $options[3] = 'Dibatalkan';
        }
        $form->select('transaction_status')->options($options);
        
        
        $form->saved(function(Form $form){
            if($form->model()->transaction_status == GH::$TRANSACTION_STATUS_CANCELED){
                $transactionItems = TransactionItem::where('transaction_id', $form->model()->transaction_id)
                ->get();
                $transaction = Transaction::where('transaction_id', $form->model()->transaction_id)->first();
                foreach($transactionItems as $ti){
                    TransactionItem::where('transaction_item_id', $ti->transaction_item_id)
                            ->update([
                                'transaction_status' => GH::$TRANSACTION_ITEM_STATUS_CANCELED
                            ]);
                    
                    $history = ProductHistory::where('product_history.transaction_item_id', $ti->transaction_item_id)
                                ->first();
                    if($history){
                        $history->delete();
                    }
                }
                $user = User::find($transaction->user_id);
                $bupda = Bupda::find($transaction->bupda_id);
                Notification::create([
                    'notification_type' => 1,
                    'sender_id' => 0,
                    'receiver_id' => $user->id,
                    'title' => 'Pesanan Ditolak',
                    'message' => 'Pesanan anda ditolak oleh BUPDA '.$bupda->bupda_name,
                    'notification_status' => 0
                ]);
            }else if($form->model()->transaction_status == 1){
                $transactionItems = TransactionItem::where('transaction_id', $form->model()->transaction_id)
                ->get();
                $transaction = Transaction::where('transaction_id', $form->model()->transaction_id)->first();
                $user = User::find($transaction->user_id);
                $bupda = Bupda::find($transaction->bupda_id);
            
                foreach($transactionItems as $ti){
                    $product = Product::where('id', $ti->product_id)->first();
                    GH::sendNotification([
                        'notification_type' => 2,
                        'sender_id' => 0,
                        'receiver_id' => $product->produsen_id,
                        'title' => 'Pesanan Baru',
                        'message' => 'Pesanan baru dari '.$user->name,
                        'notification_status' => 0,
                        'priority' => 1
                        ]);
                }

                GH::sendNotification([
                    'notification_type' => 1,
                    'sender_id' => 0,
                    'receiver_id' => $user->id,
                    'title' => 'Pesanan Diterima',
                    'message' => 'Pesanan anda diterima oleh BUPDA '.$bupda->bupda_name,
                    'notification_status' => 0,
                    'priority' => 0
                    ]);
            }
        });
        return $form;
    }
}
