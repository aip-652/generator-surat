<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $jenis_dokumen
 * @property string|null $unit_kerja
 * @property string|null $kode_surat
 * @property string $nomor_dokumen
 * @property string $perihal
 * @property string|null $kepada
 * @property string|null $alamat
 * @property string $email_requestor
 * @property string $tanggal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $pic
 * @property-read \App\Models\User|null $requestor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereEmailRequestor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereJenisDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereKepada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereKodeSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereNomorDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen wherePerihal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen wherePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereUnitKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Dokumen extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'jenis_dokumen',
    'unit_kerja',
    'kode_surat',
    'nomor_dokumen',
    'perihal',
    'kepada',
    'alamat',
    'pic',
    'email_requestor', // Nama field baru
    'tanggal',
  ];

  public function requestor()
  {
    // 'email_requestor' adalah kolom di tabel 'dokumens'
    // 'email' adalah kolom di tabel 'users'
    return $this->belongsTo(User::class, 'email_requestor', 'email');
  }
}
