<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // API Methods
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user'    => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Email or password is incorrect'
            ], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
    
    // Admin CRUD
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->where('id', '!=', auth()->id())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.users.views_users', compact('users'));
    }
    
    public function create()
    {
        return view('admin.users.form_users', ['user' => null]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:superadmin,admin',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')->with('success_key', 'users.flashCreated');
    }
    
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.form_users', compact('user'));
    }
    
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'role'     => 'required|in:superadmin,admin',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success_key', 'users.flashUpdated');
    }
    
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success_key', 'users.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:users,id',
        ]);

        $selectedUsers = User::whereIn('id', $request->ids)
            ->where('id', '!=', auth()->id())
            ->get();

        $selectedSuperadminCount = $selectedUsers->where('role', 'superadmin')->count();
        $totalSuperadmin = User::where('role', 'superadmin')->count();

        if ($selectedSuperadminCount >= $totalSuperadmin) {
            return redirect()->route('admin.users.index')
            ->with('error_key', 'users.flashCannotDeleteAllSuperadmin');
        }

        foreach ($selectedUsers as $user) {
            $user->delete();
        }

        return redirect()->route('admin.users.index')
            ->with('success_key', 'users.flashBulkDeleted');
    }
}
