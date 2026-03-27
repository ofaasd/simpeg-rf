<?php

namespace App\Http\Controllers\ustadz;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Kamar;
use App\Models\Santri;
use App\Models\Perilaku;
use App\Models\EmployeeNew;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PerilakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $labelPerilaku = ['Kurang Baik', 'Cukup', 'Baik'];
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

        $labelPerilaku = $this->labelPerilaku;
        $perilaku = Perilaku::whereIn('no_induk', $var['listSantri']->pluck('no_induk'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('no_induk')
            ->map(function ($group) use ($labelPerilaku) {
                $item = $group->first(); // Ambil data terbaru

                // Konversi angka ke labelPerilaku
                $item->tanggal = Carbon::parse($item->tanggal)->format('Y-m-d') ?? '-';

                $item->ketertiban = $labelPerilaku[$item->ketertiban] ?? '-';
                $item->kebersihan = $labelPerilaku[$item->kebersihan] ?? '-';
                $item->kedisiplinan = $labelPerilaku[$item->kedisiplinan] ?? '-';
                $item->kerapian = $labelPerilaku[$item->kerapian] ?? '-';
                $item->kesopanan = $labelPerilaku[$item->kesopanan] ?? '-';
                $item->kepekaan_lingkungan = $labelPerilaku[$item->kepekaan_lingkungan] ?? '-';
                $item->ketaatan_peraturan = $labelPerilaku[$item->ketaatan_peraturan] ?? '-';

                return $item;
            });

        $var['perilaku'] = $perilaku;

        return view('ustadz.murroby.index-perilaku', compact('title', 'var', 'id'));
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
