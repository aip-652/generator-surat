<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'action',
    'loggable_id',
    'loggable_type',
    'details',
  ];

  // Relasi ke User (siapa yang melakukan aksi)
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  // Relasi polymorphic (objek apa yang diubah)
  public function loggable()
  {
    return $this->morphTo();
  }
}
