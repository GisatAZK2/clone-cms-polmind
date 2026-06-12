<?php

namespace App\Http\Controllers;

use App\Models\DaftarSlider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index(){
        $slider = DaftarSlider::all();
        return view('slider', compact('slider'));
    }

    public function store(Request $request){
        $request->validate([
            'url_image'=>'required|image',
            'alt'=>'required|string',
        ]);

        try {
            $file = $request->file('url_image');
            $filename = time() . "." . $file->getClientOriginalExtension();
            $file->storeAs('images', $filename, 'public');

            DaftarSlider::create([
                'url_image'=>$filename,
                'alt'=>$request->alt,
            ]);

            return back()->with('success', 'Slider berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
