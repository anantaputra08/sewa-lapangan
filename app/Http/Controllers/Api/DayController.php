<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Day;
use Illuminate\Http\Request;
use Exception;

class DayController extends Controller
{
    public function index()
    {
        try {
            $days = Day::all();
            if ($days->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data hari tidak ditemukan',
                    'data' => []
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengambil data hari',
                'data' => $days
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
                'name' => 'required|unique:days,name'
            ], [
                'name.required' => 'Nama hari wajib diisi',
                'name.unique' => 'Nama hari sudah ada'
            ]);

            $day = Day::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambah hari',
                'data' => $day
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
            $day = Day::find($id);
            if (!$day) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data hari tidak ditemukan',
                    'data' => null
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengambil detail hari',
                'data' => $day
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
            $day = Day::find($id);
            if (!$day) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data hari tidak ditemukan',
                    'data' => null
                ], 404);
            }

            $request->validate([
                'name' => 'required|unique:days,name,' . $id
            ], [
                'name.required' => 'Nama hari wajib diisi',
                'name.unique' => 'Nama hari sudah ada'
            ]);

            $day->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengupdate hari',
                'data' => $day
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
            $day = Day::find($id);
            if (!$day) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data hari tidak ditemukan'
                ], 404);
            }

            $day->delete();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus hari'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
