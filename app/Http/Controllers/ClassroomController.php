<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassroomController extends Controller
{
    // Retrieve all rows from the 'classes' table
    public function read(Request $request)
    {
        $classes = Classes::all();
        return $this->jsonResponse(['message' => 'Classes retrieved successfully', 'classes' => $classes]);
    }

    // Retrieve a single row by ID from the 'classes' table
    public function readById(Request $request, $id)
    {
        $class = Classes::where('id', $id)->first();
        if ($class) {
            return $this->jsonResponse(['message' => 'Class retrieved successfully', 'class' => $class]);
        } else {
            return $this->jsonResponse(['message' => 'Class not found'], 404);
        }
    }

    // Insert a new row into the 'classes' table
    public function create(Request $request)
    {
        // Validate input
        $request->validate([
            'title' => 'required',
            'subject' => 'required|in:Sains,Matematika,Bahasa,Teknologi,Sosial,Seni',
        ]);

        Classes::create([
            'id' => Str::uuid(),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'subject' => $request->input('subject'),
            'photo' => $request->input('photo'),
        ]);

        return $this->jsonResponse(['message' => 'Class created successfully'], 201);
    }

    // Update a row by ID in the 'classes' table
    public function update(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'title' => 'required',
            'subject' => 'required|in:Sains,Matematika,Bahasa,Teknologi,Sosial,Seni',
        ]);

        Classes::where('id', $id)->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'subject' => $request->input('subject'),
            'photo' => $request->input('photo'),
        ]);

        return $this->jsonResponse(['message' => 'Class updated successfully']);
    }

    // Delete a row by ID in the 'classes' table
    public function delete(Request $request, $id)
    {
        Classes::where('id', $id)->delete();
        return $this->jsonResponse(['message' => 'Class deleted successfully']);
    }
}
