<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\RefBank;
use App\Models\Rekening;
use App\Models\Tutorial;
use Illuminate\Http\Request;

class RekeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Rekening';
        $data = Rekening::select([
                'rekening.id',
                'rekening.no AS noRek',
                'rekening.atas_nama AS atasNama',
                'ref_bank.nama AS namaBank'
            ])
            ->leftJoin('ref_bank', 'ref_bank.kode', '=', 'rekening.kode_bank')
            ->latest()
            ->get();
        $refBank = RefBank::all();
        $tutorial = Tutorial::where('jenis', 'pembayaran')->first();
        
        return view('admin.rekening.index', compact('data', 'title', 'refBank', 'tutorial'));
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

        if (!empty($id)) {
            $data = Rekening::updateOrCreate(
                ['id' => $id],
                [
                    'kode_bank' => $request->kodeBank,
                    'no' => $request->no,
                    'atas_nama' => $request->atasNama,
                ]
            );
            $messageResponse = "Merubah Rekening";
        } else {

            $data = Rekening::updateOrCreate(
                ['id' => $id],
                [
                    'kode_bank' => $request->kodeBank,
                    'no' => $request->no,
                    'atas_nama' => $request->atasNama,
                ]
            );
            $messageResponse = "Membuat Rekening";
        }

        if ($data) {
            return response()->json($messageResponse);
        } else {
            return response()->json('Failed Create');
        }
    }

    public function storeTutorial(Request $request)
    {
        $data = Tutorial::where('id', $request->idJenisTutorial)->first();
        $data->update([
            'teks'  => $request->teks
        ]);

        if ($data) {
            return response()->json('Berhasil mengupdate data.');
        } else {
            return response()->json('Gagal mengupdate data.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $where = ['id' => $id];

        $data = Rekening::where($where)->first();

        return response()->json($data);
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
        $data = Rekening::where('id', $id)->first();
        $data->delete();
    }
}
