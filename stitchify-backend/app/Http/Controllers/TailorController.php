<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\Tailor;
use App\Models\User;
use App\Models\Portfolio;
use App\Models\Notification;

class TailorController extends Controller
{
    public function index()
    {
        $tailors = Tailor::with('user')
                         ->where('available_slots', '>', 0)
                         ->get();

        return view('tailors.index', compact('tailors'));
    }

    public function show($id)
    {
        $tailor = Tailor::where('id', $id)
                        ->with(['user', 'portfolios'])
                        ->firstOrFail();

        return view('tailor.publicprofilepage', compact('tailor'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        $tailor = $user->tailor;

        if (!$tailor) {
            return redirect('/login')->with('error', 'Tailor profile not found.');
        }

        $pendingOrders = Order::where('tailor_id', $tailor->id)
                               ->where('status', 'pending')
                               ->with('customer.user')
                               ->latest()
                               ->get();

        $activeOrders = Order::where('tailor_id', $tailor->id)
                              ->whereIn('status', ['accepted', 'in_progress', 'ready', 'dispatched'])
                              ->with('customer.user')
                              ->latest()
                              ->get();

        $completedCount = Order::where('tailor_id', $tailor->id)
                               ->where('status', 'delivered')
                               ->count();

        $stats = [
            'pending' => $pendingOrders->count(),
            'active' => $activeOrders->count(),
            'completed' => $completedCount,
            'available_slots' => $tailor->available_slots,
            'max_slots' => $tailor->max_slots,
        ];

        return view('tailor.tailordashboard', compact(
            'tailor',
            'pendingOrders',
            'activeOrders',
            'completedCount',
            'stats'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        $tailor = $user->tailor()->with('portfolios')->first();

        return view('tailor.profile', compact('user', 'tailor'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $tailor = $user->tailor;

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'remove_photo' => 'nullable|boolean',
            'shop_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'experience_years' => 'nullable|integer|min:0',
            'specialization' => 'nullable|string|max:255',
            'base_price' => 'nullable|numeric|min:0',
            'max_slots' => 'nullable|integer|min:1|max:100',
        ]);

        $tailorUpdateData = [
            'shop_name' => $request->shop_name,
            'bio' => $request->bio,
            'city' => $request->city,
            'address' => $request->address,
            'experience_years' => $request->experience_years,
            'specialization' => $request->specialization,
            'base_price' => $request->base_price,
        ];

        if ($request->has('max_slots') && $request->max_slots != null) {
            $newMaxSlots = (int) $request->max_slots;

            $tailorUpdateData['base_max_slots'] = $newMaxSlots;
            $tailorUpdateData['max_slots'] = $newMaxSlots;

            if ($tailor->available_slots > $newMaxSlots) {
                $tailorUpdateData['available_slots'] = $newMaxSlots;
            }
        }

        $userData = [
            'name' => $request->name,
            'phone' => $request->phone,
        ];

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $userData['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        } elseif ($request->boolean('remove_photo') && $user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
            $userData['profile_image'] = null;
        }

        $user->update($userData);
        $tailor->update($tailorUpdateData);

        return redirect()->route('tailor.profile')->with('success', 'Profile updated successfully!');
    }

    public function uploadPortfolio(Request $request)
    {
        $tailor = Auth::user()->tailor;

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('portfolios', $imageName, 'public');

            Portfolio::create([
                'tailor_id' => $tailor->id,
                'title' => $validated['title'] ?? null,
                'description' => $validated['description'] ?? null,
                'image_path' => $imagePath,
                'sort_order' => $tailor->portfolios()->count(),
            ]);
        }

        return back()->with('success', 'Portfolio image uploaded successfully!');
    }

    public function deletePortfolio($id)
    {
        $tailor = Auth::user()->tailor;
        $portfolio = $tailor->portfolios()->findOrFail($id);

        if (Storage::disk('public')->exists($portfolio->image_path)) {
            Storage::disk('public')->delete($portfolio->image_path);
        }

        $portfolio->delete();

        return back()->with('success', 'Portfolio image deleted successfully!');
    }

    public function byCategory($category)
    {
        $tailors = Tailor::with('user')
            ->where('status', 'approved')
            ->where(function ($q) use ($category) {
                $q->where('specialization', $category)
                  ->orWhere('specialization', 'all');
            })
            ->get();

        return view('tailors.category', compact('tailors', 'category'));
    }
}
