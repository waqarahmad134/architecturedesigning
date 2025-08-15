<?php

namespace App\Http\Controllers\API;
use App\Models\Actress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActressController extends Controller
{
    public function index()
    {
        $categories = Actress::all();
        return response()->json(['status' => true, 'data' => $categories]);
    }

    public function store(Request $request)
    {
        try {
            $ActressData = [
                'name' => $request->input('name'),
            ];

            if (Actress::where('name', $ActressData['name'])->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'Actress already exists.',
                    'message' => 'Failed to add Actress.'
                ], 200);
            }

            $Actress = Actress::create($ActressData);

            return response()->json([
                'status' => true,
                'data' => $Actress,
                'message' => 'Actress Added Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to add Actress due to an unexpected error.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $Actress = Actress::findOrFail($id);

            $ActressData = [
                'name' => $request->input('name'),
            ];

            if (Actress::where('name', $ActressData['name'])->where('id', '!=', $id)->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'Actress with this name already exists.',
                    'message' => 'Failed to update Actress.'
                ], 200);
            }

            $Actress->update($ActressData);

            return response()->json([
                'status' => true,
                'data' => $Actress,
                'message' => 'Actress Updated Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update Actress due to an unexpected error.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $Actress = Actress::findOrFail($id);
            $Actress->delete();

            return response()->json([
                'status' => true,
                'message' => 'Actress Deleted Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to delete Actress due to an unexpected error.'
            ], 500);
        }
    }

    public function getMoviesByActress($identifier)
    {
        try {
            // Attempt to find the actress by ID first
            $actress = Actress::with('movies')->find($identifier);
    
            // If not found by ID, attempt to find by name
            if (!$actress) {
                $actress = Actress::with('movies')->where('name', $identifier)->firstOrFail();
            }
    
            return response()->json([
                'status' => true,
                'data' => $actress,
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
