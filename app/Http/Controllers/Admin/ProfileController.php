<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // Tampilkan Halaman Profil
    public function edit()
    {
        return view('admin.profile');
    }

    // Update Data Diri (Nama, Email, Foto)
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Cek jika ada upload foto baru
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Simpan foto baru
            $path = $request->file('avatar')->store('uploads/avatars', 'public');
            $user->avatar = $path;
        }

        /** @var \App\Models\User $user */
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // Update Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8', // password_confirmation wajib ada
        ]);

        // Cek password lama
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah!']);
        }

        // Update password
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah!');
    }
}