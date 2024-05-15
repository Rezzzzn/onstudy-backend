<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function read() {
        $materials = Material::all();
        return $this->jsonResponse(['message' => 'Materials retrieved successfully', 'materials' => $materials]);
    }

    public function readById($id) {
        $material = Material::where('id', $id)->first();
        return $this->jsonResponse(['message' => 'Material retrieved successfully', 'material' => $material]);
    }

    public function create(Request $request) {
        // Validate input
        $request->validate([
            'class_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'file' => 'required',
            'type' => 'required:in:assignment,material',
        ]);

        // Put requests into variables
        $classId = $request->input('class_id');
        $title = $request->input('title');
        $description = $request->input('description');
        $file = $request->input('file');
        $type = $request->input('type');
        $deadline = $request->input('deadline');

        // Save file material
        $filePath = Storage::disk('local')->put('materials', $file);
        // Check if file is saved properly
        $checkFile = Storage::exists($filePath);
        if (!$checkFile) {
            return $this->jsonResponse(['message' => 'File not saved'], 500);
        }

        // Create material
        Material::create([
            'id' => Str::uuid(),
            'class_id' => $classId,
            'title' => $title,
            'description' => $description,
            'file' => $filePath,
            'type' => $type,
            'deadline' => $deadline
        ]);

        return $this->jsonResponse(['message' => 'Material created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'class_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'file' => 'required',
            'type' => 'required:in:assignment,material',
        ]);

        // Put requests into variables
        $classId = $request->input('class_id');
        $title = $request->input('title');
        $description = $request->input('description');
        $file = $request->input('file');
        $type = $request->input('type');
        $deadline = $request->input('deadline');

        // Save file material
        $filePath = Storage::disk('local')->put('materials', $file);
        // Check if file is saved properly
        $checkFile = Storage::exists($filePath);
        if (!$checkFile) {
            return $this->jsonResponse(['message' => 'File not saved'], 500);
        }
    }

    public function delete()
    {

    }
}
