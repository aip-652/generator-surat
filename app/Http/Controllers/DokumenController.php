<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DokumenController extends Controller
{
  // MAPPING BARU: Nama Lengkap => Kode Singkat (digunakan untuk penomoran & disimpan di DB)
  protected $unitKerjaMap = [
    'Information Technology' => 'IT',
    'Human Resources Development' => 'HRD',
    'Finance and Accounting' => 'FNA',
    'Marketing Division' => 'MKT',
  ];

  // MAPPING BARU: Nama Lengkap => Kode Singkat (digunakan untuk penomoran & disimpan di DB)
  protected $kodeSuratMap = [
    'Surat Keterangan' => 'SK',
    'Surat Peringatan' => 'SP',
    'Perjanjian Kerja' => 'PK',
    'Keputusan Proyek' => 'KP',
  ];

  /**
   * Tampilkan formulir memo internal.
   */
  public function createMemo()
  {
    // Mengirimkan nama lengkap (keys dari map) ke view
    $unitKerja = array_keys($this->unitKerjaMap);
    return view('dokumen.create_memo', compact('unitKerja'));
  }

  /**
   * Tampilkan formulir surat keluar.
   */
  public function createSuratKeluar()
  {
    // Mengirimkan nama lengkap (keys dari map) ke view
    $kodeSurat = array_keys($this->kodeSuratMap);
    return view('dokumen.create_surat_keluar', compact('kodeSurat'));
  }

  /**
   * Simpan Memo Internal ke database dan generate nomor surat.
   */
  public function storeMemo(Request $request)
  {
    $request->validate([
      'unit_kerja' => 'required|string',
      'perihal' => 'required',
      'kepada' => 'nullable',
      'alamat' => 'nullable',
      'email_requestor' => 'required|email',
    ]);

    // 1. Mengambil kode singkat dari nama lengkap yang dikirim dari form
    $unitKerjaCode = $this->unitKerjaMap[$request->unit_kerja] ?? 'UNKNOWN';

    $tanggal = Carbon::now();
    $bulanRomawi = $this->getRomawi($tanggal->format('m'));
    $tahun = $tanggal->format('Y');

    // 2. Menghitung nomor urut harian berdasarkan unit kerja
    $nomorUrutMemoHariIni = Dokumen::where('jenis_dokumen', 'memo_internal')
      ->where('unit_kerja', $unitKerjaCode)
      ->whereDay('tanggal', $tanggal->format('d'))
      ->whereMonth('tanggal', $tanggal->format('m'))
      ->whereYear('tanggal', $tanggal->format('Y'))
      ->count();

    $nomorUrutTigaDigit = str_pad($nomorUrutMemoHariIni + 1, 3, '0', STR_PAD_LEFT);

    // 3. Membentuk nomor surat: FNA-001.03/X/2025
    $nomorSurat = "{$unitKerjaCode}-{$nomorUrutTigaDigit}.{$tanggal->format('d')}/{$bulanRomawi}/{$tahun}";

    Dokumen::create([
      'jenis_dokumen' => 'memo_internal',
      'unit_kerja' => $unitKerjaCode, // Menyimpan kode singkat
      'nomor_dokumen' => $nomorSurat,
      'perihal' => $request->perihal,
      'kepada' => $request->kepada,
      'alamat' => $request->alamat,
      'email_requestor' => $request->email_requestor,
      'tanggal' => $tanggal->format('Y-m-d'),
    ]);

    return redirect()->back()->with('success', 'Memo Internal berhasil dibuat dengan nomor: ' . $nomorSurat);
  }

  /**
   * Simpan Surat Keluar ke database dan generate nomor surat.
   */
  public function storeSuratKeluar(Request $request)
  {
    $request->validate([
      'kode_surat' => 'required|string',
      'perihal' => 'required',
      'kepada' => 'nullable',
      'alamat' => 'nullable',
      'email_requestor' => 'required|email',
    ]);

    // 1. Mengambil kode singkat dari nama lengkap yang dikirim dari form
    $kodeSuratCode = $this->kodeSuratMap[$request->kode_surat] ?? 'UNKNOWN';

    $tanggal = Carbon::now();
    $bulanRomawi = $this->getRomawi($tanggal->format('m'));
    $tahun = $tanggal->format('Y');

    // 2. Menghitung nomor urut harian berdasarkan kode surat
    $nomorUrutHariIni = Dokumen::where('jenis_dokumen', 'surat_keluar')
      ->where('kode_surat', $kodeSuratCode)
      ->whereDate('tanggal', $tanggal->toDateString()) // <-- KUNCI PERUBAHANNYA DI SINI
      ->count();

    $nomorUrut = $nomorUrutHariIni + 1;
    $nomorUrutTigaDigit = str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

    // 3. Membentuk nomor surat: SK-001.03/72425/X/2025
    $nomorSurat = "{$kodeSuratCode}-{$nomorUrutTigaDigit}.{$tanggal->format('d')}/72425/{$bulanRomawi}/{$tahun}";

    Dokumen::create([
      'jenis_dokumen' => 'surat_keluar',
      'kode_surat' => $kodeSuratCode, // Menyimpan kode singkat
      'nomor_dokumen' => $nomorSurat,
      'perihal' => $request->perihal,
      'kepada' => $request->kepada,
      'alamat' => $request->alamat,
      'email_requestor' => $request->email_requestor,
      'tanggal' => $tanggal->format('Y-m-d'),
    ]);

    return redirect()->back()->with('success', 'Surat Keluar berhasil dibuat dengan nomor: ' . $nomorSurat);
  }

  /**
   * Helper untuk mengubah angka bulan menjadi Romawi.
   */
  private function getRomawi($bulan)
  {
    $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    return $romawi[(int)$bulan];
  }

  /**
   * Tampilkan halaman admin.
   */
  public function adminDashboard(Request $request)
  {
    $filterJenis = $request->input('jenis');
    $orderBy = $request->input('order_by', 'created_at');
    $sort = $request->input('sort', 'desc');
    $search = $request->input('search');

    $dokumens = Dokumen::query();

    if ($filterJenis) {
      $dokumens->where('jenis_dokumen', $filterJenis);
    }

    if ($search) {
      $dokumens->where('perihal', 'like', '%' . $search . '%')
        ->orWhere('nomor_dokumen', 'like', '%' . $search . '%');
    }

    $dokumens->orderBy($orderBy, $sort);

    $dokumens = $dokumens->paginate(15);

    return view('dokumen.admin', compact('dokumens', 'filterJenis', 'orderBy', 'sort', 'search'));
  }
}
