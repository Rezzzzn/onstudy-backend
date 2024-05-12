<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'username' => 'required',
        ]);

        // Put request input into variables
        $email = $request->input('email');
        $password = $request->input('password');
        $username = $request->input('username');

        $output = [];
        $code = 200;

        // Prevent non-alphanumeric username
        if (ctype_alnum($username)) {
            // Create user and login them
            User::create([
                'id' => Str::uuid(),
                'username' => $username,
                'email' => $email,
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt($password),
                'remember_token' => md5($email.$password.$username.Carbon::now())
            ]);
            $user = User::where('email', $email)->firstOrFail();
            $output = [
                'message' => 'Register berhasil',
                'user' => $user
            ];
        } else {
            // Returns error if username is not alphanumeric
            $output = [
                'message' => 'Username hanya boleh memiliki karakter alphanumerik'
            ];
            $code = 400;
            return $this->jsonResponse($output, $code);
        }

        return $this->jsonResponse($output, $code);
    }

    public function login(Request $request) {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Put request input into variables
        $email = $request->input('email');
        $password = $request->input('password');

        // Get the user based on email
        $user = User::where('email', $email)->first();

        // Return error if email or password is incorrect
        if (!$user) {
            return $this->jsonResponse(['message' => 'Email tidak ditemukan'], 404);
        }
        if (!password_verify($password, $user->password)) {
            return $this->jsonResponse(['message' => 'Password salah'], 401);
        }
        // Generate new token if user is not logged in
        if (!$user->remember_token) {
            // Generate new login token
            $user->remember_token = md5($email.$password.$user->username.Carbon::now());
            $user->save();
        }

        return $this->jsonResponse(['message' => 'Login berhasil', 'user' => $user], 200);
    }

    public function logout(Request $request)
    {
        $bearerToken = $request->bearerToken();
        $user = User::where('remember_token', $bearerToken)->update([
            'remember_token' => null
        ]);
        if (!$user) {
            return $this->jsonResponse(['message' => 'Logout gagal'], 400);
        }
        return $this->jsonResponse(['message' => 'Logout berhasil'], 200);
    }

    public function verifyAuth(Request $request)
    {
        $bearerToken = $request->bearerToken();
        $user = User::where('remember_token', $bearerToken)->firstOrFail();
        if (!$user) {
            return $this->jsonResponse(['message' => 'Token salah'], 400);
        }
        return $this->jsonResponse(['message' => 'Token terverifikasi', 'user' => $user], 200);
    }
}
