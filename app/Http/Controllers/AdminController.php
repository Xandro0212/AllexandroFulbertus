<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // ===== Manajemen Pengguna =====

    public function users(Request $request)
    {
        $role = $request->query('role');

        $users = User::when($role && array_key_exists($role, User::ROLES), function ($q) use ($role) {
            $q->where('role', $role);
        })
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function createUser()
    {
        return view('admin.user-form', ['user' => new User(), 'isEdit' => false]);
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'nim' => ['nullable', 'string', 'max:20', 'unique:users,nim'],
            'phone' => ['nullable', 'string', 'max:20'],
            'program_studi' => ['nullable', 'string', 'max:100'],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'role.required' => 'Peran wajib dipilih.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nim' => $data['nim'] ?? null,
            'phone' => $data['phone'] ?? null,
            'program_studi' => $data['program_studi'] ?? null,
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        return view('admin.user-form', ['user' => $user, 'isEdit' => true]);
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'nim' => ['nullable', 'string', 'max:20', Rule::unique('users', 'nim')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'program_studi' => ['nullable', 'string', 'max:100'],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'nim' => $data['nim'] ?? null,
            'phone' => $data['phone'] ?? null,
            'program_studi' => $data['program_studi'] ?? null,
            'role' => $data['role'],
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        abort_if($user->id === auth()->id(), 422, 'Anda tidak dapat menghapus akun sendiri.');

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    // ===== Manajemen Ruangan =====

    public function classrooms()
    {
        $classrooms = Classroom::orderBy('kode_ruangan')->paginate(15);

        return view('admin.classrooms', compact('classrooms'));
    }

    public function createClassroom()
    {
        return view('admin.classroom-form', ['classroom' => new Classroom(), 'isEdit' => false]);
    }

    public function storeClassroom(Request $request)
    {
        $data = $request->validate([
            'kode_ruangan' => ['required', 'string', 'max:20', 'unique:classrooms,kode_ruangan'],
            'nama_ruangan' => ['required', 'string', 'max:255'],
            'gedung' => ['nullable', 'string', 'max:100'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:1000'],
            'fasilitas' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:tersedia,tidak_tersedia'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
        ], [
            'kode_ruangan.required' => 'Kode ruangan wajib diisi.',
            'kode_ruangan.unique' => 'Kode ruangan sudah digunakan.',
            'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
            'kapasitas.required' => 'Kapasitas wajib diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, PNG, WEBP, atau SVG.',
            'foto.max' => 'Ukuran foto maksimal 4 MB.',
        ]);

        $data['foto'] = $this->handleFoto($request);

        Classroom::create($data);

        return redirect()->route('admin.classrooms')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function editClassroom(Classroom $classroom)
    {
        return view('admin.classroom-form', ['classroom' => $classroom, 'isEdit' => true]);
    }

    public function updateClassroom(Request $request, Classroom $classroom)
    {
        $data = $request->validate([
            'kode_ruangan' => ['required', 'string', 'max:20', Rule::unique('classrooms', 'kode_ruangan')->ignore($classroom->id)],
            'nama_ruangan' => ['required', 'string', 'max:255'],
            'gedung' => ['nullable', 'string', 'max:100'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:1000'],
            'fasilitas' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:tersedia,tidak_tersedia'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
        ], [
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, PNG, WEBP, atau SVG.',
            'foto.max' => 'Ukuran foto maksimal 4 MB.',
        ]);

        $data['foto'] = $this->handleFoto($request, $classroom->foto);

        $classroom->update($data);

        return redirect()->route('admin.classrooms')->with('success', 'Data ruangan berhasil diperbarui.');
    }

    /**
     * Simpan file foto ke public/images/ruangan dan kembalikan path relatif.
     */
    private function handleFoto(Request $request, ?string $existing = null): ?string
    {
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            // Hapus foto lama jika ada (kecuali placeholder bawaan)
            if ($existing && ! str_starts_with($existing, 'ruangan/default')) {
                $oldPath = public_path('images/' . $existing);
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('foto');
            $name = 'ruangan-' . time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/ruangan'), $name);

            return 'ruangan/' . $name;
        }

        return $existing;
    }

    public function destroyClassroom(Classroom $classroom)
    {
        if ($classroom->bookings()->exists()) {
            return back()->with('error', 'Ruangan tidak dapat dihapus karena memiliki riwayat peminjaman.');
        }

        $classroom->delete();

        return back()->with('success', 'Ruangan berhasil dihapus.');
    }

    // ===== Semua Pengajuan =====

    public function bookings(Request $request)
    {
        $status = $request->query('status');

        $bookings = Booking::with(['user', 'classroom', 'approver'])
            ->when($status && array_key_exists($status, Booking::STATUSES), function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.bookings', compact('bookings'));
    }
}
