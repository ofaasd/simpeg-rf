<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Elektronik;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AsetElektronikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $id = $request->id;
    
        if ($id) {

            $elektronik = Elektronik::updateOrCreate(
              ['id' => $id],
              [
                'id_ruang' => $request->ruang,
                'nama' => $request->nama, 
                'kondisi_penerimaan' => $request->kondisiPenerimaan, 
                'tanggal_perolehan' => $request->tglPerolehan, 
                'garansi' => $request->garansi, 
                'spesifikasi' => $request->spesifikasi, 
                'serial_number' => $request->serialNumber, 
                'last_checking' => $request->lastChecking, 
                'catatan' => $request->catatan, 
                'status' => $request->status
              ]
            );

        } else {
          $elektronik = Elektronik::updateOrCreate(
            ['id' => $id],
            [
                'id_ruang' => $request->ruang,
                'nama' => $request->nama, 
                'kondisi_penerimaan' => $request->kondisiPenerimaan, 
                'tanggal_perolehan' => $request->tglPerolehan, 
                'garansi' => $request->garansi, 
                'spesifikasi' => $request->spesifikasi, 
                'serial_number' => $request->serialNumber, 
                'last_checking' => $request->lastChecking, 
                'catatan' => $request->catatan, 
                'status' => $request->status
            ]
          );
        }
        if ($elektronik) {
          return response()->json('Created');
        } else {
          return response()->json('Failed Create');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      $elektronik = Elektronik::select('aset_elektronik.*', 'aset_ruang.kode_ruang as ruang')
      ->leftJoin('aset_ruang', 'aset_ruang.id', '=', 'aset_elektronik.id_ruang')
      ->where('aset_elektronik.id', $id)
      ->first();

      if ($elektronik) {
          $elektronik->tanggal_perolehan ? $elektronik->tanggal_perolehan = Carbon::parse($elektronik->tanggal_perolehan)
              ->locale('id')
              ->translatedFormat('d F Y') : null;

          $elektronik->last_checking = Carbon::parse($elektronik->last_checking)
              ->locale('id')
              ->translatedFormat('d F Y');
      }

      return response()->json($elektronik);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $where = ['id' => $id];

        $elektronik = Elektronik::where($where)->first();

        return response()->json($elektronik);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $elektronik = Elektronik::where('id', $id)->first();

        $elektronik->delete();
    }
}
