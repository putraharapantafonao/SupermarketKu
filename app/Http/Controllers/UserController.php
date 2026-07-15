<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // 💡 PERBAIKAN: Menambahkan 'confirmed' agar password wajib cocok dengan password_confirmation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
        ];

        // Proteksi Keamanan: Hanya Owner yang boleh mendaftarkan Owner baru
        $ownerRole = Role::where('name', 'Owner')->first();
        if (auth()->user()->role->name !== 'Owner' && $data['role_id'] == $ownerRole?->id) {
            $data['role_id'] = Role::where('name', 'Kasir')->value('id');
        }

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // 💡 PERBAIKAN: Menambahkan 'confirmed' jika password diisi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];

        // Proteksi Keamanan: Non-Owner tidak boleh mengubah akun lain menjadi Owner
        $ownerRole = Role::where('name', 'Owner')->first();
        if (auth()->user()->role->name !== 'Owner' && $data['role_id'] == $ownerRole?->id) {
            $data['role_id'] = $user->getOriginal('role_id');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'User yang sedang login tidak bisa dihapus.');
        }

        $adminRoleIds = Role::whereIn('name', ['Owner', 'Admin'])->pluck('id');
        $adminCount = User::whereIn('role_id', $adminRoleIds)->count();
        if ($adminCount <= 1 && $adminRoleIds->contains($user->role_id)) {
            return back()->with('error', 'Tidak dapat menghapus Admin/Owner terakhir.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
