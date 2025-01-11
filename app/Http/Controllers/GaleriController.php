<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Galeri';
        $data = Galeri::all();
    
        return view('admin.galeri.index', compact('data', 'title'));
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

        $fileDestination = public_path('assets/img/upload/foto_galeri');
    
        if ($id) {
            if($request->hasFile('foto')){
                $file = $request->file('foto');
                $cutter = time() . '-' . $file->getClientOriginalName();
                $fileName = str_replace(' ', '-', $cutter);


                $deleteFoto = Galeri::where('id', $id)->first();
                $destinationDelete = public_path('assets/img/upload/foto_galeri/' . $deleteFoto->foto);

                if (file_exists($destinationDelete)) {
                    unlink($destinationDelete);
                }
                
                $galeri = Galeri::updateOrCreate(
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
                $galeri = Galeri::updateOrCreate(
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
                
                $galeri = Galeri::updateOrCreate(
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
                $galeri = Galeri::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama' => $request->nama,
                        'deskripsi' => $request->deskripsi,
                        'published' => $request->status,
                        ]
                    );
            }
        }
        if ($galeri) {
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
        $galeri = Galeri::where('id', $id)->first();

        return response()->json($galeri);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $where = ['id' => $id];

        $galeri = Galeri::where($where)->first();

        return response()->json($galeri);
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
        $galeri = Galeri::where('id', $id)->first();

        $fileDestination = public_path('assets/img/upload/foto_galeri/' . $galeri->foto);

        if (file_exists($fileDestination)) {
            unlink($fileDestination);
        }

        $galeri->delete();
    }
}
