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
            'email' => 'required|email',
            'username' => 'required',
            'password' => 'required',
            'institution' => 'required',
            'banned' => 'required'
        ]);

        // Put request input into variables
        $email = $request->input('email');
        $username = $request->input('username');
        $password = $request->input('password');
        $institution = $request->input('institution');
        $photo = $request->input('photo');
        $banned = $request->input('banned');

        // Save user profile if it exists
        if ($photo) {
            $filePath = Storage::disk('local')->put('users', $photo);
            User::where('id', $id)->update([
                'photo' => $filePath
            ]);
        }

        // Update user
        User::where('id', $id)->update([
            'email' => $email,
            'username' => $username,
            'password' => bcrypt($password),
            'institution' => $institution,
            'banned' => $banned
        ]);

        return $this->jsonResponse(['message' => 'User diperbarui']);
    }
    public function delete($id)
    {
        User::where('id', $id)->delete();
        return $this->jsonResponse(['message' => 'User dihapus']);
    }
}
