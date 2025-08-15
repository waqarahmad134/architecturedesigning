<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MovieManagement;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function saveFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }
        
        $title = str_ireplace('c:', '', $request->title);
        
        if (str_starts_with(strtolower($request->title), 'c:')) {
            $newEntry = MovieManagement::create([
                'title' => $title,
                'status' => $request->status,
                'youtube' => $request->youtube ?? false,
                'rumble' => $request->rumble ?? false,
                'storyfire' => $request->storyfire ?? false,
                'abyss' => $request->abyss ?? false,
                'vidhide' => $request->vidhide ?? false,
                'streamwish' => $request->streamwish ?? false,
                'vidguard' => $request->vidguard ?? false,
                'uploadFrom' => $request->uploadFrom,
            ]);
    
            return response()->json(['message' => 'Data saved successfully!', 'data' => $newEntry], 200);
        }
    
        $existingMovies = MovieManagement::all();

        $highestMatch = 0;
        $matchedMovie = null;

        foreach ($existingMovies as $movie) {
            similar_text(strtolower($title), strtolower($movie->title), $percent);
    
            // Check if the match percentage is higher than the previous highest
            if ($percent > $highestMatch) {
                $highestMatch = $percent;
                $matchedMovie = $movie;
            }
        }

        if ($highestMatch >= 80) {  // You can adjust the threshold percentage
            return response()->json(['error' => 'Duplicate entry found: ' . $matchedMovie->title . ' (' . $highestMatch . '% match)'], 200);
        }
        

        $newEntry = MovieManagement::create([
            'title' => $title,
            'status' => $request->status,
            'youtube' => $request->youtube ?? false,
            'rumble' => $request->rumble ?? false,
            'storyfire' => $request->storyfire ?? false,
            'abyss' => $request->abyss ?? false,
            'vidhide' => $request->vidhide ?? false,
            'streamwish' => $request->streamwish ?? false,
            'vidguard' => $request->vidguard ?? false,
            'uploadFrom' => $request->uploadFrom,
        ]);

        return response()->json(['message' => 'Data saved successfully!', 'data' => $newEntry], 200);
    }

    public function getFile()
    {
        $movies = MovieManagement::all(); 
        return response()->json($movies, 200);
    }

    public function updateFile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        $movie = MovieManagement::find($id);
    
        if (!$movie) {
            return response()->json(['error' => 'Entry not found'], 404);
        }
    
        $movieData = $request->only([
            'title', 'status', 'youtube', 'rumble', 'storyfire', 'abyss', 'vidhide', 'streamwish' , 'vidguard' , 'uploadFrom'
        ]);
    
        foreach ($movieData as $key => $value) {
            if ($value !== null) {
                $movie->$key = $value;
            }
        }
    
        $movie->save();
        return response()->json(['message' => 'Data updated successfully!', 'data' => $movie], 200);
    }


    public function deleteFile($id)
    {
        $movie = MovieManagement::find($id);
        if (!$movie) {
            return response()->json(['error' => 'Entry not found'], 200);
        }
        $movie->delete();
        return response()->json(['message' => 'Data deleted successfully!'], 200);
    }
}
