<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    // =====================
    // REGISTER
    // =====================
    public function register(Request $request)
    {
        // Validation
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',  // duplicate check
            'phone'         => 'required|string|max:20',
            'password'      => [
                                'required',
                                'min:8',
                                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*()_+{}:"<>?~]).{8,}$/'
                               ],
            'role'          => 'required|in:customer,tailor',
            // tailor ke liye required fields
            'address'       => 'required_if:role,tailor|nullable|string',
            'category'      => 'required_if:role,tailor|nullable|string',
            'slot_capacity' => 'required_if:role,tailor|nullable|integer|min:1',
        ], [
            // Custom error messages
            'email.unique'          => 'This email is already registered. Please login.',
            'password.regex'        => 'Password must have letters, numbers and special characters.',
            'address.required_if'   => 'Shop address is required for tailors.',
            'category.required_if'  => 'Specialization is required for tailors.',
            'slot_capacity.required_if' => 'Slot capacity is required for tailors.',
        ]);

        // User create karo — password hash hoga automatically
        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'password'      => Hash::make($request->password),  // bcrypt hash
            'role'          => $request->role,
            'address'       => $request->role === 'tailor' ? $request->address : null,
            'category'      => $request->role === 'tailor' ? $request->category : null,
            'slot_capacity' => $request->role === 'tailor' ? $request->slot_capacity : null,
        ]);

        // Register hone ke baad login kar do
        Auth::login($user);

        // Role ke hisaab se redirect
        return $this->redirectByRole($user->role);
    }

    // =====================
    // LOGIN
    // =====================
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Credentials check karo
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }

        // Session regenerate karo (security)
        $request->session()->regenerate();

        $user = Auth::user();

        return response()->json([
            'success'  => true,
            'message'  => 'Login successful.',
            'role'     => $user->role,
            'redirect' => $user->role === 'tailor' ? '/tailor/dashboard' : '/customer/dashboard',
        ]);
    }

    // =====================
    // LOGOUT
    // =====================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // =====================
    // HELPER — role ke hisaab se redirect
    // =====================
    private function redirectByRole($role)
    {
        if ($role === 'tailor') {
            return response()->json([
                'success'  => true,
                'message'  => 'Registration successful!',
                'redirect' => '/tailor/dashboard'
            ]);
        }
        return response()->json([
            'success'  => true,
            'message'  => 'Registration successful!',
            'redirect' => '/customer/dashboard'
        ]);
    }
}