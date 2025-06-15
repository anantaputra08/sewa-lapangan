<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LapanganController extends Controller
{
    /**
     * Menampilkan daftar semua lapangan.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $lapangans = Lapangan::with('category')->get();

            if ($lapangans->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'No lapangans found',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'status' => true,
                'message' => 'Lapangans retrieved successfully',
                'data' => $lapangans
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve lapangans',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan lapangan yang baru dibuat ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:lapangans',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,unavailable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except('photo');
            
            // Handle upload foto jika ada
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('public/photos/lapangans');
                $data['photo'] = Storage::url($path);
            }

            $lapangan = Lapangan::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Lapangan created successfully',
                'data' => $lapangan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create lapangan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan satu lapangan spesifik.
     *
     * @param  \App\Models\Lapangan  $lapangan
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Lapangan $lapangan)
    {
        $lapangan->load('category');

        return response()->json([
            'status' => true,
            'message' => 'Lapangan retrieved successfully',
            'data' => $lapangan
        ], 200);
    }

    /**
     * Memperbarui lapangan yang ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lapangan  $lapangan
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Lapangan $lapangan)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('lapangans')->ignore($lapangan->id),
            ],
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,unavailable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except('photo');

            // Handle upload foto baru jika ada
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($lapangan->photo) {
                    Storage::delete(str_replace('/storage', 'public', $lapangan->photo));
                }
                
                $path = $request->file('photo')->store('public/photos');
                $data['photo'] = Storage::url($path);
            }

            $lapangan->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Lapangan updated successfully',
                'data' => $lapangan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update lapangan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus lapangan dari database.
     *
     * @param  \App\Models\Lapangan  $lapangan
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Lapangan $lapangan)
    {
        try {
            // Hapus foto dari storage sebelum menghapus record
            if ($lapangan->photo) {
                // Mengubah URL kembali menjadi path storage
                // contoh: dari /storage/photos/file.jpg menjadi public/photos/file.jpg
                $photoPath = str_replace('/storage', 'public', $lapangan->photo);
                Storage::delete($photoPath);
            }

            $lapangan->delete();

            return response()->json([
                'status' => true,
                'message' => 'Lapangan deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete lapangan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
