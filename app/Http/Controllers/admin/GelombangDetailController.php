<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PsbGelombang;
use App\Models\PsbGelombangDetail;
use App\Models\TahunAjaran;

class GelombangDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
      $title = 'Detail Gelombang';
      $detail = PsbGelombangDetail::where('id_gelombang',$id)->first() ?? '';
      return view('admin.gelombang_detail.index', compact('title', 'detail','id'));
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
        $gelombang = PsbGelombangDetail::where('id_gelombang',$request->id)->first();
        $id ='';
        if($gelombang){
          $id = $gelombang->id;
        }
        if ($id) {
          // update the value
          $gelombang = PsbGelombangDetail::updateOrCreate(
            ['id' => $id],
            [
              'id_gelombang' => $request->id,
              'hari' => $request->hari,
              'jam' => $request->jam,
              'syarat' => $request->syarat,
              'prosedur_online' => $request->prosedur_online,
              'prosedur_offline' => $request->prosedur_offline,
            ]
          );

          // user updated
          return response()->json('Updated');
        } else {
          // create new one if email is unique
          //$userEmail = User::where('email', $request->email)->first();

          $gelombang = PsbGelombangDetail::updateOrCreate(
            ['id' => $id],
            [
              'id_gelombang' => $request->id,
              'hari' => $request->hari,
              'jam' => $request->jam,
              'syarat' => $request->syarat,
              'prosedur_online' => $request->prosedur_online,
              'prosedur_offline' => $request->prosedur_offline,
            ]
          );
          if ($gelombang) {
            // user created
            return response()->json('Created');
          } else {
            return response()->json('Failed Create Academic');
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
