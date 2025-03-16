<?php

namespace App\Http\Controllers\admin\master\aset;

use App\Http\Controllers\Controller;
use App\Models\RefJenisBarang;
use Illuminate\Http\Request;

class MasterJenisBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Master Jenis Barang';
        $jenisBarang = RefJenisBarang::all();
    
        return view('admin.master.aset.jenis-barang.index', compact('jenisBarang', 'title'));
    }

    public function store(Request $request)
    {

        $id = $request->id;
    
        if ($id) {

            $jenisBarang = RefJenisBarang::updateOrCreate(
              ['id' => $id],
              [
                'kode' => $request->kode,
                'nama' => $request->nama,
              ]
            );

        } else {
          $jenisBarang = RefJenisBarang::updateOrCreate(
            ['id' => $id],
            [
                'kode' => $request->kode,
                'nama' => $request->nama,
              ]
          );
        }
        if ($jenisBarang) {
          return response()->json('Created');
        } else {
          return response()->json('Failed Create');
        }
    }

    public function edit(string $id)
    {
        $where = ['id' => $id];

        $jenisBarang = RefJenisBarang::where($where)->first();

        return response()->json($jenisBarang);
    }

    public function destroy(string $id)
    {
        $jenisBarang = RefJenisBarang::where('id', $id)->first();

        $jenisBarang->delete();
    }
}
