<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Popup;
use App\Models\User;
use App\Models\Headline;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function dashboard()
    {
        $authUser = auth()->user();

        if ($authUser->role === 'admin') {
            $totalNews = News::where('author', $authUser->name)->count();

            $latestNews = News::where('author', $authUser->name)
                ->latest('published_at')
                ->take(5)
                ->get();

            return view('admin.dashboard', compact(
                'totalNews',
                'latestNews'
            ));
        }

        // Superadmin
        $totalNews = News::count();
        $totalHeadlines = Headline::count();
        $totalPopup = Popup::count();
        $totalUsers = User::count();
        $totalDosen = \DB::table('dosen')->count();

        $latestNews = News::latest('published_at')->take(5)->get();
        $latestHeadlines = Headline::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalNews',
            'totalHeadlines',
            'totalPopup',
            'totalUsers',
            'totalDosen',
            'latestNews',
            'latestHeadlines'
        ));
    }
    
    /**
     * Show the admin profile page
     */
    public function profile()
    {
        return view('admin.auth.profile');
    }
    
    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name'                   => 'required|string|max:255',
            'email'                  => 'required|email|unique:users,email,' . $user->id,
            'current_password'       => 'nullable|string',
            'new_password'           => 'nullable|string|min:6|confirmed',
            'new_password_confirmation' => 'nullable|string',
        ]);

        // Update name and email
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Check if password change is requested
        if ($validated['current_password'] && $validated['new_password']) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui');
    }
}
