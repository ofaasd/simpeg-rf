<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\School;
use App\Http\Controllers\Controller;

class SchoolController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $indexed = ['', 'id', 'name', 'grade', 'address'];

  public function index(Request $request)
  {
    //
    if (empty($request->input('length'))) {
      $academic = School::all();
      $title = 'School';
      $indexed = $this->indexed;
      return view('admin.school.index', compact('title', 'indexed'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'name',
        3 => 'grade',
        4 => 'address',
      ];

      $search = [];

      $totalData = School::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $school = School::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $school = School::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = School::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->count();
      }

      $data = [];

      if (!empty($school)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($school as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['name'] = $row->name;
          $nestedData['grade'] = $row->grade;
          $nestedData['address'] = $row->address;
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
      $school = School::updateOrCreate(
        ['id' => $id],
        [
          'name' => $request->name,
          'grade' => $request->grade,
          'address' => $request->address,
          'phone' => $request->phone,
          'fax' => $request->fax,
          'email' => $request->email,
          'latitude' => $request->latitude,
          'website' => $request->website,
          'employee_id' => $request->employee_id,
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $school = School::updateOrCreate(
        ['id' => $id],
        [
          'name' => $request->name,
          'grade' => $request->grade,
          'address' => $request->address,
          'phone' => $request->phone,
          'fax' => $request->fax,
          'email' => $request->email,
          'latitude' => $request->latitude,
          'website' => $request->website,
          'employee_id' => $request->employee_id,
        ]
      );
      if ($school) {
        // user created
        return response()->json('Created');
      } else {
        return response()->json('Failed Create School');
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

    $school = School::where($where)->first();

    return response()->json($school);
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
    $school = School::where('id', $id)->delete();
  }
}
