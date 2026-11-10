<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nim' => ['required', 'string', 'max:20', 'unique:users,nim'],
            'phone' => ['nullable', 'string', 'max:20'],
            'program_studi' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'program_studi.required' => 'Program studi wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nim' => $data['nim'],
            'phone' => $data['phone'] ?? null,
            'program_studi' => $data['program_studi'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_MAHASISWA,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil. Selamat datang di SIPKA IDB Bali!');
    }
}
