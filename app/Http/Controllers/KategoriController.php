<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
  /**
   * Display a listing of the resource.
   */

  public function index()
  {
    $title = 'Kategori Berita';
    $kategori = Kategori::all();

    return view('admin.kategoriBerita.index', compact('kategori', 'title'));
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
      $kategori = Kategori::updateOrCreate(
        ['id' => $id],
        [
          'nama_kategori' => $request->nama_kategori,
        ]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      $kategori = Kategori::updateOrCreate(
        ['id' => $id],
        [
          'nama_kategori' => $request->nama_kategori,
        ]
      );

      if ($kategori) {
        // user created
        return response()->json('Created');
      } else {
        return response()->json('Failed Create');
      }
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Kategori $kategori)
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

    $kategori = Kategori::where($where)->first();
    return response()->json($kategori);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Kategori $kategori)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    Kategori::where('id', $id)->delete();
  }

  public function reload()
  {
    $title = 'Kategori Berita';
    $agenda = Kategori::all();

    return view('admin.kategoriBerita.index', compact('kategori', 'title'));
  }
}
