<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogAksesMobileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Akses Mobile';
        $aktifitas = ActivityLog::select([
            'activity_log.log_name AS logName',
            'activity_log.description',
            'activity_log.created_at AS timestamp',
            'santri_detail.nama'
        ])
        ->leftJoin('santri_detail', 'santri_detail.id', '=', 'activity_log.causer_id')
        ->orderBy('activity_log.created_at', 'desc')
        ->paginate(15);

        $onlineUsers = $aktifitas;

        return view('admin.log-akses.index', compact('aktifitas', 'title', 'onlineUsers'));
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

    public function reload(Request $request)
    {
        $title = 'Akses Mobile';
        $aktifitas = ActivityLog::select([
            'activity_log.log_name AS logName',
            'activity_log.description',
            'activity_log.created_at AS timestamp',
            'santri_detail.nama'
        ])
        ->leftJoin('santri_detail', 'santri_detail.id', '=', 'activity_log.causer_id')
        ->orderBy('activity_log.created_at', 'desc')
        ->get();

        return view('admin.log-akses.index', compact('aktifitas', 'title'));
    }
}
