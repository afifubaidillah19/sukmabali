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
use Hash;

class DesaAdatController extends Controller
{
    public function getDesaAdat(){
        $data['desa_adat_list'] = DesaAdat::where('status', 1)
        ->orderBy('nama','asc')
        ->get();
        $data['kabupaten_list'] = Kabupaten::where('status', 1)->orderBy('nama','asc')->get();
        $data['kecamatan_list'] = Kecamatan::where('status',1)->orderBy('nama','asc')->get();

        return response()->json($data);
    }

    public function tambahDesaAdat(Request $request){
        $data['nama'] = $request->nama;
        $data['alamat'] = $request->alamat;
        $data['no_telp'] = $request->no_telp;

        $desaAdat = DesaAdat::create($data);
        return response()->json($desaAdat);
    }
}
