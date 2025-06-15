<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\LapanganStatus;
use App\Models\SessionHour;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LapanganStatusController extends Controller
{
    /**
     * Menampilkan daftar status lapangan.
     */
    public function index()
    {
        $statuses = LapanganStatus::with('lapangan')->latest()->paginate(10);
        return view('lapangan_status.index', compact('statuses'));
    }

    /**
     * Menampilkan form untuk membuat status baru.
     */
    public function create()
    {
        $lapangans = Lapangan::all();
        $sessionHours = SessionHour::all();
        return view('lapangan_status.create', compact('lapangans', 'sessionHours'));
    }

    /**
     * Menyimpan status baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'status' => 'required|in:available,unavailable',
            'date' => 'nullable|date',
            'session_ids' => 'nullable|array',
            'session_ids.*' => 'exists:session_hours,id', // Validasi setiap item dalam array
        ]);

        DB::beginTransaction();
        try {
            LapanganStatus::create([
                'lapangan_id' => $request->lapangan_id,
                'status' => $request->status,
                'date' => $request->date,
                'session_ids' => $request->session_ids,
            ]);

            // Update status utama di tabel lapangan jika statusnya 'unavailable'
            if ($request->status == 'unavailable') {
                $lapangan = Lapangan::find($request->lapangan_id);
                $lapangan->status = 'unavailable';
                $lapangan->save();
            }

            DB::commit();
            return redirect()->route('lapangan-status.index')->with('success', 'Status lapangan berhasil ditambahkan.');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan status: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit status.
     */
    public function edit(LapanganStatus $lapanganStatus)
    {
        $lapangans = Lapangan::all();
        $sessionHours = SessionHour::all();
        // Pastikan session_ids adalah array
        $selectedSessions = is_array($lapanganStatus->session_ids) ? $lapanganStatus->session_ids : [];

        return view('lapangan_status.edit', compact('lapanganStatus', 'lapangans', 'sessionHours', 'selectedSessions'));
    }


    /**
     * Memperbarui status di database.
     */
    public function update(Request $request, LapanganStatus $lapanganStatus)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'status' => 'required|in:available,unavailable',
            'date' => 'nullable|date',
            'session_ids' => 'nullable|array',
            'session_ids.*' => 'exists:session_hours,id',
        ]);

        DB::beginTransaction();
        try {
            $lapanganStatus->update([
                'lapangan_id' => $request->lapangan_id,
                'status' => $request->status,
                'date' => $request->date,
                'session_ids' => $request->session_ids,
            ]);

            // Logika untuk update status utama lapangan
            $lapangan = Lapangan::find($request->lapangan_id);
            if ($request->status == 'unavailable') {
                $lapangan->status = 'unavailable';
            } else {
                // Jika diubah kembali ke 'available', periksa apakah ada status 'unavailable' lain untuk lapangan ini
                $otherUnavailable = LapanganStatus::where('lapangan_id', $request->lapangan_id)
                    ->where('status', 'unavailable')
                    ->exists();
                if (!$otherUnavailable) {
                    $lapangan->status = 'available';
                }
            }
            $lapangan->save();

            DB::commit();
            return redirect()->route('lapangan-status.index')->with('success', 'Status lapangan berhasil diperbarui.');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus status lapangan.
     */
    public function destroy(LapanganStatus $lapanganStatus)
    {
        DB::beginTransaction();
        try {
            $lapanganId = $lapanganStatus->lapangan_id;
            $lapanganStatus->delete();

            // Periksa apakah masih ada status 'unavailable' lain untuk lapangan ini
            $isStillUnavailable = LapanganStatus::where('lapangan_id', $lapanganId)
                ->where('status', 'unavailable')
                ->exists();

            // Jika tidak ada lagi, set status utama lapangan menjadi 'available'
            if (!$isStillUnavailable) {
                $lapangan = Lapangan::find($lapanganId);
                $lapangan->status = 'available';
                $lapangan->save();
            }

            DB::commit();
            return redirect()->route('lapangan-status.index')->with('success', 'Status lapangan berhasil dihapus.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('lapangan-status.index')->with('error', 'Gagal menghapus status: ' . $e->getMessage());
        }
    }
}
