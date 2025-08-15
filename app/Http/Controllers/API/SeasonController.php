<?php

namespace App\Http\Controllers\API;

use App\Models\Season;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SeasonController extends Controller
{
    public function index()
    {
        try {
            $seasons = Season::all();
            return response()->json(['status' => true, 'data' => $seasons]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch seasons.'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $seasonData = [
                'name' => $request->input('name'),
            ];
            if (Season::where('name', $seasonData['name'])->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'Season already exists.',
                    'message' => 'Failed to add Season.'
                ], 200);
            }
            $season = Season::create($seasonData);
            return response()->json([
                'status' => true,
                'data' => $season,
                'message' => 'Season Added Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to add Season due to an unexpected error.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $season = Season::findOrFail($id);
            $seasonData = [
                'name' => $request->input('name'),
            ];
            if (Season::where('name', $seasonData['name'])->where('id', '!=', $id)->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'Season with this name already exists.',
                    'message' => 'Failed to update Season.'
                ], 200);
            }
            $season->update($seasonData);
            return response()->json([
                'status' => true,
                'data' => $season,
                'message' => 'Season Updated Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update Season due to an unexpected error.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $season = Season::findOrFail($id);
            $season->delete();

            return response()->json([
                'status' => true,
                'message' => 'Season Deleted Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to delete Season due to an unexpected error.'
            ], 500);
        }
    }
}
