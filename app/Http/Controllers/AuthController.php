<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request) {
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
            // Create user
            User::create([
                'id' => Str::uuid(),
                'username' => $username,
                'email' => $email,
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt($password),
                'remember_token' => md5($email.$password.$username.Carbon::now())
            ]);
            $user = User::where('email', $email)->firstOrFail();
            $output[] = [
                'message' => 'Register berhasil',
                'user' => $user
            ];
        } else {
            // Returns error if username is not alphanumeric
            $output[] = [
                'message' => 'Username hanya boleh memiliki karakter alphanumerik'
            ];
            $code = 400;
            return $this->jsonResponse($output, $code);
        }

        return $this->jsonResponse($output, $code);
    }
}
