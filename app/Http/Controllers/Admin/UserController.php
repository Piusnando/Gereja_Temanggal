<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 


class UserController extends Controller
{
    public function index()
    {
        // Ambil semua user, urutkan terbaru, paginasi
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }
public function store(Request $request)
    {
        // 1. Validasi Input (termasuk role baru dan ID wilayah/lingkungan)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,pengurus_gereja,misdinar,lektor,direktur_musik,omk,pia_pir,inventaris,koster,ketua_wilayah,ketua_lingkungan',
            'territory_id' => 'nullable|exists:territories,id',
            'lingkungan_id' => 'nullable|exists:lingkungans,id',
        ]);

        // 2. Simpan Data
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            // Jika role ketua_wilayah atau ketua_lingkungan, simpan territory_id, sisanya null
            'territory_id' => in_array($request->role, ['ketua_wilayah', 'ketua_lingkungan']) ? $request->territory_id : null,
            // Jika role ketua_lingkungan, simpan lingkungan_id, sisanya null
            'lingkungan_id' => $request->role === 'ketua_lingkungan' ? $request->lingkungan_id : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // 1. Validasi Input (Abaikan email unik milik user itu sendiri)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,pengurus_gereja,misdinar,lektor,direktur_musik,omk,pia_pir,inventaris,koster,ketua_wilayah,ketua_lingkungan',
            'password' => 'nullable|min:8',
            'territory_id' => 'nullable|exists:territories,id',
            'lingkungan_id' => 'nullable|exists:lingkungans,id',
        ]);

        // 2. Kumpulkan data
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'territory_id' => in_array($request->role, ['ketua_wilayah', 'ketua_lingkungan']) ? $request->territory_id : null,
            'lingkungan_id' => $request->role === 'ketua_lingkungan' ? $request->lingkungan_id : null,
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        // 3. Simpan perubahan
        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data user diperbarui!');
    }

    public function destroy(User $user)
    {
        // Gunakan Auth::id() pengganti auth()->id()
        if (Auth::id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}