<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Show the form for creating a new cashier
     */
    public function createCashier()
    {
        return view('users.create-cashier');
    }

    /**
     * Store a newly created cashier
     */
    public function storeCashier(Request $request)
    {
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'fname' => $validated['fname'],
            'lname' => $validated['lname'],
            'contact_number' => $validated['contact_number'],
            'email' => $validated['email'],
            'role' => 'cashier',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.create-cashier')
            ->with('success', 'Cashier account created successfully!');
    }

    /**
     * Show the form for creating a new manager
     */
    public function createManager()
    {
        return view('users.create-manager');
    }

    /**
     * Store a newly created manager
     */
    public function storeManager(Request $request)
    {
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'fname' => $validated['fname'],
            'lname' => $validated['lname'],
            'contact_number' => $validated['contact_number'],
            'email' => $validated['email'],
            'role' => 'manager',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.create-manager')
            ->with('success', 'Manager account created successfully!');
    }

    /**
     * Display a listing of all users (optional - for viewing created accounts)
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Delete a user account
     */
    public function destroy(User $user)
    {
        // Prevent deleting admin accounts
        if ($user->role === 'admin') {
            return redirect()->back()
                ->with('error', 'Cannot delete admin accounts!');
        }

        $user->delete();

        return redirect()->back()
            ->with('success', 'User account deleted successfully!');
    }

    /**
     * Display a listing of all cashiers
     */
    public function listCashiers()
    {
        $cashiers = User::where('role', 'cashier')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('users.list-cashiers', compact('cashiers'));
    }

    /**
     * Display a listing of all managers
     */
    public function listManagers()
    {
        $managers = User::where('role', 'manager')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('users.list-managers', compact('managers'));
    }
}