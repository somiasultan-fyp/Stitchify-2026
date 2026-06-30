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
    public function showRegister()
    {
        return view('auth.register');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

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
            'email.unique' => 'Email already registered.',
            'address.required_if' => 'Address is required for tailors.',
            'category.required_if' => 'Specialization is required for tailors.',
            'slot_capacity.required_if' => 'Slot capacity is required for tailors.',
        ]);

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

        if ($user->role === 'customer') {
            Customer::create(['user_id' => $user->id]);
        }

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
        $request->session()->regenerate();
        $user->sendEmailVerificationNotification();
         return redirect()->route('verification.notice')
        ->with('info', 'Account created! Please check your email to verify.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
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
    $user = auth()->user();

    $redirect = match($user->role) {
        'admin'    => route('admin.dashboard'),
        'tailor'   => route('tailor.dashboard'),
        'customer' => route('customer.dashboard'),
        default    => '/',
    };

    return response()->json([
        'success'  => true,
        'redirect' => $redirect,
    ]);
}

return response()->json([
    'success' => false,
    'message' => 'Invalid credentials.',
], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}