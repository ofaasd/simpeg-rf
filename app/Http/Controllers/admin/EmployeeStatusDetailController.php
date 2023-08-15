<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeStatusModel as status;
use App\Models\EmployeeStatusDetailModel as status_detail;
use Illuminate\Http\Request;

class EmployeeStatusDetailController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $indexed = ['', 'id', 'employee_status_id', 'name', 'can_leave'];
  public function index(Request $request)
  {
    //
    if (empty($request->input('length'))) {
      $title = 'Employee-Status-Detail';
      $indexed = $this->indexed;
      $status = status::all();
      $can_leave = ['Tidak Bisa', 'Bisa'];
      return view('admin.pegawai.status_detail.index', compact('title', 'indexed', 'status', 'can_leave'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'employee_status_id',
        3 => 'name',
        4 => 'can_leave',
      ];

      $search = [];

      $totalData = status_detail::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $status_detail = status_detail::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $status_detail = status_detail::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = status_detail::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->count();
      }

      $data = [];

      if (!empty($status_detail)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($status_detail as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['employee_status_id'] = $row->status->name;
          $nestedData['name'] = $row->name;
          $nestedData['can_leave'] = $row->can_leave;
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

    if ($id) {
      // update the value
      $status = status_detail::updateOrCreate(
        ['id' => $id],
        [
          'employee_status_id' => $request->employee_status_id,
          'name' => $request->name,
          'can_leave' => $request->can_leave,
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $status = status_detail::updateOrCreate(
        ['id' => $id],
        [
          'employee_status_id' => $request->employee_status_id,
          'name' => $request->name,
          'can_leave' => $request->can_leave,
        ]
      );
      if ($status) {
        // user created
        return response()->json('Created');
      } else {
        return response()->json('Failed Create status');
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

    $status_detail = status_detail::where($where)->first();

    return response()->json($status_detail);
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
    $status = status_detail::where('id', $id)->delete();
  }
}
