<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Dakwah;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mpdf\Tag\Em;

class DakwahController extends Controller
{
    public function index()
    {
        $title = 'Dakwah';
        $dakwah = Dakwah::with(['user'])->latest()->get();

        return view('admin.dakwah.index', compact('dakwah', 'title'));
    }

    public function store(Request $request)
    {
        $id_user = Auth::user()->id;
        $id = $request->id;

        $slug = Str::slug($request->judul);

        $data = [
            'slug' => $slug,
            'judul' => $request->judul,
            'isi_dakwah' => $request->isi_dakwah,
            'link' => $request->link
        ];

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $filename);
            $data['foto'] = 'img/' . $filename;
        }

        if (!empty($id)) {
            $dakwah = Dakwah::updateOrCreate(['id' => $id], $data);
            $messageResponse = "Berhasil Merubah Dakwah";
        } else {
            $data['user_id'] = $id_user;
            $dakwah = Dakwah::updateOrCreate(['id' => $id], $data);
            $messageResponse = "Berhasil Ditambahkan Dakwah";
        }

        if ($dakwah) {
            return response()->json($messageResponse);
        } else {
            return response()->json('failed created');
        }
    }

    public function edit(string $id)
    {
        $where = ['id' => $id];

        $data = Dakwah::where($where)->first();

        return response()->json($data);
    }

    public function destroy(string $id)
    {
        $berita = Dakwah::where('id', $id)->first();
        $berita->delete();
    }

    public function reload()
    {
        $title = 'Dakwah';
        $dakwah = Dakwah::all();

        return view('admin.berita.index', compact('dakwah', 'title'));
    }
}
