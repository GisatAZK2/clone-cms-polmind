<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarMitra;

class MitraController extends Controller
{
    public function index(){
        $mitra = DaftarMitra::all();
        return view('partner', compact('mitra'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_partner' => 'required|string',
            'url_image' => 'required|image',
        ]);

        try {
            $file = $request->file('url_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('images', $filename, 'public');

            DaftarMitra::create([
                'nama_partner' => $request->nama_partner,
                'url_image' => $filename,
                'alt' => $request->nama_partner,
            ]);

            return back()->with('success', 'Mitra berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan mitra: ' . $e->getMessage());
        }
    }
}
