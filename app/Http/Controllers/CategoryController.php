<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Menampilkan halaman daftar semua kategori.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil semua data kategori dari database
        $categories = Category::latest()->paginate(10); // Mengambil data terbaru & paginasi

        // Mengembalikan view 'categories.index' dan mengirimkan data categories
        return view('categories.index', compact('categories'));
    }

    /**
     * Menampilkan form untuk membuat kategori baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Cukup tampilkan view dengan form
        return view('categories.create');
    }

    /**
     * Menyimpan kategori baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        // Buat kategori baru
        Category::create($request->only(['name', 'description']));

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Menampilkan halaman detail satu kategori (opsional untuk web).
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\View\View
     */
    public function show(Category $category)
    {
        // Menampilkan view 'categories.show' dengan data kategori spesifik
        return view('categories.show', compact('category'));
    }

    /**
     * Menampilkan form untuk mengedit kategori.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\View\View
     */
    public function edit(Category $category)
    {
        // Menampilkan view 'categories.edit' dengan data kategori yang akan diedit
        return view('categories.edit', compact('category'));
    }

    /**
     * Memperbarui kategori di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Category $category)
    {
        // Validasi input
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id),
            ],
            'description' => 'nullable|string',
        ]);

        // Update kategori
        $category->update($request->only(['name', 'description']));

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus kategori dari database.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('categories.index')
                             ->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            // Jika terjadi error (misal karena foreign key constraint)
            return redirect()->route('categories.index')
                             ->with('error', 'Gagal menghapus kategori. Mungkin kategori ini sedang digunakan.');
        }
    }
}
