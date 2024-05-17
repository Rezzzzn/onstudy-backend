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
        return $this->jsonResponse(['message' => 'Semua material ditemukan', 'materials' => $materials]);
    }

    public function readById($id) {
        $material = Material::where('id', $id)->first();
        return $this->jsonResponse(['message' => 'Material ditemukan', 'material' => $material]);
    }

    public function create(Request $request) {
        // Validate input
        $request->validate([
            'class_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'type' => 'required:in:assignment,material',
        ]);

        // Put requests into variables
        $classId = $request->input('class_id');
        $title = $request->input('title');
        $description = $request->input('description');
        $type = $request->input('type');
        $deadline = $request->input('deadline');

        // Save file material
        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('public/materials');
        }

        // Create material
        $materialId = Str::uuid();
        Material::create([
            'id' => $materialId,
            'class_id' => $classId,
            'title' => $title,
            'description' => $description,
            'file' => basename($path) || $path || null,
            'type' => $type,
            'deadline' => $deadline
        ]);

        return $this->jsonResponse(['message' => 'Material dibuat', 'material' => Material::where('id', $materialId)->first()], 201);
    }

    public function update(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'class_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'type' => 'required:in:assignment,material',
        ]);

        // Put requests into variables
        $classId = $request->input('class_id');
        $title = $request->input('title');
        $description = $request->input('description');
        $type = $request->input('type');
        $deadline = $request->input('deadline');

        // Save file material
        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('public/materials');
        }

        Material::where('id', $id)->update([
            'class_id' => $classId,
            'title' => $title,
            'description' => $description,
            'file' => basename($path) || $path || null,
            'type' => $type,
            'deadline' => $deadline,
        ]);

        return $this->jsonResponse(['message' => 'Material diperbarui'], 200);
    }


    public function delete($id)
    {
        Material::where('id', $id)->delete();
        return $this->jsonResponse(['message' => 'Material dihapus'], 200);
    }
}
