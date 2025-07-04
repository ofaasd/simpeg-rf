<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Kelas;
use App\Models\RawatInap;
use App\Models\Santri;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RawatInapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $var['list_bulan'] = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];
        $santri = Santri::all();

        $title = 'Kesehatan Santri';
        $bulan = date('m');
        $tahun = date('Y');
        if (!empty($request->bulan)) {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
        }
        $var['bulan'] = $bulan;
        $var['tahun'] = $tahun;

        $rawatInap = RawatInap::select(
            'rawat_inap.*',
            'santri_detail.nama AS namaSantri',
            'employee_new.nama AS namaMurroby',
            'santri_detail.kelas',
            'santri_kamar.id AS idKamar',
            'ref_kamar.id AS idRefKamar',
            'employee_new.id AS idEmployee',
        )
            ->leftJoin('santri_detail', function ($join) {
                $join->on(DB::raw('CAST(santri_detail.no_induk AS CHAR)'), '=', DB::raw('CAST(rawat_inap.santri_no_induk AS CHAR)'));
            })
            ->leftJoin('santri_kamar', 'santri_kamar.id', '=', 'santri_detail.kamar_id')
            ->leftJoin('ref_kamar', 'ref_kamar.id', '=', 'santri_kamar.kamar_id')
            ->leftJoin('employee_new', 'employee_new.id', '=', 'ref_kamar.employee_id')
            ->whereRaw('MONTH(FROM_UNIXTIME(tanggal_masuk)) = ' . $bulan)
            ->whereRaw('YEAR(FROM_UNIXTIME(tanggal_masuk)) = ' . $tahun)
            ->get();

        return view('admin.rawat-inap.index', compact('santri', 'title', 'rawatInap', 'var'));
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

        $validator = Validator::make($request->all(), [
            'keluhan' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        if ($id) {

            $rawatInap = RawatInap::updateOrCreate(
                ['id' => $id],
                [
                    'santri_no_induk' => $request->santri_id,
                    // 'murroby_id' => $request->murroby_id,
                    'tanggal_masuk' => strtotime($request->tanggal_masuk),
                    // 'kelas_id' => $request->kelas_id,
                    'keluhan' => $request->keluhan,
                    'terapi' => $request->terapi,
                    'tanggal_keluar' => strtotime($request->tanggal_keluar),
                ]
            );

            return response()->json('Updated');
        } else {
            $rawatInap = RawatInap::updateOrCreate(
                ['id' => $id],
                [
                    'santri_no_induk' => $request->santri_id,
                    // 'murroby_id' => $request->murroby_id,
                    'tanggal_masuk' => strtotime($request->tanggal_masuk),
                    // 'kelas_id' => $request->kelas_id,
                    'keluhan' => $request->keluhan,
                    'terapi' => $request->terapi,
                    'tanggal_keluar' => strtotime($request->tanggal_keluar),
                ]
            );
            if ($rawatInap) {
                return response()->json('Created');
            } else {
                return response()->json('Failed Create');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function reload(Request $request)
    {
        $var['list_bulan'] = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];
        $santri = Santri::all();

        $title = 'Kesehatan Santri';
        $bulan = date('m');
        $tahun = date('Y');
        if (!empty($request->bulan)) {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
        }
        $var['bulan'] = $bulan;
        $var['tahun'] = $tahun;
        $rawatInap = RawatInap::select(
            'rawat_inap.*',
            'santri_detail.nama AS namaSantri',
            'employee_new.nama AS namaMurroby',
            'santri_detail.kelas',
            'santri_kamar.id AS idKamar',
            'ref_kamar.id AS idRefKamar',
            'employee_new.id AS idEmployee',
        )
            ->leftJoin('santri_detail', function ($join) {
                $join->on(DB::raw('CAST(santri_detail.no_induk AS CHAR)'), '=', DB::raw('CAST(rawat_inap.santri_no_induk AS CHAR)'));
            })
            ->leftJoin('santri_kamar', 'santri_kamar.id', '=', 'santri_detail.kamar_id')
            ->leftJoin('ref_kamar', 'ref_kamar.id', '=', 'santri_kamar.kamar_id')
            ->leftJoin('employee_new', 'employee_new.id', '=', 'ref_kamar.employee_id')
            ->whereRaw('MONTH(FROM_UNIXTIME(tanggal_masuk)) = ' . $bulan)
            ->whereRaw('YEAR(FROM_UNIXTIME(tanggal_masuk)) = ' . $tahun)
            ->get();

        return view('admin.rawat-inap.table', compact('santri', 'title', 'rawatInap', 'var'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $where = ['id' => $id];

        $kesehatan = RawatInap::where($where)->first();
        return response()->json($kesehatan);
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
        $rawatInap = RawatInap::where('id', $id)->delete();

        if ($rawatInap) {
            return response()->json([
                'message'   => "Data berhasil dihapus",
            ], 200);
        } else {
            return response()->json([
                'message'   => "Terjadi kesalahan pada saat menghapus",
            ], 500);
        }
    }
}
