<?php

namespace App\Http\Controllers\admin\master\aset;

use App\Models\RefLantai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MasterLantaiController extends Controller
{
    public function index()
    {
        $title = 'Master Lantai';
        $lantai = RefLantai::orderBy('nama', 'asc')
        ->get();
    
        return view('admin.master.aset.lantai.index', compact('lantai', 'title'));
    }

    public function store(Request $request)
    {

        $id = $request->id;
    
        if ($id) {

            $lantai = RefLantai::updateOrCreate(
              ['id' => $id],
              [
                'nama' => $request->nama,
              ]
            );

        } else {
          $lantai = RefLantai::updateOrCreate(
            ['id' => $id],
            [
                'nama' => $request->nama,
              ]
          );
        }
        if ($lantai) {
          return response()->json('Created');
        } else {
          return response()->json('Failed Create');
        }
    }

    public function edit(string $id)
    {
        $where = ['id' => $id];

        $lantai = RefLantai::where($where)->first();

        return response()->json($lantai);
    }

    public function destroy(string $id)
    {
        $lantai = RefLantai::where('id', $id)->first();

        $lantai->delete();
    }
}
