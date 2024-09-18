<?php

namespace App\Http\Controllers\admin\master\aset;

use App\Http\Controllers\Controller;
use App\Models\RefJenisRuang;
use Illuminate\Http\Request;

class MasterJenisRuangController extends Controller
{
    public function index()
    {
        $title = 'Master Jenis Ruang';
        $jenisRuang = RefJenisRuang::all();
    
        return view('admin.master.aset.jenis-ruang.index', compact('jenisRuang', 'title'));
    }

    public function store(Request $request)
    {

        $id = $request->id;
    
        if ($id) {

            $jenisRuang = RefJenisRuang::updateOrCreate(
              ['id' => $id],
              [
                'nama' => $request->nama,
              ]
            );

        } else {
          $jenisRuang = RefJenisRuang::updateOrCreate(
            ['id' => $id],
            [
                'nama' => $request->nama,
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

        $jenisRuang = RefJenisRuang::where($where)->first();

        return response()->json($jenisRuang);
    }

    public function destroy(string $id)
    {
        $jenisRuang = RefJenisRuang::where('id', $id)->first();

        $jenisRuang->delete();
    }
}
