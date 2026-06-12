<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pendaftaran_Mahasiswa_Baru;

class PendaftaranMahasiswaBaruController extends Controller
{
    public function publicIndex()
    {
        // Ambil semua program sarjana terapan, urutkan berdasarkan id dari kecil ke besar
        $pmb = Pendaftaran_Mahasiswa_Baru::get();

        return view('pages.pmb', compact('pmb'));
    }

    public function index()
    {
        $items = Pendaftaran_Mahasiswa_Baru::orderBy('id', 'desc')->get();
        return view('admin.pendaftaran_mahasiswa_baru.views_pendaftaran_mahasiswa_baru', compact('items'));
    }

    public function create()
    {
        return view('admin.pendaftaran_mahasiswa_baru.form_pendaftaran_mahasiswa_baru', [
            'action' => route('admin.pendaftaran-mahasiswa-baru.store'),
            'method' => 'POST',
            'content' => [],
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'deskripsi' => 'required|string',
            'gelombang' => 'nullable|array',
            'gelombang.*.nama_gelombang' => 'nullable|string',
            'gelombang.*.jadwal_pendaftaran' => 'nullable|string',
            'gelombang.*.jadwal_ujian' => 'nullable|string',
            'gelombang.*.jadwal_pengumuman' => 'nullable|string',
            'gelombang.*.jadwal_daftar_ulang' => 'nullable|string',
            'gelombang.*.biaya' => 'nullable|array',
            'gelombang.*.biaya.*.nama_biaya' => 'nullable|string',
            'gelombang.*.biaya.*.nominal' => 'nullable|numeric|min:0',
            'gelombang.*.biaya.*.has_diskon' => 'nullable|boolean',
            'gelombang.*.biaya.*.harga_diskon' => 'nullable|numeric|min:0',
            'gelombang.*.biaya.*.periode_bayar' => 'nullable|string',
            'gelombang.*.biaya.*.alasan_diskon' => 'nullable|string',
            'persyaratan_administrasi' => 'nullable|string',
            'kata_penutup' => 'nullable|string',
            'link_daftar' => 'nullable|string',
            'kalimat_bantuan' => 'nullable|string',
        ]);

        $validator->after(function ($validator) use ($request) {
            $gelombangs = $request->input('gelombang', []);
            foreach ($gelombangs as $gIndex => $gelombang) {
                if (!isset($gelombang['biaya']) || !is_array($gelombang['biaya'])) {
                    continue;
                }
                foreach ($gelombang['biaya'] as $bIndex => $biaya) {
                    $nominal = isset($biaya['nominal']) ? floatval(str_replace([',', ' '], ['', ''], $biaya['nominal'])) : null;
                    $diskon = isset($biaya['harga_diskon']) ? floatval(str_replace([',', ' '], ['', ''], $biaya['harga_diskon'])) : null;
                    if ($diskon !== null && $nominal !== null && $diskon > $nominal) {
                        $validator->errors()->add(
                            "gelombang.$gIndex.biaya.$bIndex.harga_diskon",
                            'Harga diskon tidak boleh lebih besar dari nominal.'
                        );
                    }
                }
            }
        });

        $validated = $validator->validate();

        $content = [
            'title' => $validated['title'],
            'deskripsi' => $validated['deskripsi'],
            'gelombang' => $validated['gelombang'] ?? [],
            'persyaratan_administrasi' => $validated['persyaratan_administrasi'] ?? '',
            'kata_penutup' => $validated['kata_penutup'] ?? '',
            'link_daftar' => $validated['link_daftar'] ?? '',
            'kalimat_bantuan' => $validated['kalimat_bantuan'] ?? '',
        ];

        Pendaftaran_Mahasiswa_Baru::create([
            'content' => $content,
        ]);

        return redirect()->route('admin.pendaftaran-mahasiswa-baru.index')->with('success_key', 'pendaftaran.flashCreated');
    }

