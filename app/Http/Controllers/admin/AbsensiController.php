<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $indexed = ['', 'id', 'user_id', 'day', 'masuk', 'keluar'];

  public function index(Request $request)
  {
    //
    $role = Auth::user()->getRoleNames();
    $user = User::all();
    $role = $role[0];
    if (empty($request->input('length'))) {
      $absensi = Absensi::where('user_id', Auth::user()->id)
        ->where('day', date('Y-m-d'))
        ->first();

      $list_absensi = Absensi::where('user_id', Auth::user()->id)
        ->whereMonth('day', date('m'))
        ->get();

      $title = 'Absensi';
      $indexed = $this->indexed;
      if ($role == 'admin') {
        return view('admin.absensi.index', compact('title', 'indexed', 'absensi', 'user'));
      } else {
        return view('admin.absensi.index_user', compact('title', 'indexed', 'absensi', 'list_absensi'));
      }
    } else {
      $columns = [
        1 => 'id',
        2 => 'user_id',
        3 => 'day',
        4 => 'masuk',
        5 => 'keluar',
      ];

      $search = [];

      $totalData = Absensi::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $absensi = Absensi::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir);
        if ($role == 'admin') {
          $absensi = $absensi->get();
        } else {
          $absensi = $absensi->where('user_id', Auth::user()->id)->get();
        }
      } else {
        $search = $request->input('search.value');

        $absensi = Absensi::where('id', 'LIKE', "%{$search}%")
          ->orWhere('user_id', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir);
        if ($role == 'admin') {
          $absensi = $absensi->get();
        } else {
          $absensi = $absensi->where('user_id', Auth::user()->id)->get();
        }

        $totalFiltered = Absensi::where('id', 'LIKE', "%{$search}%")->orWhere('user_id', 'LIKE', "%{$search}%");
        if ($role == 'admin') {
          $totalFiltered = $totalFiltered->count();
        } else {
          $totalFiltered = $totalFiltered->where('user_id', Auth::user()->id)->count();
        }
      }

      $data = [];

      if (!empty($absensi)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($absensi as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['user_id'] = $row->user->pegawai->nama;
          $nestedData['day'] = $row->day;
          $nestedData['masuk'] = !empty($row->start) ? date('H:i:s', $row->start) : '';
          $nestedData['keluar'] = !empty($row->end) ? date('H:i:s', $row->end) : '';
          $data[] = $nestedData;
        }
      }

      if ($data) {
        return response()->json([
          'draw' => intval($request->input('draw')),
          'recordsTotal' => intval($totalData),
          'recordsFiltered' => intval($totalFiltered),
          'code' => 200,
          'data' => $data,
        ]);
      } else {
        return response()->json([
          'message' => 'Internal Server Error',
          'code' => 500,
          'data' => [],
        ]);
      }
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
    //
    $id = $request->id;
    //insert by admin
    $role = Auth::user()->getRoleNames();
    $role = $role[0];
    if ($role == 'admin') {
      if ($id) {
        // update the value
        $absensi = Absensi::updateOrCreate(
          ['id' => $id],
          [
            'user_id' => $request->user_id,
            'day' => $request->day,
            'start' => strtotime($request->start),
            'end' => strtotime($request->end),
            'start_marked_by_admin' => 1,
          ]
        );

        // user updated
        return response()->json('Updated');
      } else {
        // create new one if email is unique
        //$userEmail = User::where('email', $request->email)->first();

        $absensi = Absensi::updateOrCreate(
          ['id' => $id],
          [
            'user_id' => $request->user_id,
            'day' => $request->day,
            'start' => strtotime($request->start),
            'end' => strtotime($request->end),
          ]
        );
        if ($absensi) {
          // user created
          return response()->json('Created');
        } else {
          return response()->json('Failed Create Academic');
        }
      }
    } else {
      $img = $request->image;
      $folderPath = 'assets/img/upload/absensi/';
      $absensi = Absensi::where('user_id', Auth::user()->id)
        ->where('day', date('Y-m-d'))
        ->first();
      $image_parts = explode(';base64,', $img);
      $image_type_aux = explode('image/', $image_parts[0]);
      $image_type = $image_type_aux[1];

      $image_base64 = base64_decode($image_parts[1]);
      $fileName = uniqid() . '.' . $image_type;

      $file = $folderPath . $fileName;
      if (file_put_contents($file, $image_base64)) {
        if (empty($absensi->start)) {
          $absensi = new Absensi();
          $absensi->user_id = Auth::user()->id;
          $absensi->is_remote = 1;
          $absensi->day = date('Y-m-d');
          $absensi->start = strtotime(date('H:i:s'));
          $absensi->lat_start = $request->lat;
          $absensi->long_start = $request->long;
          $absensi->ip_start = $_SERVER['REMOTE_ADDR'];
          $absensi->browser_start = $_SERVER['HTTP_USER_AGENT'];
          $absensi->image_start = $fileName;
        } elseif (empty($absensi->end)) {
          $absensi = Absensi::find($absensi->id);
          $absensi->end = strtotime(date('H:i:s'));
          $absensi->lat_end = $request->lat;
          $absensi->long_end = $request->long;
          $absensi->ip_end = $_SERVER['REMOTE_ADDR'];
          $absensi->browser_end = $_SERVER['HTTP_USER_AGENT'];
          $absensi->image_end = $fileName;
        } else {
          return response()->json('Data Sudah dimasukan');
        }
        //$absensi->save();
        // Storage::put($file, $image_base64);
        // Storage::move($file, public_path('assets/img/upload/absensi/' . $fileName));
        //file_put_contents(public_path() . '/assets/img/upload/absensi/' . $filename, $image_base64);
        if ($absensi->save()) {
          return response()->json('Data berhasil Di Input');
        } else {
          return response()->json('Data Gagal Disimpan');
        }
      } else {
        return response()->json('Gagal Upload File / Gambar Tidak ditemukan');
      }
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
    //
    $where = ['id' => $id];

    $absensi = Absensi::where($where)->first();
    $start = !empty($absensi->start) ? date('H:i:s', $absensi->start) : '';
    $end = !empty($absensi->end) ? date('H:i:s', $absensi->end) : '';
    $data = [
      'id' => $absensi->id,
      'pegawai_id' => $absensi->user_id,
      'day' => $absensi->day,
      'start' => $start,
      'end' => $end,
    ];

    return response()->json($data);
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
    $role = Auth::user()->getRoleNames();
    if ($role[0] == 'admin') {
      $absensi = Absensi::where('id', $id)->delete();
      $status = [
        'status' => 1,
      ];
      return response()->json($status);
    } else {
      $status = [
        'status' => 0,
      ];
      return response()->json($status);
    }
  }
}
