<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\Santri;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Prestasi';
        $dataPrestasi = Prestasi::select(
            'prestasi.*', 
            'santri_detail.nama AS namaSantri',
            )
        ->leftJoin('santri_detail', 'santri_detail.no_induk', '=', 'prestasi.no_induk')
        ->get();

        $jenisOptions = [
            'olah-raga' => 'Olah raga',
            'seni-ketrampilan' => 'Seni / Ketrampilan',
            'science' => 'Science',
            'agama' => 'Agama',
            'matematik' => 'Matematik',
            'sosial' => 'Sosial',
            'umum' => 'Umum'
        ];

        $prestasiOptions = [
            'juara-i' => 'Juara I',
            'juara-ii' => 'Juara II',
            'juara-iii' => 'Juara III',
            'juara-umum' => 'Juara Umum',
            'juara-favorit' => 'Juara Favorit',
            'juara-harapan-i' => 'Juara Harapan I',
            'juara-harapan-ii' => 'Juara Harapan II'
        ];
        
        // Mapping tingkat
        $tingkatOptions = [
            'kelurahan' => 'Kelurahan',
            'kecamatan' => 'Kecamatan',
            'kota' => 'Kota',
            'provinsi' => 'Provinsi',
            'nasional' => 'Nasional',
            'internasional' => 'Internasional'
        ];

        $dataPrestasi->transform(function ($item) use ($jenisOptions, $prestasiOptions, $tingkatOptions) {
            $item->jenis_text = $jenisOptions[$item->jenis] ?? $item->jenis;
            $item->prestasi_text = $prestasiOptions[$item->prestasi] ?? $item->prestasi;
            $item->tingkat_text = $tingkatOptions[$item->tingkat] ?? $item->tingkat;
            return $item;
        });

        $dataSantri = Santri::all();

        return view('admin.prestasi.index', compact('dataPrestasi', 'title', 'dataSantri'));
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

        $fileDestination = public_path('assets/img/upload/foto_prestasi');
    
        if ($id) {
            if($request->hasFile('foto')){
                $file = $request->file('foto');
                $cutter = time() . '-' . $file->getClientOriginalName();
                $fileName = str_replace(' ', '-', $cutter);


                $deleteFoto = Prestasi::where('id', $id)->first();
                $destinationDelete = public_path('assets/img/upload/foto_prestasi/' . $deleteFoto->foto);

                if (file_exists($destinationDelete)) {
                    unlink($destinationDelete);
                }
                
                $prestasi = Prestasi::updateOrCreate(
                    ['id' => $id],
                    [
                        'no_induk' => $request->noInduk,
                        'deskripsi' => $request->deskripsi,
                        'jenis' => $request->jenis,
                        'prestasi' => $request->prestasi,
                        'tingkat' => $request->tingkat,
                        'foto' => $fileName,
                    ]
                    );
                $file->move($fileDestination, $fileName);
            }else{
                $prestasi = Prestasi::updateOrCreate(
                    ['id' => $id],
                    [
                        'no_induk' => $request->noInduk,
                        'deskripsi' => $request->deskripsi,
                        'jenis' => $request->jenis,
                        'prestasi' => $request->prestasi,
                        'tingkat' => $request->tingkat,
                    ]
                    );
            }
        } else {
            if($request->hasFile('foto')){
                $file = $request->file('foto');
                $cutter = time() . '-' . $file->getClientOriginalName();
                $fileName = str_replace(' ', '-', $cutter);
                
                $prestasi = Prestasi::updateOrCreate(
                    ['id' => $id],
                    [
                        'no_induk' => $request->noInduk,
                        'deskripsi' => $request->deskripsi,
                        'jenis' => $request->jenis,
                        'prestasi' => $request->prestasi,
                        'tingkat' => $request->tingkat,
                        'foto' => $fileName,
                    ]
                    );
                $file->move($fileDestination, $fileName);
            }else{
                $prestasi = Prestasi::updateOrCreate(
                    ['id' => $id],
                    [
                        'no_induk' => $request->noInduk,
                        'deskripsi' => $request->deskripsi,
                        'jenis' => $request->jenis,
                        'prestasi' => $request->prestasi,
                        'tingkat' => $request->tingkat,
                    ]
                    );
            }
        }
        if ($prestasi) {
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
        $prestasi = Prestasi::where('id', $id)->first();

        return response()->json($prestasi);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $where = ['id' => $id];

        $prestasi = Prestasi::where($where)->first();

        return response()->json($prestasi);
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
        $prestasi = Prestasi::where('id', $id)->first();

        $fileDestination = public_path('assets/img/upload/foto_prestasi/' . $prestasi->foto);

        if (file_exists($fileDestination)) {
            unlink($fileDestination);
        }

        $prestasi->delete();
    }
}
