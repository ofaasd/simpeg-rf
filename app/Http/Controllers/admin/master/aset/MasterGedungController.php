<?php

namespace App\Http\Controllers\admin\master\aset;

use App\Models\RefGedung;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MasterGedungController extends Controller
{
    public function index()
    {
        $title = 'Master Gedung';
        $gedung = RefGedung::all();
    
        return view('admin.master.aset.gedung.index', compact('gedung', 'title'));
    }

    public function store(Request $request)
    {

        $id = $request->id;
    
        if ($id) {

            $gedung = RefGedung::updateOrCreate(
              ['id' => $id],
              [
                'kode' => $request->kode,
                'nama' => $request->nama,
              ]
            );

        } else {
          $gedung = RefGedung::updateOrCreate(
            ['id' => $id],
            [
                'kode' => $request->kode,
                'nama' => $request->nama,
              ]
          );
        }
        if ($gedung) {
          return response()->json('Created');
        } else {
          return response()->json('Failed Create');
        }
    }

    public function edit(string $id)
    {
        $where = ['id' => $id];

        $gedung = RefGedung::where($where)->first();

        return response()->json($gedung);
    }

    public function destroy(string $id)
    {
        $gedung = RefGedung::where('id', $id)->first();

        $gedung->delete();
    }
}
