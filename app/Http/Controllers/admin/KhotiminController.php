<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Khotimin;
use App\Models\Santri;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KhotiminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Data Khotimin';

        // Jika request AJAX meminta data tabel (DataTables server-side / JSON)
        if ($request->ajax() && $request->has('draw')) {
            $columns = [
                0 => 'id',
                1 => 'id',
                2 => 'kode_santri',
                3 => 'santri.nama',
                4 => 'kelas',
                5 => 'id_tahun_ajaran',
                6 => 'tanggal_khatam',
                7 => 'keterangan',
                8 => 'id',
            ];

            $search = $request->input('search.value');
            $limit = $request->input('length', 10);
            $start = $request->input('start', 0);
            $orderColIdx = $request->input('order.0.column', 6);
            $order = $columns[$orderColIdx] ?? 'tanggal_khatam';
            $dir = $request->input('order.0.dir', 'desc');

            $query = Khotimin::with(['santri', 'tahunAjaran']);

            // Filter Kelas jika ada
            if ($request->filled('filter_kelas')) {
                $query->where('kelas', $request->filter_kelas);
            }

            // Filter Tahun Ajaran jika ada
            if ($request->filled('filter_tahun_ajaran')) {
                $query->where('id_tahun_ajaran', $request->filter_tahun_ajaran);
            }

            $totalData = $query->count();

            // Search
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_santri', 'LIKE', "%{$search}%")
                        ->orWhere('kelas', 'LIKE', "%{$search}%")
                        ->orWhere('keterangan', 'LIKE', "%{$search}%")
                        ->orWhereHas('santri', function ($sq) use ($search) {
                            $sq->where('nama', 'LIKE', "%{$search}%")
                                ->orWhere('no_induk', 'LIKE', "%{$search}%");
                        });
                });
            }

            $totalFiltered = $query->count();

            // Sorting handler jika relasi
            if ($order === 'santri.nama') {
                $query->join('santri_detail', 'santri_detail.id', '=', 'tbl_khotimin.santri_id')
                    ->orderBy('santri_detail.nama', $dir)
                    ->select('tbl_khotimin.*');
            } else {
                $query->orderBy($order, $dir);
            }

            $list = $query->offset($start)->limit($limit)->get();

            $data = [];
            $no = $start;
            foreach ($list as $row) {
                $no++;
                $tahunStr = $row->tahunAjaran 
                    ? ($row->tahunAjaran->awal . '/' . $row->tahunAjaran->akhir . ' (Smst ' . $row->tahunAjaran->jenis . ')')
                    : '-';

                $data[] = [
                    'no' => $no,
                    'id' => $row->id,
                    'kode_santri' => $row->kode_santri ?? ($row->santri->no_induk ?? '-'),
                    'nama_santri' => $row->santri->nama ?? '-',
                    'kelas' => strtoupper($row->kelas),
                    'tahun_ajaran' => $tahunStr,
                    'tanggal_khatam' => $row->tanggal_khatam ? $row->tanggal_khatam->format('d/m/Y') : '-',
                    'tanggal_khatam_raw' => $row->tanggal_khatam ? $row->tanggal_khatam->format('Y-m-d') : '',
                    'keterangan' => $row->keterangan ?? '-',
                    'santri_id' => $row->santri_id,
                    'id_tahun_ajaran' => $row->id_tahun_ajaran,
                ];
            }

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $totalData,
                'recordsFiltered' => $totalFiltered,
                'data' => $data,
            ]);
        }

        // Data untuk halaman web biasa
        $dataKhotimin = Khotimin::with(['santri', 'tahunAjaran'])->orderBy('tanggal_khatam', 'desc')->get();
        $santriList = Santri::select('id', 'no_induk', 'nama', 'kelas', 'jenis_kelamin')
            ->orderBy('nama', 'asc')
            ->get();
        $tahunAjaranList = TahunAjaran::orderBy('id', 'desc')->get();
        $kelasList = Kelas::orderBy('code', 'asc')->get();

        // Statistik ringkas
        $totalKhotimin = Khotimin::count();
        $activeTa = TahunAjaran::where('is_aktif', 1)->first();
        $khotiminTahunIni = $activeTa ? Khotimin::where('id_tahun_ajaran', $activeTa->id)->count() : 0;

        return view('admin.khotimin.index', compact(
            'title',
            'dataKhotimin',
            'santriList',
            'tahunAjaranList',
            'kelasList',
            'totalKhotimin',
            'activeTa',
            'khotiminTahunIni'
        ));
    }

    /**
     * Store a newly created or updated resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santri_detail,id',
            'id_tahun_ajaran' => 'required|exists:ref_tahun_ajaran,id',
            'tanggal_khatam' => 'required|date',
            'kelas' => 'required|string|max:10',
            'keterangan' => 'nullable|string',
        ], [
            'santri_id.required' => 'Santri wajib dipilih.',
            'id_tahun_ajaran.required' => 'Tahun ajaran wajib dipilih.',
            'tanggal_khatam.required' => 'Tanggal khatam wajib diisi.',
            'kelas.required' => 'Kelas wajib diisi.',
        ]);

        try {
            $santri = Santri::findOrFail($request->santri_id);
            $kodeSantri = (string) $santri->no_induk;

            $payload = [
                'santri_id' => $santri->id,
                'kode_santri' => $kodeSantri,
                'id_tahun_ajaran' => $request->id_tahun_ajaran,
                'tanggal_khatam' => $request->tanggal_khatam,
                'kelas' => strtoupper(trim($request->kelas)),
                'keterangan' => $request->keterangan,
            ];

            if ($request->filled('id')) {
                $khotimin = Khotimin::findOrFail($request->id);
                $khotimin->update($payload);
                $message = 'Data khotimin berhasil diperbarui.';
            } else {
                $khotimin = Khotimin::create($payload);
                $message = 'Data khotimin berhasil ditambahkan.';
            }

            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => $message,
                'data' => $khotimin->load(['santri', 'tahunAjaran']),
            ]);
        } catch (\Exception $e) {
            Log::error('KhotiminController@store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $khotimin = Khotimin::with(['santri', 'tahunAjaran'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $khotimin->id,
                    'santri_id' => $khotimin->santri_id,
                    'kode_santri' => $khotimin->kode_santri,
                    'nama_santri' => $khotimin->santri->nama ?? '',
                    'id_tahun_ajaran' => $khotimin->id_tahun_ajaran,
                    'tanggal_khatam' => $khotimin->tanggal_khatam ? $khotimin->tanggal_khatam->format('Y-m-d') : '',
                    'kelas' => strtoupper($khotimin->kelas),
                    'keterangan' => $khotimin->keterangan,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->merge(['id' => $id]);
        return $this->store($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $khotimin = Khotimin::findOrFail($id);
            $khotimin->delete();

            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Data khotimin berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            Log::error('KhotiminController@destroy error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
