<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::all();
        return response()->json(['status' => true, 'data' => $complaints]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'issue' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'detail' => 'required|string',
            'movieTitle' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        try {
            $complaint = Complaint::create($request->all());
            return response()->json(['status' => true, 'data' => $complaint, 'message' => 'Complaint Added Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage(), 'message' => 'Failed to add complaint due to an unexpected error.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $complaint = Complaint::findOrFail($id);
            return response()->json(['status' => true, 'data' => $complaint]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage(), 'message' => 'Complaint not found.'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'issue' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'detail' => 'required|string',
            'movieTitle' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        try {
            $complaint = Complaint::findOrFail($id);
            $complaint->update($request->all());
            return response()->json(['status' => true, 'data' => $complaint, 'message' => 'Complaint Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage(), 'message' => 'Failed to update complaint due to an unexpected error.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $complaint = Complaint::findOrFail($id);
            $complaint->delete();
            return response()->json(['status' => true, 'message' => 'Complaint Deleted Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'error' => $e->getMessage(), 'message' => 'Failed to delete complaint due to an unexpected error.'], 500);
        }
    }
}
