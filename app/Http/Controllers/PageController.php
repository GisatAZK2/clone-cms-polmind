<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Headline;
use App\Models\Popup;
use App\Models\Content_pendaftaran;
use App\Models\Keunikan_Dan_Keunggulan;
use App\Models\Keunggulan;
use App\Models\Mitra;
use App\Models\Program_Sarjana_Terapan;
use App\Models\Sambutan_Direktur;
use App\Models\Projects;
use App\Models\Prodi;
use App\Models\Karakter;

class PageController extends Controller
{
    /**
     * Display beranda page
     */
    public function beranda()
    {
        $data = [
            'page_title' => 'Beranda - Politeknik Mitra Industri',
            'canonical_url' => 'https://polmind.ac.id/beranda',
            'meta_description' => 'Politeknik Mitra Industri Dikembangkan di kawasan industri MM2100, didukung oleh para praktisi industri dan pendidikan.',
            'meta_keywords' => 'politeknik, mitra, industri, polmind, politeknik mitra industri, perguruan tinggi vokasi terbaik, politeknik terbaik',
            'slides' => Headline::where('status', 'active')->orderBy('urutan')->orderBy('id')->get(),
            'contentPendaftaranAtas' => Content_pendaftaran::where('type', 'atas')->latest('id')->first(),
            'contentPendaftaranBawah' => Content_pendaftaran::where('type', 'bawah')->latest('id')->first(),
            'keunggulan' => Keunggulan::latest('id')->get(),
            'jumlahprodi' => Program_Sarjana_Terapan::count(),
            'latestNews' => News::where('status', 'published')->orderBy('urutan')->latest('published_at')->limit(8)->get(),
            'partners' => Mitra::latest('id')->get(),
            'popup' => Popup::latest('id')->first(),
            'sambutan' => Sambutan_Direktur::latest('id')->first(),   // Ambil data terbaru
            'projects' => Projects::latest('id')->get(),
            'prodis' => Prodi::whereIn('type', ['card', 'image'])
                ->orderByRaw("FIELD(type, 'card', 'image')")
                ->latest('id')
                ->get(),
            'karakters' => Karakter::latest('id')->get(),
        ];
        return view('pages.beranda', $data);
    }

    /**
     * Display profil page
     */
    public function profil()
    {
        $data = [
            'page_title' => 'Profil - Politeknik Mitra Industri',
            'canonical_url' => 'https://polmind.ac.id/profil',
            'meta_description' => 'Profil Politeknik Mitra Industri - Visi, Misi, dan Jajaran Pendiri & Expert',
            'meta_keywords' => 'profil polmind, visi misi, pendiri expert'
        ];
        return view('pages.profil', $data);
    }

    /**
     * Display keunikan page
     */
   public function keunikan()
{
    $items = Keunikan_Dan_Keunggulan::latest()->get();

    $data = [
        'page_title' => 'Keunikan - Politeknik Mitra Industri',
        'canonical_url' => 'https://polmind.ac.id/keunikan',
        'meta_description' => 'Keunikan dan Keunggulan Politeknik Mitra Industri - Teaching Factory dan Project-Based Learning',
        'meta_keywords' => 'keunikan polmind, teaching factory, project based learning',
        'items' => $items
    ];

    return view('pages.keunikan', $data);
}
    /**
     * Display pmb page
     */
    public function pmb()
    {
        $data = [
            'page_title' => 'PMB - Politeknik Mitra Industri',
            'canonical_url' => 'https://polmind.ac.id/pmb',
            'meta_description' => 'Pendaftaran Mahasiswa Baru Politeknik Mitra Industri Tahun 2025/2026',
            'meta_keywords' => 'pmb polmind, pendaftaran mahasiswa baru, politeknik mitra industri',
            'popup' => Popup::latest('id')->first()
        ];
        return view('pages.pmb', $data);
    }

    /**
     * Display prodi page
     */
    public function prodi()
    {
        $data = [
            'page_title' => 'Program Studi - Politeknik Mitra Industri',
            'canonical_url' => 'https://polmind.ac.id/prodi',
            'meta_description' => 'Program Studi D4 Sarjana Terapan - TRM, Bisnis Digital, TRPL',
            'meta_keywords' => 'program studi, teknologi rekayasa manufaktur, bisnis digital, teknologi rekayasa perangkat lunak'
        ];
        return view('pages.prodi', $data);
    }
    

    /**
     * Display daftar_dosen page
     */
    public function daftar_dosen()
    {
        $data = [
            'page_title' => 'Daftar Dosen - Politeknik Mitra Industri',
            'canonical_url' => 'https://polmind.ac.id/daftar_dosen',
            'meta_description' => 'Daftar Dosen Internal dan Expert Industri Politeknik Mitra Industri',
            'meta_keywords' => 'daftar dosen, dosen internal, expert industri, polmind'
        ];
        return view('pages.daftar_dosen', $data);
    }


    /**
     * Display daftar_tendik page
     */
    public function daftar_tendik()
    {
        $data = [
            'page_title' => 'Daftar Tenaga Kependidikan - Politeknik Mitra Industri',
            'canonical_url' => 'https://polmind.ac.id/daftar_tendik',
            'meta_description' => 'Daftar Tenaga Kependidikan Internal dan Expert Industri Politeknik Mitra Industri',
            'meta_keywords' => 'daftar tendik, tendik internal, expert industri, polmind'
        ];
        return view('pages.daftar_tendik', $data);
    }

    /**
     * Display dokumentasi page
     */
    public function dokumentasi()
    {
        $data = [
            'page_title' => 'Dokumentasi - Politeknik Mitra Industri',
            'canonical_url' => 'https://polmind.ac.id/dokumentasi',
            'meta_description' => 'Dokumentasi Politeknik Mitra Industri - Kegiatan, Kerjasama, Fasilitas Kampus',
            'meta_keywords' => 'dokumentasi polmind, kegiatan mahasiswa, kerjasama internasional, fasilitas kampus'
        ];
        return view('pages.dokumentasi', $data);
    }

    /**
     * Handle admin login
     */
    public function handleLogin(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (\Illuminate\Support\Facades\Auth::attempt($validated, $request->boolean('remember'))) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password tidak sesuai');
    }
}
