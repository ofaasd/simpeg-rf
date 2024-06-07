<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Image;

class BeritaController extends Controller
{
  /**
   * Display a listing of the resource.
   */

  public function index()
  {
    $title = 'Berita';
    $berita = Berita::all();
    $kategori = Kategori::all();
    return view('admin.berita.index', compact('berita', 'title', 'kategori'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  public function poto()
  {
    $berita = Berita::leftJoin('users', 'beritas.user_id', '=', 'users.id')
      ->leftJoin('kategori', 'beritas.kategori_id', '=', 'kategori.id')
      ->select(
        'users.name',
        'kategori.nama_kategori',
        'beritas.kategori_id',
        'beritas.judul',
        'beritas.slug',
        'beritas.thumbnail',
        'beritas.gambar_dalam',
        'beritas.isi_berita'
      )
      ->get();

    // Modify the paths if necessary
    $thumbnailPath = 'assets/img/upload/berita/thumbnail/';
    $gambarDalamPath = 'assets/img/upload/berita/foto_isi/';

    $result = [];
    foreach ($berita as $item) {
      $result[] = [
        'name' => $item->name,
        'nama_kategori' => $item->nama_kategori,
        'judul' => $item->judul,
        'slug' => $item->slug,
        'thumbnail' => $thumbnailPath . $item->thumbnail,
        'gambar_dalam' => $gambarDalamPath . $item->gambar_dalam,
        'isi_berita' => $item->isi_berita,
      ];
    }

    return response()->json($result);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $id = $request->id;
    $id_user = Auth::user()->id;

    if ($id) {
      $slug = Str::slug($request->judul);
      if ($request->file('gambar')) {
        $thumbnail = $request->file('thumbnail');
        $foto_isi = $request->file('foto_isi');
        $fileNameThumbnail = date('YmdHis') . $thumbnail->getClientOriginalName();
        $fileNameFotoIsi = date('YmdHis') . $foto_isi->getClientOriginalName();
        Image::make($thumbnail)
          ->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
          })
          ->save('assets/img/upload/berita/thumbnail/' . $fileNameThumbnail); //note

        Image::make($foto_isi)
          ->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
          })
          ->save('assets/img/upload/berita/foto_isi/' . $fileNameFotoIsi); //note

        $berita = Berita::updateOrCreate(
          ['id' => $id],
          [
            'thumbnail' => $fileNameThumbnail,
            'gambar_dalam' => $fileNameFotoIsi,
            'slug' => $slug,
            'judul' => $request->judul,
            'isi_berita' => $request->isi_berita,
            'kategori_id' => $request->kategori,
            'user_id' => $id_user,
          ]
        );
      } else {
        $berita = Berita::updateOrCreate(
          ['id' => $id],
          [
            'slug' => $slug,
            'judul' => $request->judul,
            'isi_berita' => $request->isi_berita,
            'kategori_id' => $request->kategori,
            'user_id' => $id_user,
          ]
        );
      }
    } else {
      $thumbnail = $request->file('thumbnail');
      $foto_isi = $request->file('foto_isi');
      $fileNameThumbnail = date('YmdHis') . $thumbnail->getClientOriginalName();
      $fileNameFotoIsi = date('YmdHis') . $foto_isi->getClientOriginalName();
      $slug = Str::slug($request->judul);

      Image::make($thumbnail)
        ->resize(800, null, function ($constraint) {
          $constraint->aspectRatio();
        })
        ->save('assets/img/upload/berita/thumbnail/' . $fileNameThumbnail); //note

      Image::make($foto_isi)
        ->resize(800, null, function ($constraint) {
          $constraint->aspectRatio();
        })
        ->save('assets/img/upload/berita/foto_isi/' . $fileNameFotoIsi);

      $berita = Berita::updateOrCreate(
        ['id' => $id],
        [
          'thumbnail' => $fileNameThumbnail,
          'gambar_dalam' => $fileNameFotoIsi,
          'slug' => $slug,
          'judul' => $request->judul,
          'isi_berita' => $request->isi_berita,
          'kategori_id' => $request->kategori,
          'user_id' => $id_user,
        ]
      );
    }
    if ($berita) {
      // user created
      return response()->json('Created');
    } else {
      return response()->json('Failed Create');
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Berita $berita)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $where = ['id' => $id];

    $berita = Berita::where($where)->first();

    return response()->json($berita);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update($request, Berita $berita)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $berita = Berita::where('id', $id)->delete();
  }

  public function reload()
  {
    $title = 'Berita';
    $agenda = Berita::all();

    return view('admin.berita.index', compact('berita', 'kategori', 'title'));
  }
}
