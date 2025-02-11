<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class AboutPondokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Tentang Pondok';
        $dataTentang = About::first();

        return view('admin.tentang-pondok.index', compact('title', 'dataTentang'));
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
        // Ambil data berdasarkan idTentang, jika ada
        $dataAbout = About::where('id', $request->idTentang)->first();

        if(!$dataAbout){
            return response()->json([
                'message'   => 'data tidak ditemukan',
            ], 500);
        }

        $dataAbout->update([
            'tentang' => $request->tentang, 
            'visi'  => $request->visi, 
            'misi'  => $request->misi, 
            'alamat'    => $request->alamat, 
            'sekolah'   => $request->sekolah, 
            'nsm'   =>  $request->nsm, 
            'npsn'  => $request->npsn, 
            'nara_hubung' =>  $request->naraHubung,
        ]);

        return response()->json([
            'message'   => 'Data tentang Pondok berhasil diubah'
        ], 200);
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
        //
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
        //
    }
}
