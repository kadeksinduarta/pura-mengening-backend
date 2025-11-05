<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $admin = Admin::create($validated);

        return response()->json([
            'message' => 'Admin berhasil dibuat',
            'data' => $admin,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::guard('admin')->attempt($credentials)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $admin = Auth::guard('admin')->user();
        $token = $admin->createToken('admin_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'admin' => $admin,
        ]);
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (! $user || ! Hash::check($request->password, $user->password)) {
    //         return response()->json(['message' => 'Invalid credentials'], 401);
    //     }

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'Bearer'
    //     ]);
    // }


    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout berhasil']);
    }

    public function me()
    {
        return response()->json(Auth::guard('admin')->user());
    }
}