    public function edit($id)
    {
        $item = Pendaftaran_Mahasiswa_Baru::findOrFail($id);
        $content = $item->content ?? [];
        return view('admin.pendaftaran_mahasiswa_baru.form_pendaftaran_mahasiswa_baru', [
            'action' => route('admin.pendaftaran-mahasiswa-baru.update', $item->id),
            'method' => 'PUT',
            'content' => $content,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'deskripsi' => 'required|string',
            'gelombang' => 'nullable|array',
            'gelombang.*.nama_gelombang' => 'nullable|string',
            'gelombang.*.jadwal_pendaftaran' => 'nullable|string',
            'gelombang.*.jadwal_ujian' => 'nullable|string',
            'gelombang.*.jadwal_pengumuman' => 'nullable|string',
            'gelombang.*.jadwal_daftar_ulang' => 'nullable|string',
            'gelombang.*.biaya' => 'nullable|array',
            'gelombang.*.biaya.*.nama_biaya' => 'nullable|string',
            'gelombang.*.biaya.*.nominal' => 'nullable|numeric|min:0',
            'gelombang.*.biaya.*.has_diskon' => 'nullable|boolean',
            'gelombang.*.biaya.*.harga_diskon' => 'nullable|numeric|min:0',
            'gelombang.*.biaya.*.periode_bayar' => 'nullable|string',
            'gelombang.*.biaya.*.alasan_diskon' => 'nullable|string',
            'persyaratan_administrasi' => 'nullable|string',
            'kata_penutup' => 'nullable|string',
            'link_daftar' => 'nullable|string',
            'kalimat_bantuan' => 'nullable|string',
        ]);

        $validator->after(function ($validator) use ($request) {
            $gelombangs = $request->input('gelombang', []);
            foreach ($gelombangs as $gIndex => $gelombang) {
                if (!isset($gelombang['biaya']) || !is_array($gelombang['biaya'])) {
                    continue;
                }
                foreach ($gelombang['biaya'] as $bIndex => $biaya) {
                    $nominal = isset($biaya['nominal']) ? floatval(str_replace([',', ' '], ['', ''], $biaya['nominal'])) : null;
                    $diskon = isset($biaya['harga_diskon']) ? floatval(str_replace([',', ' '], ['', ''], $biaya['harga_diskon'])) : null;
                    if ($diskon !== null && $nominal !== null && $diskon > $nominal) {
                        $validator->errors()->add(
                            "gelombang.$gIndex.biaya.$bIndex.harga_diskon",
                            'Harga diskon tidak boleh lebih besar dari nominal.'
                        );
                    }
                }
            }
        });

        $validated = $validator->validate();

        $content = [
            'title' => $validated['title'],
            'deskripsi' => $validated['deskripsi'],
            'gelombang' => $validated['gelombang'] ?? [],
            'persyaratan_administrasi' => $validated['persyaratan_administrasi'] ?? '',
            'kata_penutup' => $validated['kata_penutup'] ?? '',
            'link_daftar' => $validated['link_daftar'] ?? '',
            'kalimat_bantuan' => $validated['kalimat_bantuan'] ?? '',
        ];

        $item = Pendaftaran_Mahasiswa_Baru::findOrFail($id);
        $item->update(['content' => $content]);

        return redirect()->route('admin.pendaftaran-mahasiswa-baru.index') ->with('success_key', 'pendaftaran.flashUpdated');
    }

    public function destroy($id)
    {
        $item = Pendaftaran_Mahasiswa_Baru::findOrFail($id);
        $item->delete();
        return redirect()->route('admin.pendaftaran-mahasiswa-baru.index')->with('success_key', 'pendaftaran.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:pendaftaran_mahasiswa_baru,id',
        ]);

        Pendaftaran_Mahasiswa_Baru::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.pendaftaran-mahasiswa-baru.index')
            ->with('success_key', 'pendaftaran.flashBulkDeleted');
    }
}
