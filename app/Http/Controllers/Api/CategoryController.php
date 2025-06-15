<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar semua kategori.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $categories = Category::all();

            if ($categories->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'No categories found',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'status' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            // Log::error('Failed to retrieve categories: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan kategori yang baru dibuat ke dalam database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category = Category::create($request->only(['name', 'description']));

            return response()->json([
                'status' => true,
                'message' => 'Category created successfully',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            // Log::error('Failed to create category: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to create category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan satu kategori spesifik.
     * Route-model binding akan secara otomatis menangani kasus "tidak ditemukan".
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        return response()->json([
            'status' => true,
            'message' => 'Category retrieved successfully',
            'data' => $category
        ], 200);
    }

    /**
     * Memperbarui kategori yang ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Category $category)
    {
        // Validasi data yang masuk
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id),
            ],
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category->update($request->only(['name', 'description']));

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully',
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            // Log::error('Failed to update category: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to update category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus kategori dari database.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return response()->json([
                'status' => true,
                'message' => 'Category deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            // Log::error('Failed to delete category: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete category',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
