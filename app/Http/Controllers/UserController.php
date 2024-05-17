<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function read()
    {
        $users = User::all();
        return $this->jsonResponse(['message' => 'User ditemukan', 'users' => $users]);
    }
    public function readById($id)
    {
        $user = User::where('id', $id)->first();
        return $this->jsonResponse(['message' => 'User ditemukan', 'user' => $user]);
    }
    public function update(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'username' => 'required',
            'institution' => 'required'
        ]);

        // Put request input into variables
        $username = $request->input('username');
        $institution = $request->input('institution');

        // Save user profile if it exists
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/users');
            User::where('id', $id)->update([
                'photo' => basename($path)
            ]);
        }

        // Update user
        User::where('id', $id)->update([
            'username' => $username,
            'institution' => $institution,
        ]);

        return $this->jsonResponse(['message' => 'User diperbarui']);
    }
    public function delete($id)
    {
        User::where('id', $id)->delete();
        return $this->jsonResponse(['message' => 'User dihapus']);
    }
}
