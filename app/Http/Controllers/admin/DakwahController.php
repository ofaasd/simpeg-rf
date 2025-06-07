<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Dakwah;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

        if (!empty($id)) {
            $slug = Str::slug($request->judul);
                $dakwah = Dakwah::updateOrCreate(
                    ['id' => $id],
                    [
                        'slug' => $slug,
                        'judul' => $request->judul,
                        'isi_dakwah' => $request->isi_dakwah,
                    ]
                );
                $messageResponse = "Berhasil merubah dakwah";
        } else {
            $slug = Str::slug($request->judul);

            $dakwah = Dakwah::updateOrCreate(
                ['id' => $id],
                [
                    'slug' => $slug,
                    'judul' => $request->judul,
                    'isi_dakwah' => $request->isi_dakwah,
                    'user_id' => $id_user,
                ]
            );
                $messageResponse = "Berhasil menambahkan dakwah";
            }

            if ($dakwah) 
            {
                return response()->json($messageResponse);
            } 
        else {
            return response()->json('Failed Create');
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
