<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MovieRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieRequestController extends Controller
{
    public function index()
    {
        $movieRequests = MovieRequest::all();
        return response()->json(['status' => true, 'data' => $movieRequests]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'movieTitle' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        try {
            $movieRequest = MovieRequest::create($request->all());
            return response()->json(['status' => true, 'data' => $movieRequest, 'message' => 'Movie Request Added Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage(), 'message' => 'Failed to add movie request due to an unexpected error.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $movieRequest = MovieRequest::findOrFail($id);
            return response()->json(['status' => true, 'data' => $movieRequest]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage(), 'message' => 'Movie Request not found.'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'movieTitle' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        try {
            $movieRequest = MovieRequest::findOrFail($id);
            $movieRequest->update($request->all());
            return response()->json(['status' => true, 'data' => $movieRequest, 'message' => 'Movie Request Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage(), 'message' => 'Failed to update movie request due to an unexpected error.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $movieRequest = MovieRequest::findOrFail($id);
            $movieRequest->delete();
            return response()->json(['status' => true, 'message' => 'Movie Request Deleted Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage(), 'message' => 'Failed to delete movie request due to an unexpected error.'], 500);
        }
    }
}
