<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PsbSlide;
use Image;

class PsbSlideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $indexed = ['', 'id', 'gambar','caption','link'];
    public function index(Request $request)
    {
      //
      if (empty($request->input('length'))) {
        $slide = PsbSlide::all();
        $title2 = 'PSB Slideshow';
        $title = 'psb_slide';
        $indexed = $this->indexed;
        return view('admin.psb.slides', compact('title','title2', 'indexed'));
      } else {
        $columns = [
          1 => 'id',
          2 => 'gambar',
          3 => 'caption',
          4 => 'link',
        ];

        $search = [];

        $totalData = PsbSlide::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
          $slide = PsbSlide::offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();
        } else {
          $search = $request->input('search.value');

          $slide = PsbSlide::where('id', 'LIKE', "%{$search}%")
            ->orWhere('caption', 'LIKE', "%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

          $totalFiltered = PsbSlide::where('id', 'LIKE', "%{$search}%")
            ->orWhere('caption', 'LIKE', "%{$search}%")
            ->count();
        }

        $data = [];

        if (!empty($slide)) {
          // providing a dummy id instead of database ids
          $ids = $start;

          foreach ($slide as $row) {
            $nestedData['id'] = $row->id;
            $nestedData['fake_id'] = ++$ids;
            $nestedData['gambar'] = $row->gambar;
            $nestedData['caption'] = $row->caption;
            $nestedData['link'] = $row->link;
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
            'data' => $data,
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
      $gambar = '';
      if ($request->file('gambar')) {
        $photo = $request->file('gambar');
        $filename = date('YmdHi') . $photo->getClientOriginalName();
        $kompres = Image::make($photo)
          ->resize(940, null, function ($constraint) {
            $constraint->aspectRatio();
          })
          ->save('assets/img/upload/photo/slide_' . $filename);
        if ($kompres) {
          //$file = $request->file->store('public/assets/img/upload/photo');
          $gambar = $filename;
        }

      }
      if ($id) {
        // update the value
        if ($request->file('gambar')) {
          $slide = PsbSlide::updateOrCreate(['id' => $id], ['caption' => $request->caption, 'link' => $request->link, 'gambar' => $gambar]);
        }else{
          $slide = PsbSlide::updateOrCreate(['id' => $id], ['caption' => $request->caption, 'link' => $request->link]);
        }

        // user updated
        return response()->json('Updated');
      } else {
        // create new one if email is unique
        //$userEmail = User::where('email', $request->email)->first();
        if ($request->file('gambar')) {
          $slide = PsbSlide::updateOrCreate(['id' => $id], ['caption' => $request->caption, 'link' => $request->link, 'gambar' => $gambar]);
        }else{
          $slide = PsbSlide::updateOrCreate(['id' => $id], ['caption' => $request->caption, 'link' => $request->link]);
        }
        if ($slide) {
          // user created
          return response()->json('Created');
        } else {
          return response()->json('Failed Create status');
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

      $slide = PsbSlide::where($where)->first();

      return response()->json($slide);
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
      $slide = PsbSlide::where('id', $id)->delete();
    }
}
