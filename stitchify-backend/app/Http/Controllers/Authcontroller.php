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
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'phone'         => 'required|string|max:20',
            'password'      => 'required|min:8',
            'role'          => 'required|in:customer,tailor',
            'address'       => 'required_if:role,tailor|nullable|string',
            'category'      => 'required_if:role,tailor|nullable|string',
            'slot_capacity' => 'required_if:role,tailor|nullable|integer|min:1',
        ], [
            'email.unique'              => 'Email is already registered.',
            'address.required_if'       => 'Address is compulsory.',
            'category.required_if'      => 'Specialization is compulsory.',
            'slot_capacity.required_if' => 'Slot is compulsory',
        ]);

        // User banao
        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'password'      => Hash::make($request->password),
            'role'          => $request->role,
            'address'       => $request->role === 'tailor' ? $request->address : null,
            'category'      => $request->role === 'tailor' ? $request->category : null,
            'slot_capacity' => $request->role === 'tailor' ? $request->slot_capacity : null,
            'is_active'     => true,
        ]);

        // Customer profile banao
        if ($user->role === 'customer') {
            Customer::create([
                'user_id' => $user->id,
            ]);
        }

        // Tailor profile banao
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

        $redirect = $user->role === 'tailor'
            ? '/tailor/dashboard'
            : '/customer/dashboard';

        return response()->json([
            'success'  => true,
            'redirect' => $redirect,
        ]);
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

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email or password is incorrect.',
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been blocked. Contact support.',
            ], 403);
        }

        if (Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate();

            $redirect = match(Auth::user()->role) {
                'admin'  => '/admin/dashboard',
                'tailor' => '/tailor/dashboard',
                default  => '/customer/dashboard',
            };

            return response()->json([
                'success'  => true,
                'redirect' => $redirect,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email or password is incorrect.',
        ], 401);
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