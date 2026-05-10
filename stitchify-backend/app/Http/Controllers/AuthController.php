<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Customer;
use App\Models\Tailor;

class AuthController extends Controller
{
    // Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Register process
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name'          => 'required|string|max:255',
                'email'         => 'required|email|unique:users,email',
                'phone'         => 'required|string|max:20',
                'password'      => 'required|min:8',
                'role'          => 'required|in:customer,tailor',
                'address'       => 'required_if:role,tailor|nullable|string',
                'category'      => 'required_if:role,tailor|nullable|string',
                'slot_capacity' => 'required_if:role,tailor|nullable|integer|min:1',
            ]);

            // Create User
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'password'  => Hash::make($request->password),
                'role'      => $request->role,
                'is_active' => true,
            ]);

            // Customer profile
            if ($user->role === 'customer') {
                Customer::create([
                    'user_id' => $user->id,
                ]);
            }

            // Tailor profile
            if ($user->role === 'tailor') {
                Tailor::create([
                    'user_id'         => $user->id,
                    'address'         => $request->address,
                    'specialization'  => $request->category,
                    'max_slots'       => $request->slot_capacity ?? 5,
                    'available_slots' => $request->slot_capacity ?? 5,
                    'status'          => 'pending',
                ]);
            }

            Auth::login($user);

            return response()->json([
                'success'  => true,
                'redirect' => $user->role === 'tailor'
                    ? '/tailor/dashboard'
                    : '/customer/dashboard'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Login process
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();

            return response()->json([
                'success' => false,
                'message' => 'Your account is blocked.'
            ], 403);
        }

        $request->session()->regenerate();

        return response()->json([
            'success'  => true,
            'redirect' => match ($user->role) {
                'admin'  => '/admin/dashboard',
                'tailor' => '/tailor/dashboard',
                default  => '/customer/dashboard',
            }
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}