<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // <-- Jangan lupa import model User

class UserSeeder extends Seeder
{
  public function run(): void
  {
    // CONTOH 1: Membuat satu user Admin spesifik
    if (!User::where('email','admin@eigeradventure.id')->exist()){    
      User::create([
      'name' => 'Administrator',
      'email' => 'admin@eigeradventure.id',
      'password' => bcrypt('P@ssw0rd'), // atau Hash::make()
      'role' => 'admin',
    ]);
   }
  }
}
