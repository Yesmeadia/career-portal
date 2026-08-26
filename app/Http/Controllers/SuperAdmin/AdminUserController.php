<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status']);

        $query = User::role('Super Admin');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $admins = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_admins' => User::role('Super Admin')->count(),
            'active_admins' => User::role('Super Admin')->where('status', 'active')->count(),
            'inactive_admins' => User::role('Super Admin')->where('status', 'inactive')->count(),
        ];

        return view('superadmin.admins.index', compact('admins', 'filters', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|in:active,inactive',
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'],
            'school_id' => null,
        ]);

        $admin->assignRole('Super Admin');

        ActivityLog::record("Created new Super Admin account {$admin->email}", $admin, 'admins');

        return redirect()->route('superadmin.admins.index')->with('success', "Super Admin account '{$admin->name}' created successfully.");
    }

    public function update(Request $request, $id)
    {
        $admin = User::role('Super Admin')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'phone' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:active,inactive',
        ]);

        if (auth()->id() === $admin->id && $validated['status'] === 'inactive') {
            return redirect()->back()->with('error', 'You cannot deactivate your own Super Admin account.');
        }

        if ($validated['status'] === 'inactive' && $admin->status === 'active') {
            $activeCount = User::role('Super Admin')->where('status', 'active')->where('id', '!=', $admin->id)->count();
            if ($activeCount === 0) {
                return redirect()->back()->with('error', 'Cannot deactivate the only active Super Admin account.');
            }
        }

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->phone = $validated['phone'] ?? null;
        $admin->status = $validated['status'];

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        ActivityLog::record("Updated Super Admin account {$admin->email}", $admin, 'admins');

        return redirect()->route('superadmin.admins.index')->with('success', "Super Admin account '{$admin->name}' updated successfully.");
    }

    public function toggleStatus($id)
    {
        $admin = User::role('Super Admin')->findOrFail($id);

        if (auth()->id() === $admin->id) {
            return redirect()->back()->with('error', 'You cannot deactivate your own Super Admin account.');
        }

        $newStatus = $admin->status === 'active' ? 'inactive' : 'active';

        if ($newStatus === 'inactive') {
            $activeCount = User::role('Super Admin')->where('status', 'active')->where('id', '!=', $admin->id)->count();
            if ($activeCount === 0) {
                return redirect()->back()->with('error', 'Cannot deactivate the only active Super Admin account.');
            }
        }

        $admin->update(['status' => $newStatus]);

        ActivityLog::record("Changed status of Super Admin {$admin->email} to {$newStatus}", $admin, 'admins');

        return redirect()->back()->with('success', "Super Admin '{$admin->name}' status updated to " . ucfirst($newStatus) . ".");
    }

    public function destroy($id)
    {
        $admin = User::role('Super Admin')->findOrFail($id);

        if (auth()->id() === $admin->id) {
            return redirect()->back()->with('error', 'You cannot delete your own Super Admin account.');
        }

        $totalAdmins = User::role('Super Admin')->count();
        if ($totalAdmins <= 1) {
            return redirect()->back()->with('error', 'Cannot delete the only remaining Super Admin account.');
        }

        $name = $admin->name;
        $email = $admin->email;

        $admin->delete();

        ActivityLog::record("Deleted Super Admin account {$email}", null, 'admins');

        return redirect()->route('superadmin.admins.index')->with('success', "Super Admin account '{$name}' deleted successfully.");
    }
}
