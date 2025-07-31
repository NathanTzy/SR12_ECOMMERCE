<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when(
            $request->filled('role'),
            fn($query) => $query->whereHas('roles', fn($q) => $q->where('name', $request->role))
        )->latest()->paginate(5)
            ->withQueryString();

        $roles = Role::pluck('name');

        return view('pages.backend.user .index', compact('users', 'roles'));
    }
    public function create()
    {
        $roles = Role::all();
        return view(' pages.backend.user .create', compact('roles'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('pages.backend.user .edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'required|exists:roles,name',

            // Validasi tambahan
            'nama_lengkap' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'kecamatan' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'no_wa' => 'nullable|string|max:20',
        ]);

        // Update data lama
        if ($request->filled('name')) $user->name = $request->name;
        if ($request->filled('email')) $user->email = $request->email;
        if ($request->filled('password')) $user->password = Hash::make($request->password);
        $user->syncRoles([$request->role]);

        // Update biodata tambahan
        $user->nama_lengkap = $request->nama_lengkap;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->tempat_lahir = $request->tempat_lahir;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->kecamatan = $request->kecamatan;
        $user->kota = $request->kota;
        $user->provinsi = $request->provinsi;
        $user->no_wa = $request->no_wa;

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate');
    }

    public function show(User $user)
    {
        return view('pages.backend.user .show', compact('user'));
    }
    


    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }
}
