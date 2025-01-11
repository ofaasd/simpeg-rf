<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;

use Carbon\Carbon;

class FasilitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Fasilitas';
        $data = Fasilitas::all();
    
        return view('admin.fasilitas.index', compact('data', 'title'));
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

        $fileDestination = public_path('assets/img/upload/foto_fasilitas');
    
        if ($id) {
            if($request->hasFile('foto')){
                $file = $request->file('foto');
                $cutter = time() . '-' . $file->getClientOriginalName();
                $fileName = str_replace(' ', '-', $cutter);
                
                $deleteFoto = Fasilitas::where('id', $id)->first();
                $destinationDelete = public_path('assets/img/upload/foto_fasilitas/' . $deleteFoto->foto);

                if (file_exists($destinationDelete)) {
                    unlink($destinationDelete);
                }

                $fasilitas = Fasilitas::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'deskripsi' => $request->deskripsi,
                        'published' => $request->status,
                        'foto' => $fileName,
                        ]
                    );
                $file->move($fileDestination, $fileName);
            }else{
                $fasilitas = Fasilitas::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'deskripsi' => $request->deskripsi,
                        'published' => $request->status,
                        ]
                    );
            }
        } else {
            if($request->hasFile('foto')){
                $file = $request->file('foto');
                $cutter = time() . '-' . $file->getClientOriginalName();
                $fileName = str_replace(' ', '-', $cutter);
                
                $fasilitas = Fasilitas::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'deskripsi' => $request->deskripsi,
                        'published' => $request->status,
                        'foto' => $fileName,
                        ]
                    );
                $file->move($fileDestination, $fileName);
            }else{
                $fasilitas = Fasilitas::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'deskripsi' => $request->deskripsi,
                        'published' => $request->status,
                        ]
                    );
            }
        }
        if ($fasilitas) {
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
        $fasilitas = Fasilitas::where('id', $id)->first();

        return response()->json($fasilitas);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $where = ['id' => $id];

        $fasilitas = Fasilitas::where($where)->first();

        return response()->json($fasilitas);
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
        $fasilitas = Fasilitas::where('id', $id)->first();

        $fileDestination = public_path('assets/img/upload/foto_fasilitas/' . $fasilitas->foto);

        if (file_exists($fileDestination)) {
            unlink($fileDestination);
        }

        $fasilitas->delete();
    }
}
