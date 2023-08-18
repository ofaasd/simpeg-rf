<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grades;

class GradesController extends Controller
{
  public $indexed = ['', 'id', 'name', 'description'];
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    //
    if (empty($request->input('length'))) {
      $Grades = Grades::all();
      $title = 'Grades';
      $indexed = $this->indexed;
      return view('admin.grades.index', compact('title', 'indexed'));
    } else {
      $columns = [
        1 => 'id',
        2 => 'name',
        3 => 'description',
      ];

      $search = [];

      $totalData = Grades::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $Grades = Grades::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $Grades = Grades::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = Grades::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->count();
      }

      $data = [];

      if (!empty($Grades)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($Grades as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['name'] = $row->name;
          $nestedData['description'] = $row->description;
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
      $Grades = Grades::updateOrCreate(
        ['id' => $id],
        ['name' => $request->name, 'description' => $request->description]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $Grades = Grades::updateOrCreate(
        ['id' => $id],
        ['name' => $request->name, 'description' => $request->description]
      );
      if ($Grades) {
        // user created
        return response()->json('Created');
      } else {
        return response()->json('Failed Create Grades');
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

    $Grades = Grades::where($where)->first();

    return response()->json($Grades);
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
    $Grades = Grades::where('id', $id)->delete();
  }
}
