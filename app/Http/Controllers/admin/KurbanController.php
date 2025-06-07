<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kurban;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class KurbanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'nama', 'jumlah', 'jenis', 'atas_nama', 'tanggal'];

    public function index(Request $request)
    {
        if (empty($request->input('length'))) {
            $santri = Santri::all();
            $title = 'Kurban';
            $indexed = $this->indexed;
            return view('admin.kurban.index', compact('title', 'indexed', 'santri'));
        } else {
            $columns = [
                1 => 'id',
                2 => 'id_santri',
                3 => 'jumlah',
                4 => 'jenis',
                5 => 'atas_nama',
                6 => 'tanggal'
            ];

            $search = $request->input('search.value');
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')] ?? 'id';
            $dir = $request->input('order.0.dir') ?? 'asc';

            $query = Kurban::with('santri');

            $totalData = $query->count();

            if (!empty($search)) {
                $query->whereHas('santri', function ($q) use ($search) {
                    $q->where('nama', 'LIKE', "%{$search}%")
                        ->orWhere('no_induk', 'LIKE', "%{$search}%");
                })->orWhere('atas_nama', 'LIKE', "%{$search}%");
            }

            $totalField = $query->count();

            $kurban = $query
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $data = [];
            $ids = $start;

            $jenisLabel = [
                1 => 'Sapi',
                2 => 'Kambing',
                3 => 'Domba',
                4 => 'Lainnya',
            ];

            foreach ($kurban as $row) {
                $nestedData['id'] = $row->id;
                $nestedData['fake_id'] = ++$ids;
                $nestedData['nama'] = $row->santri->nama ?? '-';
                $nestedData['jumlah'] = $row->jumlah;
                $nestedData['jenis'] = $jenisLabel[$row->jenis] ?? 'Unknown';  // pake mapping lengkap
                $nestedData['atas_nama'] = $row->atas_nama;
                $nestedData['tanggal'] = $row->tanggal;
                $data[] = $nestedData;
            }

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalData,
                'recordsFiltered' => $totalField,
                'code' => 200,
                'data' => $data,
            ]);
        }
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

        $request->validate([
            'id_santri' => 'required|exists:santri,id',
            'jumlah' => 'required|integer|min:1',
            'jenis' => 'required|integer|in:1,2,3,4',
            'atas_nama' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal' => 'required|date'
        ]);

        try {
            if ($id) {
                $kurban = Kurban::find($id);
                if (!$kurban) {
                    return response()->json(['error' => 'Data tidak ditemukan'], 404);
                }

                $kurban->update([
                    'id_santri' => $request->id_santri,
                    'jumlah' => $request->jumlah,
                    'jenis' => $request->jenis,
                    'atas_nama' => $request->atas_nama,
                    'tanggal' => $request->tanggal
                ]);

                Log::info("Kurban updated", ['id' => $kurban->id, 'user' => auth()->user()->id ?? 'guest']);
            } else {
                $kurban = Kurban::create([
                    'id_santri' => $request->id_santri,
                    'jumlah' => $request->jumlah,
                    'jenis' => $request->jenis,
                    'atas_nama' => $request->atas_nama,
                    'tanggal' => $request->tanggal
                ]);

                Log::info("Kurban created", ['id' => $kurban->id, 'user' => auth()->user()->id ?? 'guest']);
            }

            // Handle upload foto jika ada
            if ($request->hasFile('foto')) {
                $photo = $request->file('foto');

                // Nama file acak dengan ekstensi asli
                $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();

                // Kompres dan simpan
                $image = Image::make($photo)
                    ->resize(400, 400)
                    ->save(public_path('assets/img/upload/kurban/' . $filename));

                if ($image) {
                    $kurban->foto = $filename;
                    $kurban->save();

                    Log::info("Foto uploaded for Kurban", ['id' => $kurban->id, 'filename' => $filename]);
                }
            }

            return response()->json($kurban);
        } catch (\Exception $e) {
            Log::error("Error in Kurban store: " . $e->getMessage(), [
                'request' => $request->all(),
                'user' => auth()->user()->id ?? 'guest'
            ]);
            return response()->json(['error' => 'Failed to save data'], 500);
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
        $kurban = Kurban::findOrFail($id);

        return response()->json($kurban);
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
        $kurban = Kurban::where('id', $id)->delete();
    }
}
