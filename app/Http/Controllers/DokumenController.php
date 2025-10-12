<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DokumenController extends Controller
{
  // MAPPING BARU: Nama Lengkap => Kode Singkat (digunakan untuk penomoran & disimpan di DB)
  protected $unitKerjaMap = [
    'CEO / COO / CSO / DIRECTOR'  =>  'DIR',
    'Brand Marketing Division'  =>  'MKT',
    'Brand Stratetgic Department'  =>  'BST',
    'Business & Partnership Division'  =>  'BNP',
    'Business Analyst'  =>  'BSA',
    'Business Development'  =>  'BSD',
    'Business Support Department'  =>  'BSP',
    'Community & Partnership'  =>  'COM',
    'Creative Design Department'  =>  'CDS',
    'Digital Marketing Department'  =>  'DMT',
    'EAST Team'  =>  'EAST',
    'Finance & Accounting Department'  =>  'FNA',
    'General Purchasing Section'  =>  'GPR',
    'IT Service Section'  =>  'ICT',
    'Key Account Manager'  =>  'KAM',
    'Material Sourcing & Development'  =>  'MAT',
    'Procurement Division'  =>  'PRC',
    'Product Design Division'  =>  'DSN',
    'Product Development Division'  =>  'PDV',
    'Product Innovation Department'  =>  'PIN',
    'Product Manager'  =>  'PRM',
    'Product Sourcing & Development'  =>  'PRD',
    'Quality Assurance Department'  =>  'QUA',
    'Quaity Control Department'  =>  'QUC',
    'Quality Management Division'  =>  'QUM',
    'SBU Women & Kids'  =>  'WNK',
    'Technical Design Department'  =>  'TDS',
    'Transformation Management Office'  =>  'TMO',
    'Visual Creative Department'  =>  'VIS',

  ];

  // MAPPING BARU: Nama Lengkap => Kode Singkat (digunakan untuk penomoran & disimpan di DB)
  protected $kodeSuratMap = [
    'Surat' => 'S',
    'BA Nego' => 'BA-NEG',
    'Perjanjian' => 'P',
    'Surat Keputusan' => 'SK',
    'Berita Acara' => 'BA',
    'Berita Acara Serah Terima' => 'BAST',
    'Berita Acara Kesepakatan' => 'BAK',
    'Surat Perintah' => 'SPK',
    'Kebijakan' => 'K',
    'Retur Promosi' => 'SRP',
    'Offering Letter' => 'OL',
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
      'pic' => 'required|string',
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
      'pic' => $request->pic, // <-- Ambil dari input
      'email_requestor' => Auth::user()->email, // <-- Ambil otomatis dari user login      
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
      'pic' => 'required|string', // Validasi untuk PIC    
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
      'pic' => $request->pic, // <-- Ambil dari input
      'email_requestor' => Auth::user()->email, // <-- Ambil otomatis dari user login      
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

    // Ganti baris ini
    // $dokumens = Dokumen::query();

    // Menjadi seperti ini
    $query = Dokumen::with('requestor'); // <-- TAMBAHKAN with('requestor')

    if ($filterJenis) {
      $query->where('jenis_dokumen', $filterJenis);
    }

    if ($search) {
      $query->where(function($q) use ($search){
          $q->where('perihal', 'like', '%' . $search . '%')
        ->orWhere('nomor_dokumen', 'like', '%' . $search . '%');
      });
    }

    $query->orderBy($orderBy, $sort);

    $dokumens = $query->paginate(15);

    return view('dokumen.admin', compact('dokumens', 'filterJenis', 'orderBy', 'sort', 'search'));
  }

  public function destroy(Dokumen $dokumen)
  {
    // Hapus record dokumen
    $dokumen->delete();

    // Redirect kembali ke dashboard dengan pesan sukses
    return redirect()->route('dashboard')->with('success', 'Dokumen berhasil dihapus.');
  }
}
