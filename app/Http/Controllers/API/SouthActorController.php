<?php

namespace App\Http\Controllers\API;
use App\Models\SouthActor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SouthActorController extends Controller
{
    public function index()
    {
        $data = SouthActor::all();
        return response()->json(['status' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        try {
            $SouthActorData = [
                'name' => $request->input('name'),
            ];

            if (SouthActor::where('name', $SouthActorData['name'])->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'SouthActor already exists.',
                    'message' => 'Failed to add SouthActor.'
                ], 200);
            }

            $SouthActor = SouthActor::create($SouthActorData);

            return response()->json([
                'status' => true,
                'data' => $SouthActor,
                'message' => 'SouthActor Added Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to add SouthActor due to an unexpected error.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $SouthActor = SouthActor::findOrFail($id);

            $SouthActorData = [
                'name' => $request->input('name'),
            ];

            if (SouthActor::where('name', $SouthActorData['name'])->where('id', '!=', $id)->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'SouthActor with this name already exists.',
                    'message' => 'Failed to update SouthActor.'
                ], 200);
            }

            $SouthActor->update($SouthActorData);

            return response()->json([
                'status' => true,
                'data' => $SouthActor,
                'message' => 'SouthActor Updated Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update SouthActor due to an unexpected error.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $SouthActor = SouthActor::findOrFail($id);
            $SouthActor->delete();

            return response()->json([
                'status' => true,
                'message' => 'SouthActor Deleted Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to delete SouthActor due to an unexpected error.'
            ], 500);
        }
    }

    public function getMoviesBySouthActor($identifier)
{
    try {
        // Attempt to find the South actor by ID first
        $actor = SouthActor::with('movies')->find($identifier);

        // If not found by ID, attempt to find by name
        if (!$actor) {
            $actor = SouthActor::with('movies')->where('name', $identifier)->firstOrFail();
        }

        return response()->json([
            'status' => true,
            'data' => $actor,
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
