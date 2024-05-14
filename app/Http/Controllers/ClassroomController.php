<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;


class ClassesController extends Controller
{
    // Retrieve all rows from the 'classes' table
    public function read(Request $request)
    {
        $classes = Classes::all();
        return response()->json($classes);
    }

    // Retrieve a single row by ID from the 'classes' table
    public function readById(Request $request, $id)
    {
        $class = Classes::find($id);
        if ($class) {
            return response()->json($class);
        } else {
            return response()->json(['message' => 'Class not found'], );
        }
    }

    // Insert a new row into the 'classes' table
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Add other validation rules as necessary
        ]);

        $class = new Classes();
        $class->name = $request->input('name');
        $class->description = $request->input('description');
        // Set other fields as necessary

        $class->save();

        return response()->json($class, 201);
    }

    // Update a row by ID in the 'classes' table
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Add other validation rules as necessary
        ]);

        $class = Classes::find($id);
        if ($class) {
            $class->name = $request->input('name');
            $class->description = $request->input('description');
            // Update other fields as necessary

            $class->save();

            return response()->json($class);
        } else {
            return response()->json(['message' => 'Class not found'], 404);
        }
    }

    // Delete a row by ID in the 'classes' table
    public function delete(Request $request, $id)
    {
        $class = Classes::find($id);
        if ($class) {
            $class->delete();
            return response()->json(['message' => 'Class deleted successfully']);
        } else {
            return response()->json(['message' => 'Class not found'], 404);
        }
    }
}
