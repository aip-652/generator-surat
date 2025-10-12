<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\RedirectMiddleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- Import Hash
use Illuminate\Support\Facades\Auth; 
use Illuminate\Validation\Rule;      // <-- Import Rule

class UserController extends Controller
{
  /**
   * Menampilkan daftar semua pengguna dengan sorting.
   */
  public function index(Request $request) // <-- Tambahkan Request $request
  {
    // 1. Ambil parameter sorting dari URL, tentukan nilai default
    $orderBy = $request->input('order_by', 'name'); // Default sort by name
    $sort = $request->input('sort', 'asc');         // Default sort ascending

    // 2. Buat query dengan sorting
    $query = User::orderBy($orderBy, $sort);

    // 3. Ambil data dengan pagination
    $users = $query->paginate(15);

    // 4. Kirim data dan parameter sorting ke view
    return view('users.index', compact('users', 'orderBy', 'sort'));
  }

  /**
   * Tampilkan formulir untuk membuat pengguna baru.
   */
  public function create()
  {
    return view('users.create');
  }

  /**
   * Simpan pengguna baru ke database.
   */
  public function store(Request $request)
  {
    // 1. Validasi input
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password' => ['required', 'string', 'min:8', 'confirmed'],
      'role' => ['required', 'string', Rule::in(['admin', 'user', 'special'])],
    ]);

    // 2. Buat user baru
    User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role' => $request->role,
    ]);

    // 3. Redirect kembali dengan pesan sukses
    return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
  }
  /**
     * Tampilkan formulir untuk mengedit pengguna.
     */
    public function edit(User $user) // Laravel akan otomatis mencari user berdasarkan ID
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update data pengguna di database.
     */
    public function update(Request $request, User $user)
    {
        // 1. Validasi input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(['admin', 'user', 'special'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // Password opsional
        ]);

        // 2. Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // 3. Redirect kembali dengan pesan sukses
        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

  public function destroy(User $user):RedirectResponse
  {
    // dd() akan menghentikan semua proses dan menampilkan informasi ini di layar
    dd(
      'User yang akan dihapus:',
      $user->toArray(),
      'User yang sedang login:',
      Auth::user()->toArray(),
      'Apakah ID mereka sama?',
      Auth::id() === $user->id
    );

    if (!$user->exists) {
      return redirect()->route('users.index')->with('error', 'Gagal: Pengguna yang akan dihapus tidak ditemukan.');
    }

    // Kode di bawah ini tidak akan pernah dijalankan selama dd() aktif
    if (Auth::id() === $user->id) {
      return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
    }

    $user->forceDelete();

    return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
  }
  
}