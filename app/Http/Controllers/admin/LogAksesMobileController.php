<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Santri;
use App\Models\User;
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
      'santri_detail.nama',
    ])
    ->leftJoin('santri_detail', 'santri_detail.id', '=', 'activity_log.causer_id')
    ->orderBy('activity_log.created_at', 'desc')
    ->whereNot('activity_log.causer_id', 958)
    ->paginate(15);

    $threshold = now()->subMinutes(5); // ambil data yang online dalam 5 menit terakhir

    $usersOnline = User::where('last_seen', '>=', $threshold)
      ->select(['employee_new.nama', 'users.last_seen'])
      ->leftJoin('employee_new', 'employee_new.id', 'users.pegawai_id')
      ->get();

    $santriOnline = Santri::where('last_seen', '>=', $threshold)->whereNot('no_induk', 958)->get();

    $onlineUsers = $usersOnline->concat($santriOnline);
    $jmlUserOnline = $onlineUsers->count();

    return view('admin.log-akses.index', compact('aktifitas', 'title', 'onlineUsers', 'jmlUserOnline'));
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
      'santri_detail.nama',
    ])
      ->leftJoin('santri_detail', 'santri_detail.id', '=', 'activity_log.causer_id')
      ->orderBy('activity_log.created_at', 'desc')
      ->get();

    return view('admin.log-akses.index', compact('aktifitas', 'title'));
  }
}
