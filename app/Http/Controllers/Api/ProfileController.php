<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan data pengguna yang sedang terautentikasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        // Mengembalikan data user yang sedang login,
        // yang otomatis didapatkan dari token Sanctum.
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }

    /**
     * Memperbarui data profil pengguna.
     * (Contoh untuk update nama dan telepon)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string',
            'password' => 'sometimes|nullable|string|min:8|confirmed',
            'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk foto
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        // **Logika untuk menangani upload foto baru**
        if ($request->hasFile('photo')) {
            // 1. Hapus foto lama jika ada untuk menghemat ruang penyimpanan
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            // 2. Simpan foto baru dan dapatkan path-nya
            $path = $request->file('photo')->store('users', 'public');
            $validatedData['photo'] = $path;
        }

        // Hash password baru jika ada
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']); // Hapus dari data jika kosong
        }

        // Update data pengguna
        $user->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => $user->fresh(), // Ambil data terbaru dari user
        ]);
    }
}
