<?php

namespace App\Http\Controllers\API;

use App\Models\Actor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActorController extends Controller
{
    public function index()
    {
        $actors = Actor::all();
        return response()->json(['status' => true, 'data' => $actors]);
    }

    public function store(Request $request)
    {
        try {
            $actorData = [
                'name' => $request->input('name'),
            ];
            if (Actor::where('name', $actorData['name'])->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'Actor already exists.',
                    'message' => 'Failed to add actor.'
                ], 200);
            }
            $actor = Actor::create($actorData);
            return response()->json([
                'status' => true,
                'data' => $actor,
                'message' => 'Actor Added Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to add actor due to an unexpected error.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $actor = Actor::findOrFail($id);
            $actorData = [
                'name' => $request->input('name'),
            ];
            if (Actor::where('name', $actorData['name'])->where('id', '!=', $id)->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'Actor with this name already exists.',
                    'message' => 'Failed to update actor.'
                ], 200);
            }
            $actor->update($actorData);
            return response()->json([
                'status' => true,
                'data' => $actor,
                'message' => 'Actor Updated Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update actor due to an unexpected error.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $actor = Actor::findOrFail($id);
            $actor->delete();

            return response()->json([
                'status' => true,
                'message' => 'Actor Deleted Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to delete actor due to an unexpected error.'
            ], 500);
        }
    }

    public function getMoviesByActors($identifier)
    {
        try {
            // Attempt to find the actor by ID first
            $actor = Actor::with('movies')->find($identifier);

            // If not found by ID, attempt to find by name
            if (!$actor) {
                $actor = Actor::with('movies')->where('name', $identifier)->firstOrFail();
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
