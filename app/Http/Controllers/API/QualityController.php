<?php

namespace App\Http\Controllers\API;
use App\Models\Quality;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QualityController extends Controller
{
    public function index()
    {
        $Quality = Quality::all();
        return response()->json(['status' => true, 'data' => $Quality]);
    }

    public function store(Request $request)
    {
        try {
            $QualityData = [
                'name' => $request->input('name'),
            ];
            if (Quality::where('name', $QualityData['name'])->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'Quality already exists.',
                    'message' => 'Failed to add Quality.'
                ], 200);
            }
            $Quality = Quality::create($QualityData);
            return response()->json([
                'status' => true,
                'data' => $Quality,
                'message' => 'Quality Added Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to add Quality due to an unexpected error.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $Quality = Quality::findOrFail($id);
            $QualityData = [
                'name' => $request->input('name'),
            ];
            if (Quality::where('name', $QualityData['name'])->where('id', '!=', $id)->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'Quality with this name already exists.',
                    'message' => 'Failed to update Quality.'
                ], 200);
            }
            $Quality->update($QualityData);
            return response()->json([
                'status' => true,
                'data' => $Quality,
                'message' => 'Quality Updated Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update Quality due to an unexpected error.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $Quality = Quality::findOrFail($id);
            $Quality->delete();

            return response()->json([
                'status' => true,
                'message' => 'Quality Deleted Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to delete Quality due to an unexpected error.'
            ], 500);
        }
    }
}
