<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LapanganController extends Controller
{
    /**
     * Menampilkan halaman daftar semua lapangan.
     */
    public function index()
    {
        $lapangans = Lapangan::with('category')->latest()->paginate(10);
        return view('lapangans.index', compact('lapangans'));
    }

    /**
     * Menampilkan form untuk membuat lapangan baru.
     */
    public function create()
    {
        $categories = Category::all();
        return view('lapangans.create', compact('categories'));
    }

    /**
     * Menyimpan lapangan baru ke database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:lapangans',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,unavailable',
        ]);

        if ($request->hasFile('photo')) {
            // Simpan foto di disk 'public' dalam folder 'lapangans' dan simpan path relatifnya.
            $data['photo'] = $request->file('photo')->store('lapangans', 'public');
        }

        Lapangan::create($data);

        return redirect()->route('lapangans.index')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit lapangan.
     */
    public function edit(Lapangan $lapangan)
    {
        $categories = Category::all();
        return view('lapangans.edit', compact('lapangan', 'categories'));
    }

    /**
     * Memperbarui data lapangan di database.
     */
    public function update(Request $request, Lapangan $lapangan)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('lapangans')->ignore($lapangan->id)],
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,unavailable',
        ]);

        if ($request->hasFile('photo')) {
            // Hapus foto lama dari storage jika ada.
            if ($lapangan->photo) {
                Storage::disk('public')->delete($lapangan->photo);
            }
            // Simpan foto baru dan dapatkan path relatifnya.
            $data['photo'] = $request->file('photo')->store('lapangans', 'public');
        }

        $lapangan->update($data);

        return redirect()->route('lapangans.index')
            ->with('success', 'Lapangan berhasil diperbarui.');
    }

    /**
     * Menghapus lapangan dari database.
     */
    public function destroy(Lapangan $lapangan)
    {
        try {
            // Hapus foto dari storage jika ada
            if ($lapangan->photo) {
                Storage::disk('public')->delete($lapangan->photo);
            }
            $lapangan->delete();
            return redirect()->route('lapangans.index')
                ->with('success', 'Lapangan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('lapangans.index')
                ->with('error', 'Gagal menghapus lapangan.');
        }
    }
}
