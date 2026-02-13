<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Dokumen extends Model
{
  use HasFactory, LogsActivity;

  protected $fillable = [
    'jenis_dokumen',

    // ID dokumen
    'nomor_dokumen',
    'kode_surat',
    'tanggal',

    // Tujuan
    'tNama',
    'tJabatan',
    'tPerusahaan',

    // Struktur organisasi
    'unit_kerja',
    'tujuan',
    'dari',
    'order',
    'pic',

    // Konten dokumen
    'perihal',
    'lampiran',
    'tembusan',
    'badan_surat',
  ];

  public function getTembusanArrayAttribute()
{
    if (!$this->tembusan)
        return [];

    return explode(',', $this->tembusan);
}


  public function requestor()
  {
    return $this->belongsTo(User::class, 'pic', 'name');
  }
}
