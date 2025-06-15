<?php

namespace App\Http\Controllers;

use App\Models\SessionHour;
use Exception;
use Illuminate\Http\Request;

class SessionHourController extends Controller
{
    /**
     * Menampilkan halaman daftar semua jam sesi.
     */
    public function index()
    {
        $sessionHours = SessionHour::latest()->paginate(10);
        return view('session_hours.index', compact('sessionHours'));
    }

    /**
     * Menampilkan form untuk membuat jam sesi baru.
     */
    public function create()
    {
        return view('session_hours.create');
    }

    /**
     * Menyimpan jam sesi baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string|max:255',
        ]);

        SessionHour::create($request->all());

        return redirect()->route('session-hours.index')
            ->with('success', 'Jam sesi berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit jam sesi.
     */
    public function edit(SessionHour $sessionHour)
    {
        return view('session_hours.edit', compact('sessionHour'));
    }

    /**
     * Memperbarui data jam sesi di database.
     */
    public function update(Request $request, SessionHour $sessionHour)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string|max:255',
        ]);

        $sessionHour->update($request->all());

        return redirect()->route('session-hours.index')
            ->with('success', 'Jam sesi berhasil diperbarui.');
    }

    /**
     * Menghapus jam sesi dari database.
     */
    public function destroy(SessionHour $sessionHour)
    {
        try {
            $sessionHour->delete();
            return redirect()->route('session-hours.index')
                ->with('success', 'Jam sesi berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->route('session-hours.index')
                ->with('error', 'Gagal menghapus jam sesi. Pesan: ' . $e->getMessage());
        }
    }
}
