<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RefJenisBarang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Aset Barang';
        $barang = Barang::select('aset_barang.*', 'ref_jenis_barang.nama as jenisBarang')
        ->leftJoin('ref_jenis_barang', 'ref_jenis_barang.id', '=', 'aset_barang.id_jenis_barang')
        ->get();

        $refJenisBarang = RefJenisBarang::all();
    
        return view('admin.aset.barang.index', compact('barang', 'refJenisBarang', 'title'));
    }

    public function store(Request $request)
    {

        $id = $request->id;
    
        if ($id) {

            $barang = Barang::updateOrCreate(
              ['id' => $id],
              [
                'id_jenis_barang' => $request->jenisBarang,
                'nama' => $request->nama,
                'kondisi_penerimaan' => $request->kondisiPenerimaan,
                'tanggal_perolehan' => $request->tglPerolehan,
                'catatan' => $request->catatan,
              ]
            );

        } else {
          $barang = Barang::updateOrCreate(
            ['id' => $id],
            [
                'id_jenis_barang' => $request->jenisBarang,
                'nama' => $request->nama,
                'kondisi_penerimaan' => $request->kondisiPenerimaan,
                'tanggal_perolehan' => $request->tglPerolehan,
                'catatan' => $request->catatan,
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
