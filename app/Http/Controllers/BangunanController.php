<?php

namespace App\Http\Controllers;

use App\Models\Bangunan;
use App\Models\RefGedung;
use App\Models\RefLantai;
use App\Models\Tanah;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BangunanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Aset Bangunan';
        $bangunan = Bangunan::select('aset_bangunan.*', 'ref_gedung.nama as gedung', 'aset_tanah.nama as tanah', 'ref_lantai.nama as lantai')
        ->leftJoin('aset_tanah', 'aset_tanah.id', '=', 'aset_bangunan.id_tanah')
        ->leftJoin('ref_gedung', 'ref_gedung.id', '=', 'aset_bangunan.id_gedung')
        ->leftJoin('ref_lantai', 'ref_lantai.id', '=', 'aset_bangunan.id_lantai')
        ->get();

        $gedung = RefGedung::all();
        $lantai = RefLantai::all();
        $tanah = Tanah::all();
    
        return view('admin.aset.bangunan.index', compact('bangunan', 'gedung', 'tanah', 'lantai', 'title'));
    }

    public function store(Request $request)
    {

        $id = $request->id;
    
        if ($id) {

            $ruang = Bangunan::updateOrCreate(
              ['id' => $id],
              [
                'nama' => $request->nama,
                'id_gedung' => $request->gedung,
                'id_lantai' => $request->lantai,
                'id_tanah' => $request->tanah,
                'luas' => $request->luas,
                'status' => $request->status,
                'kondisi' => $request->kondisi,
                'tanggal_pembangunan' => $request->tglPembangunan,
                // 'bukti_fisik' => $request->buktiFisik,
              ]
            );

        } else {
          $ruang = Bangunan::updateOrCreate(
            ['id' => $id],
            [
                'nama' => $request->nama,
                'id_gedung' => $request->gedung,
                'id_lantai' => $request->lantai,
                'id_tanah' => $request->tanah,
                'luas' => $request->luas,
                'status' => $request->status,
                'kondisi' => $request->kondisi,
                'tanggal_pembangunan' => $request->tglPembangunan,
                // 'bukti_fisik' => $request->buktiFisik,
            ]
          );
        }
        if ($ruang) {
          return response()->json('Created');
        } else {
          return response()->json('Failed Create');
        }
    }

    public function show(string $id)
    {
        $bangunan = Bangunan::select('aset_bangunan.*', 'ref_gedung.nama as gedung', 'ref_lantai.nama as lantai', 'aset_tanah.nama as tanah')
        ->leftJoin('ref_gedung', 'ref_gedung.id', '=', 'aset_bangunan.id_gedung')
        ->leftJoin('ref_lantai', 'ref_lantai.id', '=', 'aset_bangunan.id_lantai')
        ->leftJoin('aset_tanah', 'aset_tanah.id', '=', 'aset_bangunan.id_tanah')
        ->where('aset_bangunan.id', $id)
        ->first();

        if ($bangunan) {
            $bangunan->tanggal_pembangunan = $bangunan->tanggal_pembangunan ? Carbon::parse($bangunan->tanggal_pembangunan)
                ->locale('id')
                ->translatedFormat('d F Y') : '';
            $bangunan->kondisi = $bangunan->kondisi ? ucwords(str_replace('-', ' ', $bangunan->kondisi)) : '';
            $bangunan->status = $bangunan->status ? strtoupper($bangunan->kondisi) : '';
        }

        return response()->json($bangunan);
    }

    public function edit(string $id)
    {
        $where = ['id' => $id];

        $bangunan = Bangunan::where($where)->first();

        return response()->json($bangunan);
    }

    public function destroy(string $id)
    {
        $bangunan = Bangunan::where('id', $id)->first();

        $bangunan->delete();
    }
}
