<?php

namespace App\Http\Controllers;

use App\Models\Tanah;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TanahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Aset Tanah';
        $tanah = Tanah::all();
    
        return view('admin.aset.tanah.index', compact('tanah', 'title'));
    }

    public function store(Request $request)
    {

        $id = $request->id;

        $fileDestination = public_path('assets/img/upload/bukti_fisik_tanah');
    
        if ($id) {
            if($request->hasFile('buktiFisik')){
                $file = $request->file('buktiFisik');
                $cutter = time() . '-' . $file->getClientOriginalName();
                $fileName = str_replace(' ', '-', $cutter);
                
                $barang = Tanah::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'alamat' => $request->alamat,
                        'luas' => $request->luas,
                        'tanggal_perolehan' => $request->tglPerolehan,
                        'no_sertifikat' => $request->noSertifikat,
                        'status_tanah' => $request->statusTanah,
                        'keterangan' => $request->keterangan,
                        'bukti_fisik' => $fileName,
                        ]
                    );
                $file->move($fileDestination, $fileName);
            }else{
                $barang = Tanah::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'alamat' => $request->alamat,
                        'luas' => $request->luas,
                        'tanggal_perolehan' => $request->tglPerolehan,
                        'no_sertifikat' => $request->noSertifikat,
                        'status_tanah' => $request->statusTanah,
                        'keterangan' => $request->keterangan,
                        ]
                    );
            }
        } else {
            if($request->hasFile('buktiFisik')){
                $file = $request->file('buktiFisik');
                $cutter = time() . '-' . $file->getClientOriginalName();
                $fileName = str_replace(' ', '-', $cutter);
                
                $barang = Tanah::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'alamat' => $request->alamat,
                        'luas' => $request->luas,
                        'tanggal_perolehan' => $request->tglPerolehan,
                        'no_sertifikat' => $request->noSertifikat,
                        'status_tanah' => $request->statusTanah,
                        'keterangan' => $request->keterangan,
                        'bukti_fisik' => $fileName,
                        ]
                    );
                $file->move($fileDestination, $fileName);
            }else{
                $barang = Tanah::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'alamat' => $request->alamat,
                        'luas' => $request->luas,
                        'tanggal_perolehan' => $request->tglPerolehan,
                        'no_sertifikat' => $request->noSertifikat,
                        'status_tanah' => $request->statusTanah,
                        'keterangan' => $request->keterangan,
                        ]
                    );
            }
        }
        if ($barang) {
          return response()->json('Created');
        } else {
          return response()->json('Failed Create');
        }
    }

    public function show(string $id)
    {
        $barang = Tanah::where('id', $id)->first();

        if ($barang) {
            $barang->tanggal_perolehan = $barang->tanggal_perolehan ? Carbon::parse($barang->tanggal_perolehan)
                ->locale('id')
                ->translatedFormat('d F Y') : '';
        }

        return response()->json($barang);
    }

    public function edit(string $id)
    {
        $where = ['id' => $id];

        $barang = Tanah::where($where)->first();

        return response()->json($barang);
    }

    public function destroy(string $id)
    {
        $barang = Tanah::where('id', $id)->first();

        $barang->delete();
    }
}
