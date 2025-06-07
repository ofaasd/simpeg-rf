<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Santri;
use App\Models\SakuKeluar;
use App\Models\SakuMasuk;
use App\Models\UangSaku;
use App\Models\EmployeeNew;
use App\Models\Kamar;

class LaporanSaku extends Controller
{
    //
    public function index(){
        $santri = Santri::orderBy("kelas","asc")->get();
        $tgl_mulai = '2023-09-22';
        $tgl_selesai = date('Y-m-d');
        $list_santri = [];
        $list_saku = [];
        $list_santri_masuk = [];
        $murroby = [];
        $i = 1;
        $title = "Laporan Uang Saku";
        
        foreach($santri as $row){
            $list_santri[$row->no_induk] = SakuKeluar::select('jumlah')->where('tanggal','>=',$tgl_mulai)->where('tanggal','<=',$tgl_selesai)->where('no_induk',$row->no_induk)->sum('jumlah') ?? 0;
            $list_santri_masuk[$row->no_induk] = SakuMasuk::select('jumlah')->where('tanggal','>=',$tgl_mulai)->where('tanggal','<=',$tgl_selesai)->where('no_induk',$row->no_induk)->sum('jumlah') ?? 0;
            $list_saku[$row->no_induk] = UangSaku::where('no_induk',$row->no_induk)->first()->jumlah ?? 0;
            $kamar = Kamar::where('id',$row->kamar_id)->first();
            if(!empty($kamar->employee_id)){
                $murroby[$row->no_induk] = EmployeeNew::where('id',$kamar->employee_id)->first()->nama ?? '';
            }else{
                $murroby[$row->no_induk] = '';
            }
            
        }
        return view("admin/laporan/saku", compact('title','murroby','santri','list_santri','list_santri_masuk','list_saku','i'));
    }
}
