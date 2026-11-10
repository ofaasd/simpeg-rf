<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\EmployeeNew;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserManagement extends Controller
{
  /**
   * Redirect to user-management view.
   *
   */
  public function UserManagement()
  {
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    if (empty($request->input('length'))) {
      $users = User::all();
      $userCount = $users->count();
      $pegawai = EmployeeNew::all();
      $verified = User::whereNotNull('email_verified_at')
        ->get()
        ->count();
      $notVerified = User::whereNull('email_verified_at')
        ->get()
        ->count();
      $usersUnique = $users->unique(['email']);
      $role = Role::all();
      $userDuplicates = $users->diff($usersUnique)->count();

      return view('admin.user.user-management', [
        'totalUser' => $userCount,
        'verified' => $verified,
        'notVerified' => $notVerified,
        'role' => $role,
        'pegawai' => $pegawai,
        'userDuplicates' => $userDuplicates,
      ]);
    } else {
      $columns = [
        1 => 'id',
        2 => 'name',
        3 => 'email',
        4 => 'email_verified_at',
      ];

      $search = [];

      $totalData = User::count();

      $totalFiltered = $totalData;

      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');

      if (empty($request->input('search.value'))) {
        $users = User::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      } else {
        $search = $request->input('search.value');

        $users = User::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->orWhere('email', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();

        $totalFiltered = User::where('id', 'LIKE', "%{$search}%")
          ->orWhere('name', 'LIKE', "%{$search}%")
          ->orWhere('email', 'LIKE', "%{$search}%")
          ->count();
      }

      $data = [];

      if (!empty($users)) {
        // providing a dummy id instead of database ids
        $ids = $start;

        foreach ($users as $user) {
          $nestedData['id'] = $user->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['name'] = $user->name;
          $nestedData['email'] = $user->email;
          $nestedData['email_verified_at'] = $user->getRoleNames();

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
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $userID = $request->id;

    if ($userID) {
      // update the value
      $userData = [
        'name' => $request->name,
        'email' => $request->email,
        'pegawai_id' => $request->pegawai_id,
      ];

      if ($request->filled('password')) {
        $userData['password'] = Hash::make($request->password);
      }

      $users = User::updateOrCreate(
        ['id' => $userID],
        $userData
      );

      // user updated
      $role = $request->role;
      $users->syncRoles($role);
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      $userEmail = User::where('email', $request->email)->first();

      if (empty($userEmail)) {
        $password = $request->filled('password') ? Hash::make($request->password) : Hash::make('password');
        $users = User::updateOrCreate(
          ['id' => $userID],
          [
            'name' => $request->name,
            'email' => $request->email,
            'pegawai_id' => $request->pegawai_id,
            'password' => $password,
          ]
        );
        $role = $request->role;
        $users->assignRole($role);
        // user created
        return response()->json('Created');
      } else {
        // user already exist
        return response()->json(['message' => 'already exits'], 422);
      }
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $users = User::find($id);

    $role = $users->roles->pluck('id')->first();

    return response()->json([
      'users' => $users,
      'role' => $role,
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $users = User::where('id', $id)->delete();
  }

  /**
   * Update password for a specified user.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function updatePassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|exists:users,id',
      'password' => 'required|string|min:6|confirmed',
    ], [
      'user_id.required' => 'User ID tidak valid.',
      'user_id.exists' => 'User tidak ditemukan.',
      'password.required' => 'Password baru wajib diisi.',
      'password.min' => 'Password minimal harus 6 karakter.',
      'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 'error',
        'message' => $validator->errors()->first(),
        'errors' => $validator->errors(),
      ], 422);
    }

    $user = User::findOrFail($request->user_id);
    $user->password = Hash::make($request->password);
    $user->save();

    return response()->json([
      'status' => 'success',
      'message' => 'Password untuk user "' . $user->name . '" berhasil diperbarui!'
    ]);
  }
}
