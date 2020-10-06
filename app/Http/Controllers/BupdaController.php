<?php

namespace App\Http\Controllers;

use GH;
use App\Bupda;
use Illuminate\Http\Request;
use App\Review;
use App\AdminUser;
use App\Http\Controllers\Redirect;
use App\DesaAdat;
use App\Kabupaten;
use App\Kecamatan;
use App\TempDesaAdat;
use App\Transaction;
use App\TransactionItem;
use App\User;
use App\Product;
use Hash;
use App\Notification;

class BupdaController extends Controller
{
    public function setAutoVerificationOrder(Request $request){
        $bupda = Bupda::find($request->id);
        $bupda->auto_verification_order = $request->checked;
        if($bupda->save()){
            if($bupda->auto_verification_order == 1){
                $transaction = Transaction::where('transaction_status', 0)->where('bupda_id', $bupda->id)->get()->toArray();
                foreach($transaction as $t){
                    $user = User::find($t['user_id']);
                    $transactionItem = TransactionItem::where('transaction_id', $t['transaction_id'])->get()->toArray();
                    foreach($transactionItem as $ti){
                        $product = Product::find($ti['product_id']);
                        Notification::create([
                            'notification_type' => 2,
                            'sender_id' => 0,
                            'receiver_id' => $product->produsen_id,
                            'title' => 'Pesanan Baru',
                            'message' => 'Pesanan baru dari '.$user->name,
                            'notification_status' => 0
                        ]);
                    }
                    Notification::create([
                        'notification_type' => 1,
                        'sender_id' => 0,
                        'receiver_id' => $user->id,
                        'title' => 'Pesanan Diterima',
                        'message' => 'Pesanan anda diterima oleh BUPDA '.$bupda->bupda_name,
                        'notification_status' => 0
                    ]);
                }
                Transaction::where('transaction_status',0)->where('bupda_id', $bupda->id)->update(['transaction_status' => 1]);
            }
            return 1;
        }else {
            return 0;
        }
    }
    public function index(){
        $data['desaAdat'] = DesaAdat::whereNotIn('id',function($query){
            $query->select('desa_adat_id')
            ->from('bupda');
        })
        ->groupBy('desa_adat.id')
        ->orderBy('desa_adat.nama','asc')
        ->get();
        $data['kecamatan'] = Kecamatan::where('status', 1)->orderBy('nama','asc')->get();
        $data['kabupaten'] = Kabupaten::where('status', 1)->orderBy('nama','asc')->get();
        return view('register_bupda', $data);
    }
    
    public function store(Request $request){
        $p = $request->password;
        $p2 = $request->konfirmasi_password;
        if(strlen($p) < 6){
            return redirect()->back()->withInput()->with('message', 'Panjang karakter password harus lebih dari atau sama dengan 6');;
            // return redirect()->back()->with('message', 'Panjang karakter password harus lebih dari atau sama dengan 6');
        }
        if(strlen($p) != strlen($p2)){
            return redirect()->back()->withInput()->with('message', 'Konfirmasi password salah');
            // return redirect()->back()->with('message', 'Konfirmasi password salah');
        }

        $bupda['bupda_name'] = $request->bupda_name;
        $bupda['phone_number'] = $request->phone_number;
        $bupda['desa_adat_id'] = $request->desa_adat_id;
        $bupda['address'] = $request->address;
        $bupda['description'] = $request->description;
        $bupda['latitude'] = $request->latitude;
        $bupda['longitude'] = $request->longitude;
        $bupda['no_rekening'] = '';
        $bupda['nama_bank'] = '';
        $bupda['an'] = '';
        // $bupda['verification_photo'] = GH::uploadFile($request->file('ktp'), 'merchant');
        // $bupda['photo_path'] = GH::uploadFile($request->file('bupda_photo'), 'merchant');
        $bupda['desa_adat_id'] = $request->desa_adat_id;

        $count = Bupda::where('phone_number', $bupda['phone_number'])->count();
        if($count > 0){
            return redirect()->back()->withInput()->with('message', 'No. telepon telah digunakan');
            // return redirect()->back()->with('message', 'No. telepon telah digunakan');
        }

        $adminUser['name']= $bupda['bupda_name'];
        $adminUser['username'] = $bupda['phone_number'];
        // $adminUser['avatar'] = $bupda['photo_path'];
        $adminUser['password'] = Hash::make($p);

        $id = AdminUser::create($adminUser)->id;
        $bupda['user_id'] = $id;
        $bupda = Bupda::create($bupda);
        if($bupda->desa_adat_id == 0){
            $temp_desa_adat = $request->temp_desa_adat;
            $desa['nama'] = $temp_desa_adat;
            $desa['user_id'] = $bupda->id;
            $desa['type_user'] = 1;
            TempDesaAdat::insert($desa);
        }

        return redirect('register_success');
        
    }
    public function getBupdaList(Request $request){
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $bupda = Bupda::all();
        $bupdaTemp = [];

        for($i=0; $i<sizeOf($bupda); $i++){
            $rating = Review::join('transaction_item','transaction_item.transaction_item_id','review.transaction_item_id')
                    ->join('transaction','transaction.transaction_id','transaction_item.transaction_id')
                    ->where('bupda_id',$bupda[$i]['id'])
                    ->get();
            
            $countRating = sizeOf($rating);
            $sumRating = $rating->sum('rating');
            $resultRating = $countRating == 0 ? 0 : $sumRating / $countRating;
            $resultRating = floatval(number_format((float)($resultRating), 1, '.', ''));
            if($resultRating == 0) $resultRating = 0;
            $bupda[$i]['rating'] = $resultRating;
            if($latitude == null || $longitude == null){
                $bupda[$i]['distance'] = 0.0;
            }else {
                $bupda[$i]['distance'] = floatval(number_format((float)GH::haversineGreatCircleDistance($latitude, $longitude,
                    doubleval($bupda[$i]->latitude), doubleval($bupda[$i]->longitude)), 2, '.', ''));
            }
            array_push($bupdaTemp, $bupda[$i]);
        }
        usort($bupdaTemp, function($a, $b) {
            if(  $a->distance ==  $b->distance ){ return 0 ; } 
            return ($a->distance < $b->distance) ? -1 : 1;
        });

        return response()->json($bupdaTemp);
    }
}
