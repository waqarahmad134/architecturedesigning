<?php

namespace App\Http\Controllers\API;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function index()
    {
        $Tag = Tag::all();
        return response()->json(['status' => true, 'data' => $Tag]);
    }

    public function store(Request $request)
    {
        try {
            $TagData = [
                'name' => $request->input('name'),
            ];
            if (Tag::where('name', $TagData['name'])->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'Tag already exists.',
                    'message' => 'Failed to add Tag.'
                ], 200);
            }
            $Tag = Tag::create($TagData);
            return response()->json([
                'status' => true,
                'data' => $Tag,
                'message' => 'Tag Added Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to add Tag due to an unexpected error.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $Tag = Tag::findOrFail($id);
            $TagData = [
                'name' => $request->input('name'),
            ];
            if (Tag::where('name', $TagData['name'])->where('id', '!=', $id)->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'Tag with this name already exists.',
                    'message' => 'Failed to update Tag.'
                ], 200);
            }
            $Tag->update($TagData);
            return response()->json([
                'status' => true,
                'data' => $Tag,
                'message' => 'Tag Updated Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update Tag due to an unexpected error.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $Tag = Tag::findOrFail($id);
            $Tag->delete();

            return response()->json([
                'status' => true,
                'message' => 'Tag Deleted Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to delete Tag due to an unexpected error.'
            ], 500);
        }
    }
}
