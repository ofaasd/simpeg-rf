<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agenda;
use Illuminate\Support\Facades\Auth;
use Image;

class AgendaController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public $kategori = [1 => 'Agenda', 'Pengumuman', 'Berita'];
  public function index()
  {
    //
    $title = 'Agenda';
    $agenda = Agenda::all();
    $kategori = $this->kategori;

    return view('admin.agenda.index', compact('agenda', 'kategori', 'title'));
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
    $id_user = Auth::user()->id;
    // $tanggal_mulai = $request->tanggal_mulai;
    // $dt = DateTime::createFromFormat('Y-m-d\TH:i', $tanggal_mulai);
    // $new_tanggal_mulai = $dt->format('Y-m-d H:i:s');

    // $tanggal_selesai = $request->tanggal_selesai;
    // $dt2 = DateTime::createFromFormat('Y-m-d\TH:i', $tanggal_selesai);
    // $new_tanggal_selesai = $dt2->format('Y-m-d H:i:s');
    if ($id) {
      if ($request->file('gambar')) {
        $photo = $request->file('gambar');
        $filename = date('YmdHis') . $photo->getClientOriginalName();
        $kompres = Image::make($photo)
          ->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
          })
          ->save('assets/img/upload/foto_agenda/' . $filename); //note
        $agenda = Agenda::updateOrCreate(
          ['id' => $id],
          [
            'gambar' => $filename,
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'user_id' => $id_user,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
          ]
        );
      } else {
        $agenda = Agenda::updateOrCreate(
          ['id' => $id],
          [
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'user_id' => $id_user,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
          ]
        );
      }

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      //$userEmail = User::where('email', $request->email)->first();

      if ($request->file('gambar')) {
        $photo = $request->file('gambar');
        $filename = date('YmdHis') . $photo->getClientOriginalName();
        $kompres = Image::make($photo)
          ->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
          })
          ->save('assets/img/upload/foto_agenda/' . $filename); //note
        $agenda = Agenda::updateOrCreate(
          ['id' => $id],
          [
            'gambar' => $filename,
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'user_id' => $id_user,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
          ]
        );
      } else {
        $agenda = Agenda::updateOrCreate(
          ['id' => $id],
          [
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'user_id' => $id_user,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
          ]
        );
      }
      if ($agenda) {
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

    $agenda = Agenda::where($where)->first();
    return response()->json($agenda);
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
    $agenda = Agenda::where('id', $id)->delete();
  }
  public function reload(Request $request)
  {
    $title = 'Agenda';
    $agenda = Agenda::all();
    $kategori = $this->kategori;

    return view('admin.agenda.table', compact('agenda', 'kategori', 'title'));
  }
}
