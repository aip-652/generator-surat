<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // <-- Jangan lupa import model User

class UserSeeder extends Seeder
{
  public function run(): void
  {
    // CONTOH 1: Membuat satu user Admin spesifik
    User::create([
      'name' => 'Administrator',
      'email' => 'admin@eigeradventure.id',
      'password' => bcrypt('P@ssw0rd'), // atau Hash::make()
      'role' => 'admin',
    ]);

    // CONTOH 2: Membuat 10 user biasa secara acak menggunakan factory
    User::factory()->count(10)->create();
  }
}
