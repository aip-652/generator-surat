<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\RedirectMiddleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- Import Hash
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;      // <-- Import Rule
use App\Models\AdminLog;             // <-- Import AdminLog

class UserController extends Controller
{
  /**
   * Menampilkan daftar semua user dengan sorting.
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
   * Tampilkan formulir untuk membuat user baru.
   */
  public function create()
  {
    return view('users.create');
  }

  /**
   * Simpan user baru ke database.
   */
  public function store(Request $request)
  {
    // 1. Validasi input
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password' => ['required', 'string', 'min:8'],
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
    return redirect()->route('users.index')->with('success', 'user baru berhasil ditambahkan.');
  }
  /**
   * Tampilkan formulir untuk mengedit user.
   */
  public function edit(User $user) // Laravel akan otomatis mencari user berdasarkan ID
  {
    return view('users.edit', compact('user'));
  }

  /**
   * Update data user di database.
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

    // AdminLog::create([
    //   'user_id' => Auth::id(),
    //   'action' => 'updated',
    //   'loggable_id' => $user->id,
    //   'loggable_type' => User::class,
    //   'details' => "Data user '{$user->name}' diperbarui.",
    // ]);

    // 3. Redirect kembali dengan pesan sukses
    return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
  }

  public function destroy($id): RedirectResponse
  {
    // 1. Cari user secara manual. Jika tidak ketemu, akan gagal dengan error 404.
    $user = User::find($id);

    // 2. Jika karena suatu alasan user tidak ditemukan, kembali dengan pesan error.
    if (!$user) {
      return redirect()->route('users.index')->with('error', 'Gagal: user yang akan dihapus tidak ditemukan.');
    }

    // 3. Cek jika admin mencoba menghapus dirinya sendiri.
    if (Auth::id() == $user->id) {
      return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
    }

    // 4. Hapus user secara permanen.
    $user->forceDelete();

    // AdminLog::create([
    //   'user_id' => Auth::id(),
    //   'action' => 'deleted',
    //   'loggable_id' => $user->id,
    //   'loggable_type' => User::class,
    //   'details' => "user '{$user->name}' dihapus.",
    // ]);

    // 5. Kembali dengan pesan sukses.
    return redirect()->route('users.index')->with('success', 'user berhasil dihapus secara permanen.');
  }
}
