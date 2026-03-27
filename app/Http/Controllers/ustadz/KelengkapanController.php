<?php

namespace App\Http\Controllers\ustadz;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Kamar;
use App\Models\Santri;
use App\Models\EmployeeNew;
use App\Models\Kelengkapan;

use Carbon\Carbon;

class KelengkapanController extends Controller
{
    protected $labelKelengkapan = ['Tidak Lengkap', 'Lengkap & Kurang baik', 'lengkap & baik'];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id_user = Auth::user()->id;
        $user = User::find($id_user);
        $id = $user->pegawai_id;
        $where = ['id' => $id];
        $var['EmployeeNew'] = EmployeeNew::where($where)->first();
        $title = 'Pegawai';

        $kamar = Kamar::where('employee_id', $id)->first();
        $var['listSantri'] = Santri::where('kamar_id', $kamar->id)
        ->orderBy('nama', 'asc')
        ->get();

        $labelKelengkapan = $this->labelKelengkapan;
        $kelengkapan = Kelengkapan::whereIn('no_induk', $var['listSantri']->pluck('no_induk'))
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('no_induk')
        ->map(function ($group) use ($labelKelengkapan) {
            $item = $group->first(); // Ambil data terbaru

            // Konversi angka ke labelKelengkapan
            $item->tanggal = Carbon::parse($item->tanggal)->format('Y-m-d') ?? '-';

            $item->perlengkapan_mandi = $labelKelengkapan[$item->perlengkapan_mandi] ?? '-';
            $item->peralatan_sekolah = $labelKelengkapan[$item->peralatan_sekolah] ?? '-';
            $item->perlengkapan_diri = $labelKelengkapan[$item->perlengkapan_diri] ?? '-';

            return $item;
        });

        $var['kelengkapan'] = $kelengkapan;

        return view('ustadz.murroby.index-kelengkapan', compact('title', 'var', 'id'));
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
        //
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
