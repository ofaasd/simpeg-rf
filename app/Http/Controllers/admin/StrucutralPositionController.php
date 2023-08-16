<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StructuralPosition;
use App\Models\School;
use App\Models\SchoolIdentityStructuralPosition;

class StrucutralPositionController extends Controller
{
  /**
   * Display a listing of the resource.
   */

  public $indexed = ['', 'id', 'name', 'type', 'structural_position_id', 'description'];
  public $tipe = [
    '' => '-',
    '1' => 'Kepala Sekolah',
    '2' => 'Penasehat',
    '3' => 'Pengasuh',
    '4' => 'Guru',
    '5' => 'Tata Usaha',
    '6' => 'Staf IT',
    '7' => 'Satpam',
    '8' => 'Kebersihan',
    '9' => 'Masak',
    '10' => 'Murroby',
    '11' => 'UKS',
  ];
  public function index(Request $request)
  {
    //
    if (empty($request->input('length'))) {
      $StructuralPosition = StructuralPosition::all();
      $school = School::all();
      $title = 'Structural-Position';
      $tipe = $this->tipe;
      $indexed = $this->indexed;
      return view(
        'admin.structural_position.index',
        compact('title', 'school', 'indexed', 'StructuralPosition', 'tipe')
      );
    } else {
      $columns = [
        1 => 'id',
        2 => 'name',
        3 => 'type',
        3 => 'structural_position_id',
        4 => 'description',
      ];

      $search = [];

      $totalData = StructuralPosition::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $StructuralPosition = StructuralPosition::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $StructuralPosition = StructuralPosition::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = StructuralPosition::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->count();
      }

      $data = [];

      if (!empty($StructuralPosition)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($StructuralPosition as $row) {
          $nestedData['id'] = $row->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['name'] = $row->name;
          $nestedData['type'] = $this->tipe[$row->type];
          $nestedData['structural_position_id'] = $row->structural_position_id == 0 ? '-' : $row->superordinate->name;
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
      $StructuralPosition = StructuralPosition::updateOrCreate(
        ['id' => $id],
        [
          'name' => $request->name,
          'structural_position_id' => $request->structural_position_id,
          'type' => $request->type,
          'description' => $request->description,
        ]
      );
      $SchoolIdentityStructuralPosition = SchoolIdentityStructuralPosition::updateOrCreate(
        ['structural_position_id' => $id],
        [
          'school_identity_id' => $request->school_identity_id,
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $StructuralPosition = StructuralPosition::updateOrCreate(
        ['id' => $id],
        [
          'name' => $request->name,
          'structural_position_id' => $request->structural_position_id,
          'type' => $request->type,
          'description' => $request->description,
        ]
      );
      $SchoolIdentityStructuralPosition = SchoolIdentityStructuralPosition::updateOrCreate(
        ['id' => ''],
        [
          'school_identity_id' => $request->school_identity_id,
          'structural_position_id' => $StructuralPosition->id,
        ]
      );

      if ($StructuralPosition) {
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

    $StructuralPosition = StructuralPosition::where($where)->first();

    return response()->json($StructuralPosition);
  }

  public function getSchool(string $id)
  {
    $where = ['structural_position_id' => $id];

    $SchoolIdentityStructuralPosition = SchoolIdentityStructuralPosition::where($where)->first();

    return response()->json($StructuralPosition);
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
    $StructuralPosition = StructuralPosition::where('id', $id)->delete();
    $SchoolIdentityStructuralPosition = SchoolIdentityStructuralPosition::where(
      'structural_position_id',
      $id
    )->delete();
  }
}
