<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SessionHour;
use Illuminate\Http\Request;
use Exception;

class SessionHourController extends Controller
{
    public function index()
    {
        try {
            $sessions = SessionHour::with('day')->get();

            if ($sessions->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data sesi kosong',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengambil data sesi',
                'data' => $sessions
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'day_id' => 'required|exists:days,id',
                'start_time' => 'required',
                'end_time' => 'required',
                'description' => 'nullable',
            ]);

            $session = SessionHour::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambah sesi',
                'data' => $session
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $session = SessionHour::with('day')->find($id);
            if (!$session) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data sesi tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengambil detail sesi',
                'data' => $session
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $session = SessionHour::find($id);
            if (!$session) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data sesi tidak ditemukan',
                ], 404);
            }

            $request->validate([
                'day_id' => 'required|exists:days,id',
                'start_time' => 'required',
                'end_time' => 'required',
                'description' => 'nullable',
            ]);

            $session->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengupdate sesi',
                'data' => $session
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $session = SessionHour::find($id);
            if (!$session) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data sesi tidak ditemukan',
                ], 404);
            }

            $session->delete();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus sesi'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
