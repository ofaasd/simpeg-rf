<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\SakuKeluar;
use App\Models\UangSaku;

class LaporanSaku extends Controller
{
    //
    public function index(){
        $santri = Santri::all();
        $tgl_mulai = '2023-09-22';
        $tgl_selesai = date('Y-m-d');
        $list_santri = [];
        $list_saku = [];
        $i = 1;
        $title = "Laporan Uang Saku";
        foreach($santri as $row){
            $list_santri[$row->no_induk] = SakuKeluar::select('jumlah')->where('tanggal','>=',$tgl_mulai)->where('tanggal','<=',$tgl_selesai)->where('no_induk',$row->no_induk)->sum('jumlah') ?? 0;
            $list_saku[$row->no_induk] = UangSaku::where('no_induk',$row->no_induk)->first()->jumlah ?? 0;
        }
        return view("admin/laporan/saku", compact('title','santri','list_santri','list_saku','i'));
    }
}
