<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Contracts\Service\Attribute\Required;

class AuthController extends Controller
{

    public function __construct(){
        $this->middleware(['auth:sanctum'])->only('logout', 'me');
    }

    public function login(Request $request)
     {
        $request->validate([
            'email' => 'Required',
            'password' => 'Required',
        ]);

        $user = User::where('email', $request->email)->first();
 
    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
 
    return $user->createToken($request->email)->plainTextToken;
    }

    public function logout(Request $request) 
    {
        
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Anda Telah Logout']);
    }

    public function me()
    {
        $user = Auth::user();
        return response()->json([
            'id' => $user->id,
            'username' => $user->username
        ]);
    }
}
