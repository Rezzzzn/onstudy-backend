<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\UserClass;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassroomController extends Controller
{
    // Retrieve all rows from the 'classes' table
    public function read(Request $request)
    {
        $classes = Classes::all();
        return $this->jsonResponse(['message' => 'Semua kelas ditemukan', 'classrooms' => $classes]);
    }

    // Retrieve a single row by ID from the 'classes' table
    public function readById(Request $request, $id)
    {
        $class = Classes::where('id', $id)->first();
        if ($class) {
            return $this->jsonResponse(['message' => 'Kelas ditemukan', 'classroom' => $class]);
        } else {
            return $this->jsonResponse(['message' => 'Kelas tidak ditemukan'], 404);
        }
    }

    // Insert a new row into the 'classes' table
    public function create(Request $request)
    {
        // Validate input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required',
            'description' => 'required',
            'subject' => 'required|in:Sains,Matematika,Bahasa,Teknologi,Sosial,Seni',
        ]);

        $classId = Str::uuid();

        // Create a new classroom
        Classes::create([
            'id' => $classId,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'subject' => $request->input('subject'),
            'photo' => $request->input('photo'),
        ]);

        // Create a new teacher relation
        // Get user
        $userId = $request->input('user_id');

        UserClass::create([
            'id' => Str::uuid(),
            'user_id' => $userId,
            'class_id' => $classId,
            'role' => 'Teacher',
        ]);

        return $this->jsonResponse(['message' => 'Kelas terbuat', 'classroom' => Classes::where('id', $classId)->first()], 201);
    }

    // Update a row by ID in the 'classes' table
    public function update(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'subject' => 'required|in:Sains,Matematika,Bahasa,Teknologi,Sosial,Seni'
        ]);

        // Save user profile if it exists
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/classrooms');
            Classes::where('id', $id)->update(['photo' => basename($path)]);
        }

        // Update class details
        Classes::where('id', $id)->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'subject' => $request->input('subject'),
        ]);

        return $this->jsonResponse(['message' => 'Kelas diperbarui']);
    }

    // Delete a row by ID in the 'classes' table
    public function delete(Request $request, $id)
    {
        Classes::where('id', $id)->delete();
        return $this->jsonResponse(['message' => 'Kelas dihapus']);
    }
}
