<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Goutte\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json(['status' => true, 'data' => $categories]);
    }

    public function store(Request $request)
    {
        try {
            $categoryData = [
                'name' => $request->input('name'),
            ];

            if (Category::where('name', $categoryData['name'])->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'Category already exists.',
                    'message' => 'Failed to add category.'
                ], 200);
            }

            $category = Category::create($categoryData);

            return response()->json([
                'status' => true,
                'data' => $category,
                'message' => 'Category Added Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to add category due to an unexpected error.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $categoryData = [
                'name' => $request->input('name'),
            ];

            if (Category::where('name', $categoryData['name'])->where('id', '!=', $id)->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'Category with this name already exists.',
                    'message' => 'Failed to update category.'
                ], 200);
            }

            $category->update($categoryData);

            return response()->json([
                'status' => true,
                'data' => $category,
                'message' => 'Category Updated Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update category due to an unexpected error.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'status' => true,
                'message' => 'Category Deleted Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to delete category due to an unexpected error.'
            ], 500);
        }
    }

    public function getMoviesByCategory($identifier)
    {
        try {
            $category = Category::where('name', $identifier)->firstOrFail();
            $movies = $category->movies()->orderBy('year', 'desc')->paginate(30);
            return response()->json([
                'status' => true,
                'data' => [
                    'category' => $category,
                    'movies' => $movies
                ],
                'message' => 'Movies fetched successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch movies due to an unexpected error.'
            ], 500);
        }
    }


   


}
