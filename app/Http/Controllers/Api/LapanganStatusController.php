<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LapanganStatus;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LapanganStatusController extends Controller
{
    public function index()
    {
        try {
            $statuses = LapanganStatus::with('lapangan')->get();
            return response()->json([
                'status' => true,
                'message' => 'Data status lapangan berhasil diambil',
                'data' => $statuses
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'lapangan_id' => 'required|exists:lapangans,id',
                'status' => 'required|in:available,unavailable',
                'date' => 'nullable|date',
                'session_ids' => 'nullable|array',
            ]);

            $status = LapanganStatus::create([
                'lapangan_id' => $request->lapangan_id,
                'status' => $request->status,
                'date' => $request->date,
                'session_ids' => $request->session_ids,
            ]);

            // Update status di tabel lapangan
            $lapangan = Lapangan::find($request->lapangan_id);
            $lapangan->status = $request->status;
            $lapangan->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Status lapangan berhasil ditambahkan',
                'data' => $status->load('lapangan')
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan status: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'lapangan_id' => 'required|exists:lapangans,id',
                'status' => 'required|in:available,unavailable',
                'date' => 'nullable|date',
                'session_ids' => 'nullable|array',
            ]);

            $status = LapanganStatus::findOrFail($id);
            $status->update([
                'lapangan_id' => $request->lapangan_id,
                'status' => $request->status,
                'date' => $request->date,
                'session_ids' => $request->session_ids,
            ]);

            // Update status di tabel lapangan
            $lapangan = Lapangan::find($request->lapangan_id);
            $lapangan->status = $request->status;
            $lapangan->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Status lapangan berhasil diupdate',
                'data' => $status->load('lapangan')
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupdate status: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $status = LapanganStatus::with('lapangan')->findOrFail($id);
            return response()->json([
                'status' => true,
                'message' => 'Data status lapangan ditemukan',
                'data' => $status
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data status tidak ditemukan',
                'data' => null
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $status = LapanganStatus::findOrFail($id);
            
            // Reset status lapangan menjadi available
            $lapangan = Lapangan::find($status->lapangan_id);
            $lapangan->status = 'available';
            $lapangan->save();

            $status->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Status lapangan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus status: ' . $e->getMessage()
            ], 500);
        }
    }
}
