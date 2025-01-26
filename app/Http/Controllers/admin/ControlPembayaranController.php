<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\TbBukatutup;
use App\Http\Controllers\Controller;

class ControlPembayaranController extends Controller
{
    //
    public function index(){
      $bukatutup = TbBukatutup::orderBy('id','desc')->limit(1)->first();
      $title = 'Bukat Tutup Pembayaran';
      return view('admin.pembayaran.buka_tutup', compact('title', 'bukatutup'));
    }

    public function store(){
      $bukatutup = TbBukatutup::orderBy('id','desc')->limit(1)->first();
      $status = 0;
      if($bukatutup->status == 0){
        $status = 1;
      }
      $new_bukatutup = new TbBukatutup;
      $new_bukatutup->status = $status;
      $new_bukatutup->save();
      return redirect('/admin/pembayaran/bukatutup');
    }
}
