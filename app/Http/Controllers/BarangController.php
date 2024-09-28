<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use App\Models\Barang;
use App\Models\Elektronik;
use Illuminate\Http\Request;
use App\Models\RefJenisBarang;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Aset Barang';
        $barang = Barang::select('aset_barang.*', 'ref_jenis_barang.nama as jenisBarang', 'aset_ruang.kode_ruang as ruang')
        ->leftJoin('ref_jenis_barang', 'ref_jenis_barang.id', '=', 'aset_barang.id_jenis_barang')
        ->leftJoin('aset_ruang', 'aset_ruang.id', '=', 'aset_barang.id_ruang')
        ->get();

        $refJenisBarang = RefJenisBarang::all();
        $ruang = Ruang::all();

        $asetElektronik = Elektronik::select('aset_elektronik.*', 'aset_ruang.kode_ruang as ruang')
        ->leftJoin('aset_ruang', 'aset_ruang.id', '=', 'aset_elektronik.id_ruang')
        ->get();
    
        return view('admin.aset.barang.index', compact('barang', 'refJenisBarang', 'asetElektronik', 'ruang', 'title'));
    }

    public function store(Request $request)
    {

        $id = $request->id;
    
        if ($id) {

            $barang = Barang::updateOrCreate(
              ['id' => $id],
              [
                'id_ruang' => $request->ruang,
                'id_jenis_barang' => $request->jenisBarang,
                'nama' => $request->nama,
                'kondisi_penerimaan' => $request->kondisiPenerimaan,
                'tanggal_perolehan' => $request->tglPerolehan,
                'catatan' => $request->catatan,
                'status' => $request->status,
              ]
            );

        } else {
          $barang = Barang::updateOrCreate(
            ['id' => $id],
            [
                'id_ruang' => $request->ruang,
                'id_jenis_barang' => $request->jenisBarang,
                'nama' => $request->nama,
                'kondisi_penerimaan' => $request->kondisiPenerimaan,
                'tanggal_perolehan' => $request->tglPerolehan,
                'catatan' => $request->catatan,
                'status' => $request->status,
            ]
          );
        }
        if ($barang) {
          return response()->json('Created');
        } else {
          return response()->json('Failed Create');
        }
    }

    public function edit(string $id)
    {
        $where = ['id' => $id];

        $barang = Barang::where($where)->first();

        return response()->json($barang);
    }

    public function destroy(string $id)
    {
        $barang = Barang::where('id', $id)->first();

        $barang->delete();
    }
}
