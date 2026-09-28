<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkirKendaraan;
use Illuminate\Http\Request;

class ParkirKendaraanController extends Controller
{
    public function index()
    {
        $kendaraan = ParkirKendaraan::orderBy('id_kendaraan', 'desc')->get();

        return view('admin.kendaraan.index', compact('kendaraan'));
    }

    public function create()
    {
        return view('admin.kendaraan.form', [
            'kendaraan' => new ParkirKendaraan,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plat_nomor' => 'required|max:20|unique:tb_kendaraan,plat_nomor',
            'jenis_kendaraan' => 'required',
            'nama_pemilik' => 'required|max:100',
        ]);

        // SIMPAN PERMANEN KE MYSQL
        ParkirKendaraan::create($validated);

        return redirect()
            ->route('admin.kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(ParkirKendaraan $kendaraan)
    {
        return view('admin.kendaraan.form', compact('kendaraan'));
    }

    public function update(Request $request, ParkirKendaraan $kendaraan)
    {
        $validated = $request->validate([
            'plat_nomor' => 'required|max:20|unique:tb_kendaraan,plat_nomor,'.$kendaraan->id_kendaraan.',id_kendaraan',
            'jenis_kendaraan' => 'required',
            'nama_pemilik' => 'required|max:100',
        ]);

        $kendaraan->update($validated);

        return redirect()
            ->route('admin.kendaraan.index')
            ->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(ParkirKendaraan $kendaraan)
    {
        $kendaraan->delete();

        return redirect()
            ->route('admin.kendaraan.index')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }
}
