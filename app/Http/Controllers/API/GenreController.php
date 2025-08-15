<?php

namespace App\Http\Controllers\API;

use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenreController extends Controller
{

    public function index()
    {
        try {
            $genres = Genre::all();
            return response()->json([
                'status' => true,
                'data' => $genres
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch genres due to an unexpected error.'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'slug' => 'required',
                'meta_title' => 'required',
                'meta_description' => 'required',
                'image' => 'nullable|image',
                'status' => 'nullable|boolean'
            ]);

            $genreData = $request->only(['name', 'slug', 'meta_title', 'meta_description', 'image', 'status']);
            $genre = Genre::create($genreData);

            return response()->json([
                'status' => true,
                'data' => $genre,
                'message' => 'Genre Added Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to add genre due to an unexpected error.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if (!$request->has('name') || $request->name === null) {
                return response()->json([
                    'status' => false,
                    'message' => 'The name field is required.'
                ], 200);
            }
            $genre = Genre::findOrFail($id);
            $genre->name = $request->name;
            $genre->save();

            return response()->json([
                'status' => true,
                'data' => $genre,
                'message' => 'Genre Updated Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update genre due to an unexpected error.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $genre = Genre::findOrFail($id);
            $genre->delete();

            return response()->json([
                'status' => true,
                'message' => 'Genre Deleted Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to delete genre due to an unexpected error.'
            ], 500);
        }
    }

    public function getMoviesByGenre($identifier)
    {
        try {
            $genre = Genre::where('slug', $identifier)->firstOrFail();
            $movies = $genre->movies()->orderBy('year', 'desc')->paginate(30); 

            return response()->json([
                'status' => true,
                'data' => [
                    'genre' => $genre,
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
