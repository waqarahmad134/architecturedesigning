<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'error' => 'The provided credentials are incorrect.',
                    'message' => 'The provided credentials are incorrect'
                ], 200);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'user' => $user,
                ],
                'error' => null,
                'message' => 'Login successful.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'error' => $e->getMessage(),
                'message' => 'An unexpected error occurred during login.'
            ], 200);
        }
    }

    public function index()
    {
        $users = User::all();
        return response()->json(['status' => true, 'data' => $users]);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 200);
            }

            $userData = [
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role' => $request->input('role'),
            ];

            $user = User::create($userData);

            return response()->json([
                'status' => true,
                'data' => $user,
                'message' => 'User Created Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to create user due to an unexpected error.'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'status' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch user.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8',
            ]);
    
            $user = User::findOrFail($id);
            $userData = $request->only(['first_name', 'last_name', 'email']);
    
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->input('password'));
            }

            $user->update($userData);
            return response()->json([
                'status' => true,
                'data' => $user,
                'message' => 'Admin Updated Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update user due to an unexpected error.'
            ], 200);
        }
    }
    
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'User Deleted Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to delete user due to an unexpected error.'
            ], 500);
        }
    }

}
