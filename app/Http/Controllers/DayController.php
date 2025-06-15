<?php

namespace App\Http\Controllers;

use App\Models\Day;
use Exception;
use Illuminate\Http\Request;

class DayController extends Controller
{
    /**
     * Menampilkan halaman daftar semua hari.
     */
    public function index()
    {
        $days = Day::latest()->paginate(10);
        return view('days.index', compact('days'));
    }

    /**
     * Menampilkan form untuk membuat hari baru.
     */
    public function create()
    {
        return view('days.create');
    }

    /**
     * Menyimpan hari baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:days,name'
        ], [
            'name.required' => 'Nama hari wajib diisi.',
            'name.unique' => 'Nama hari sudah ada.'
        ]);

        Day::create($request->only('name'));

        return redirect()->route('days.index')
            ->with('success', 'Hari berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit hari.
     */
    public function edit(Day $day)
    {
        return view('days.edit', compact('day'));
    }

    /**
     * Memperbarui data hari di database.
     */
    public function update(Request $request, Day $day)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:days,name,' . $day->id
        ], [
            'name.required' => 'Nama hari wajib diisi.',
            'name.unique' => 'Nama hari sudah ada.'
        ]);

        $day->update($request->only('name'));

        return redirect()->route('days.index')
            ->with('success', 'Hari berhasil diperbarui.');
    }

    /**
     * Menghapus hari dari database.
     */
    public function destroy(Day $day)
    {
        try {
            // Periksa jika ada relasi sebelum menghapus (jika diperlukan)
            // if ($day->sessionHours()->exists()) {
            //     return redirect()->route('days.index')
            //                      ->with('error', 'Gagal menghapus! Hari ini memiliki sesi terkait.');
            // }

            $day->delete();
            return redirect()->route('days.index')
                ->with('success', 'Hari berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->route('days.index')
                ->with('error', 'Gagal menghapus hari. Pesan: ' . $e->getMessage());
        }
    }
}
