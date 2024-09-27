<?php

namespace App\Http\Controllers\admin\master\aset;

use App\Models\RefRuang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RefGedung;
use App\Models\RefJenisRuang;
use App\Models\RefLantai;

class MasterRuangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Master Ruang';
        $ruang = RefRuang::select('ref_ruang.*', 'ref_gedung.nama as gedung', 'ref_lantai.nama as lantai', 'ref_jenis_ruang.nama as jenisRuang')
        ->leftJoin('ref_gedung', 'ref_gedung.id', '=', 'ref_ruang.id_gedung')
        ->leftJoin('ref_lantai', 'ref_lantai.id', '=', 'ref_ruang.id_lantai')
        ->leftJoin('ref_jenis_ruang', 'ref_jenis_ruang.id', '=', 'ref_ruang.id_jenis_ruang')
        ->get();

        $refGedung = RefGedung::all();
        $refLantai = RefLantai::all();
        $refJenisRuang = RefJenisRuang::all();

        $data = [
            'title' => $title,
            'ruang' => $ruang,
            'refGedung' => $refGedung,
            'refLantai' => $refLantai,
            'refJenisRuang' => $refJenisRuang,
        ];
    
        return view('admin.master.aset.ruang.index', $data);
    }

    public function store(Request $request)
    {

        $id = $request->id;
    
        if ($id) {

            $jenisRuang = RefRuang::updateOrCreate(
              ['id' => $id],
              [
                'id_gedung' => $request->gedung,
                'id_lantai' => $request->lantai,
                'kode_ruang' => $request->kodeRuang,
                'id_jenis_ruang' => $request->jenisRuang,
                'nama' => $request->nama,
                'kapasitas' => $request->kapasitas,
              ]
            );

        } else {
          $jenisRuang = RefRuang::updateOrCreate(
            ['id' => $id],
            [
                'id_gedung' => $request->gedung,
                'id_lantai' => $request->lantai,
                'kode_ruang' => $request->kodeRuang,
                'id_jenis_ruang' => $request->jenisRuang,
                'nama' => $request->nama,
                'kapasitas' => $request->kapasitas,
              ]
          );
        }
        if ($jenisRuang) {
          return response()->json('Created');
        } else {
          return response()->json('Failed Create');
        }
    }

    public function edit(string $id)
    {
        $where = ['id' => $id];

        $jenisRuang = RefRuang::where($where)->first();

        return response()->json($jenisRuang);
    }

    public function destroy(string $id)
    {
        $jenisRuang = RefRuang::where('id', $id)->first();

        $jenisRuang->delete();
    }
}
