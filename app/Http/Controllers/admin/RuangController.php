<?php

namespace App\Http\Controllers\admin;

use App\Models\Ruang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RefGedung;
use App\Models\RefJenisRuang;
use App\Models\RefLantai;

class RuangController extends Controller
{
    public function index()
    {
        $title = 'Ruang';
        $ruang = Ruang::select('aset_ruang.*', 'ref_gedung.nama as gedung', 'ref_lantai.nama as lantai', 'ref_jenis_ruang.nama as jenisRuang')
        ->leftJoin('ref_gedung', 'ref_gedung.kode', '=', 'aset_ruang.id_gedung')
        ->leftJoin('ref_lantai', 'ref_lantai.id', '=', 'aset_ruang.id_lantai')
        ->leftJoin('ref_jenis_ruang', 'ref_jenis_ruang.kode', '=', 'aset_ruang.id_jenis_ruang')
        ->get();

        $refGedung = RefGedung::all();
        $refJenisRuang = RefJenisRuang::all();
        $refLantai = RefLantai::all();
    
        return view('admin.aset.ruang.index', compact('ruang', 'refGedung', 'refLantai', 'refJenisRuang', 'title'));
    }

    public function store(Request $request)
    {

        $id = $request->id;
    
        if ($id) {

            $ruang = Ruang::updateOrCreate(
              ['id' => $id],
              [
                'id_gedung' => $request->gedung,
                'id_lantai' => $request->lantai,
                'id_jenis_ruang' => $request->jenisRuang,
                'kode' => $request->kodeRuang,
                'nama' => $request->nama,
                'kapasitas' => $request->kapasitas,
                'status' => $request->status,
                'catatan' => $request->catatan,
                'last_checking' => $request->lastChecking,
              ]
            );

        } else {
          $ruang = Ruang::updateOrCreate(
            ['id' => $id],
            [
              'id_gedung' => $request->gedung,
              'id_lantai' => $request->lantai,
              'id_jenis_ruang' => $request->jenisRuang,
              'kode' => $request->kodeRuang,
              'nama' => $request->nama,
              'kapasitas' => $request->kapasitas,
              'status' => $request->status,
              'catatan' => $request->catatan,
              'last_checking' => $request->lastChecking,
            ]
          );
        }
        if ($ruang) {
          return response()->json('Created');
        } else {
          return response()->json('Failed Create');
        }
    }

    public function edit(string $id)
    {
        $where = ['id' => $id];

        $ruang = Ruang::where($where)->first();

        return response()->json($ruang);
    }

    public function destroy(string $id)
    {
        $ruang = Ruang::where('id', $id)->first();

        $ruang->delete();
    }
}
