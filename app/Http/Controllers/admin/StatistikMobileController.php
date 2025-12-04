<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Santri;
use App\Models\ActivityLog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Pagination\LengthAwarePaginator;


class StatistikMobileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Statistik Mobile';

        $santriOnline = ActivityLog::select([
            'santri_detail.nama',
            DB::raw('count(*) as jumlahLogin')
        ])
        ->leftJoin('santri_detail', 'santri_detail.id', '=', 'activity_log.causer_id')
        ->where('activity_log.description', 'Login')
        ->where('activity_log.causer_id', '!=', 958)
        ->groupBy('santri_detail.nama')
        ->orderBy('santri_detail.nama', 'asc')
        ->get();
      
        $pegawaiOnline = ActivityLog::select([
            'employee_new.nama',
            DB::raw('count(*) as jumlahLogin')
        ])
        ->leftJoin('users', 'users.id', '=', 'activity_log.causer_id')
        ->leftJoin('employee_new', 'employee_new.id', '=', 'users.pegawai_id')
        ->where('activity_log.description', 'Login')
        ->where('activity_log.causer_id', '!=', 958)
        ->groupBy('employee_new.nama')
        ->orderBy('employee_new.nama', 'asc')
        ->get();

        $dataUserLogIn = $santriOnline->concat($pegawaiOnline);

        $loggedInCauserIds = ActivityLog::where('description', 'Login')
            ->where('activity_log.causer_id', '!=', 958)
            ->pluck('causer_id')
            ->unique()
            ->toArray();

        $userBelumLogin = User::select('employee_new.nama')
            ->leftJoin('employee_new', 'employee_new.id', '=', 'users.pegawai_id')
            ->whereNotIn('users.id', $loggedInCauserIds)
            ->orderBy('employee_new.nama', 'asc')
            ->get();

        $santriBelumLogin = Santri::select('santri_detail.nama')
            ->whereNotIn('id', $loggedInCauserIds)
            ->orderBy('santri_detail.nama', 'asc')
            ->get();

        $dataUserBelumLogin = $userBelumLogin->concat($santriBelumLogin);
        $jmlUserBelumLogin = $dataUserBelumLogin->count();

        $userSudahLogin = User::select('employee_new.nama')
            ->leftJoin('employee_new', 'employee_new.id', '=', 'users.pegawai_id')
            ->whereIn('users.id', $loggedInCauserIds)
            ->get();

        $santriSudahLogin = Santri::select('santri_detail.nama')
            ->whereIn('id', $loggedInCauserIds)
            ->get();

        $sudahLogin = $userSudahLogin->concat($santriSudahLogin);
        $jmlUserSudahLogin = $sudahLogin->count();

        return view('admin.statistik-mobile.index', compact('title', 'santriOnline', 'pegawaiOnline', 'userBelumLogin', 'santriBelumLogin', 'jmlUserBelumLogin', 'dataUserLogIn', 'jmlUserSudahLogin', 'dataUserBelumLogin'));
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
