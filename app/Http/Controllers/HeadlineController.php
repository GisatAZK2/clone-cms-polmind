<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarHeadline;


class HeadlineController extends Controller
{
    public function index(){
        $headline = DaftarHeadline::all();
        return view('headline', compact('headline'));
    }

    public function store(Request $request){
        $request->validate([
            'title'=>'required',
            'url_image'=>'required',
            'alt'=>'required',
    ]);



    try{
        $file = $request->file('url_image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('images', $filename, 'public');

            DaftarHeadline::create([
            'title'=>$request->title,
            'url_image'=>$filename,
            'alt'=>$request->alt
            ]);

            return redirect()->back()->with('succes', 'headline telah dibuat');
        

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

/*<?php

namespace App\Http\Controllers;

use App\Models\DaftarDosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        $daftardosen = DaftarDosen::all();
        return view('dosen', compact('daftardosen'));
    }

    public function store(Request $request)
    {
     
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'url_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:Dosen_Internal,Expert_industri',
            'alt' => 'required|string|max:255',

        ]);

        try {
           
            $file = $request->file('url_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $filename);

            // Simpan data ke database
            DaftarDosen::create([
                'name' => $request->name,
                'url_image' => $filename,
                'type' => $request->type,
                'alt' => $request->alt,
            ]);

            return redirect()->back()->with('success', 'Gambar berhasil diupload!');
        } 
    }
}
    */