<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminLog;
use Barryvdh\DomPDF\Facade\Pdf;

class DokumenController extends Controller
{
    protected $unitKerjaMap = [
        'CEO / COO / CSO / DIRECTOR' => 'DIR',
        'Brand Marketing Division' => 'MKT',
        'Brand Stratetgic Department' => 'BST',
        'Business & Partnership Division' => 'BNP',
        'Business Analyst' => 'BSA',
        'Business Development' => 'BSD',
        'Business Support Department' => 'BSP',
        'Community & Partnership' => 'COM',
        'Creative Design Department' => 'CDS',
        'Digital Marketing Department' => 'DMT',
        'EAST Team' => 'EAST',
        'Finance & Accounting Department' => 'FNA',
        'General Purchasing Section' => 'GPR',
        'IT Service Section' => 'ICT',
        'Key Account Manager' => 'KAM',
        'Material Sourcing & Development' => 'MAT',
        'Procurement Division' => 'PRC',
        'Product Design Division' => 'DSN',
        'Product Development Division' => 'PDV',
        'Product Innovation Department' => 'PIN',
        'Product Manager' => 'PRM',
        'Product Sourcing & Development' => 'PRD',
        'Quality Assurance Department' => 'QUA',
        'Quaity Control Department' => 'QUC',
        'Quality Management Division' => 'QUM',
        'SBU Women & Kids' => 'WNK',
        'Technical Design Department' => 'TDS',
        'Transformation Management Office' => 'TMO',
        'Visual Creative Department' => 'VIS',
    ];

    public array $jabatanChoices = ['Apparel & Headwear Product Line Manager', 'Brand & Marketing Operation General Manager', 'Brand Communication & Partnership Manager', 'Brand Creative & Visual Production Manager', 'Brand Product Marketing Manager', 'Corporate Secretary Manager', 'Creative Lifestyle Design Manager', 'Creative Performance Design Manager', 'Finance & Accounting Manager', 'Footwear & Equipment Product Line Manager', 'Human Capital & General Affair Manager', 'Material Development Manager', 'Product Engineering & Innovation General Manager', 'Product Manager Eiger Active & Women Series', 'Product Manager Eiger Junior & Teen', 'Product Manager Eiger Mountaineering', 'Product Manager Eiger Riding', 'Product Manager Eiger Tactical', 'Quality Assurance Manager', 'Research & Data Analytics Manager', 'Sr Product Manager', 'Supply Chain General Manager', 'Technical Design Manager'];

    protected $kodeSuratMap = [
        'Surat' => 'S',
        'BA Nego' => 'BA-NEG',
        'Perjanjian' => 'P',
        'Surat Keputusan' => 'SK',
        'Berita Acara' => 'BA',
        'Berita Acara Serah Terima' => 'BAST',
        'Berita Acara Kesepakatan' => 'BAK',
        'Surat Perintah Kerja' => 'SPK',
        'Kebijakan' => 'K',
        'Retur Promosi' => 'SRP',
        'Offering Letter' => 'OL',
    ];

    /**
     * Tampilkan formulir memo internal.
     */
    public function createMemo()
    {
        $unitKerja = array_keys($this->unitKerjaMap);
        $tujuans = $this->jabatanChoices;
        $daris = $this->jabatanChoices;
        $tembusans = $this->jabatanChoices;
        return view('dokumen.create_memo', compact('unitKerja', 'tujuans', 'daris', 'tembusans'));
    }

    /**
     * Tampilkan formulir surat keluar.
     */
    public function createSuratKeluar()
    {
        $kodeSurat = array_keys($this->kodeSuratMap);
        $daris = $this->jabatanChoices;
        $tembusans = $this->jabatanChoices;
        return view('dokumen.create_surat_keluar', compact('kodeSurat', 'daris', 'tembusans'));
    }

    /**
     * Simpan Memo Internal ke database dan generate nomor surat.
     */
    public function storeMemo(Request $request)
    {
        $request->validate([
            'unit_kerja' => 'required|string',
            'perihal' => 'required|string',
            'tujuan' => 'nullable|string',
            'dari' => 'nullable|string',
            'order' => 'nullable|string',
            'lampiran' => 'nullable|string',
            'tembusan' => 'nullable|string',
            'badan_surat' => 'nullable|string',
        ]);

        // 1. Mengambil kode singkat dari nama lengkap yang dikirim dari form
        $unitKerjaCode = $this->unitKerjaMap[$request->unit_kerja] ?? 'UNKNOWN';

        $tanggal = Carbon::now();
        $bulanRomawi = $this->getRomawi($tanggal->format('m'));
        $tahun = $tanggal->format('Y');

        // 2. Menghitung nomor urut harian berdasarkan unit kerja
        $nomorUrutMemoHariIni = Dokumen::where('jenis_dokumen', 'memo_internal')->where('unit_kerja', $unitKerjaCode)->whereDay('tanggal', $tanggal->format('d'))->whereMonth('tanggal', $tanggal->format('m'))->whereYear('tanggal', $tanggal->format('Y'))->count();

        $nomorUrutTigaDigit = str_pad($nomorUrutMemoHariIni + 1, 3, '0', STR_PAD_LEFT);

        // 3. Membentuk nomor surat: FNA-001.03/X/2025
        $nomorSurat = "{$unitKerjaCode}-{$nomorUrutTigaDigit}.{$tanggal->format('d')}/{$bulanRomawi}/{$tahun}";

        Dokumen::create([
            'jenis_dokumen' => 'memo_internal',
            'unit_kerja' => $unitKerjaCode,
            'nomor_dokumen' => $nomorSurat,
            'tujuan' => $request->tujuan,
            'dari' => $request->dari,
            'lampiran' => $request->lampiran,
            'tembusan' => $request->tembusan,
            'perihal' => $request->perihal,
            'order' => $request->order,
            'pic' => Auth::user()->name,
            'badan_surat' => $request->badan_surat,
            'tanggal' => $tanggal->format('Y-m-d'),
        ]);

        return redirect()
            ->route('dokumen.create.memo')
            ->with([
                'success' => 'Memo Internal berhasil dibuat dengan nomor: ' . $nomorSurat,
                'nomor_surat' => $nomorSurat,
            ]);
    }

    /**
     * Simpan Surat Keluar ke database dan generate nomor surat.
     */
    public function storeSuratKeluar(Request $request)
    {
        $request->validate([
            'kode_surat' => 'required|string',
            'perihal' => 'required|string',
            'tNama' => 'nullable|string',
            'tJabatan' => 'nullable|string',
            'tujuan' => 'nullable|string',
            'tPerusahaan' => 'nullable|string',
            'dari' => 'nullable|string',
            'order' => 'nullable|string',
            'lampiran' => 'nullable|string',
            'tembusan' => 'nullable|array',
            'badan_surat' => 'nullable|string',
            'tanggal_manual' => 'nullable|date',
        ]);

        // 1. Mengambil kode singkat dari nama lengkap yang dikirim dari form
        $kodeSuratCode = $this->kodeSuratMap[$request->kode_surat] ?? 'UNKNOWN';

        // Jika kode surat Perjanjian atau Surat Keterangan, gunakan tanggal dari input
        if (in_array($request->kode_surat, ['Perjanjian', 'Surat Perintah Kerja']) && $request->filled('tanggal_manual')) {
            $tanggal = Carbon::parse($request->tanggal_manual);
        } else {
            $tanggal = Carbon::now();
        }

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
            'tNama' => $request->tNama,
            'tJabatan' => $request->tJabatan,
            'tujuan' => $request->tujuan,
            'tPerusahaan' => $request->tPerusahaan,
            'dari' => $request->dari,
            'lampiran' => $request->lampiran,
            'tembusan' => collect($request->tembusan)->filter()->implode(', '),
            'perihal' => $request->perihal,
            'order' => $request->order, // <-- Ambil dari input
            'pic' => Auth::user()->name, // <-- Ambil otomatis dari user login
            'badan_surat' => $request->badan_surat,
            'tanggal' => $tanggal->format('Y-m-d'),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Surat Keluar berhasil dibuat dengan nomor: ' . $nomorSurat);
    }

    /**
     * Helper untuk mengubah angka bulan menjadi Romawi.
     */
    private function getRomawi($bulan)
    {
        $romawi = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        return $romawi[(int) $bulan];
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

        // Menjadi seperti ini
        $query = Dokumen::with('requestor'); // <-- TAMBAHKAN with('requestor')

        if ($filterJenis) {
            $query->where('jenis_dokumen', $filterJenis);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('perihal', 'like', '%' . $search . '%')->orWhere('nomor_dokumen', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy($orderBy, $sort);

        $dokumens = $query->paginate(15);

        return view('dokumen.dashboard', compact('dokumens', 'filterJenis', 'orderBy', 'sort', 'search'));
    }

    public function destroy(Dokumen $dokumen)
    {
        // Hapus record dokumen
        $dokumen->delete();

        // Redirect kembali ke dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function edit(Dokumen $dokumen)
    {
        $tujuans = $this->jabatanChoices;
        $daris = $this->jabatanChoices;
        $tembusans = $this->jabatanChoices;

        // Kirim data dokumen ke view
        if ($dokumen->jenis_dokumen === 'surat_keluar') {
            return view('dokumen.edit_surat_keluar', compact('dokumen', 'tembusans', 'daris'));
        }

        return view('dokumen.edit_memo', compact('dokumen', 'tujuans', 'daris', 'tembusans'));
    }

    public function update(Request $request, Dokumen $dokumen)
    {
        /*
    |--------------------------------------------------------------------------
    | Validasi Berdasarkan Jenis Dokumen
    |--------------------------------------------------------------------------
    */

        if ($dokumen->jenis_dokumen === 'memo_internal') {
            $validated = $request->validate([
                'perihal' => 'required|string|max:255',
                'tujuan' => 'nullable|string|max:255',
                'badan_surat' => 'required|string',
                'tembusan' => 'nullable|string',
                'order' => 'nullable|string',
            ]);
        } elseif ($dokumen->jenis_dokumen === 'surat_keluar') {
            $validated = $request->validate([
                'tNama' => 'nullable|string',
                'tJabatan' => 'nullable|string',
                'tujuan' => 'nullable|string',
                'tPerusahaan' => 'nullable|string',
                'perihal' => 'required|string',
                'dari' => 'nullable|string',
                'order' => 'nullable|string',
                'lampiran' => 'nullable|string',
                'tembusan' => 'nullable|string',
                'badan_surat' => 'nullable|string',
            ]);
        } else {
            abort(404, 'Jenis dokumen tidak dikenali');
        }

        /*
    |--------------------------------------------------------------------------
    | Parsing Tembusan (delimiter koma)
    |--------------------------------------------------------------------------
    */

        if (!empty($validated['tembusan'])) {
            $validated['tembusan'] = collect(explode(',', $validated['tembusan']))
                ->map(fn($item) => trim($item))
                ->filter()
                ->implode(', ');
        }

        /*
    |--------------------------------------------------------------------------
    | Simpan Data Lama
    |--------------------------------------------------------------------------
    */

        $oldData = $dokumen->getOriginal();

        /*
    |--------------------------------------------------------------------------
    | Update Dokumen
    |--------------------------------------------------------------------------
    */

        $dokumen->update($validated);

        /*
    |--------------------------------------------------------------------------
    | Logging Perubahan (Optional Tapi Bagus)
    |--------------------------------------------------------------------------
    */

        $changes = [];

        foreach ($dokumen->getChanges() as $key => $value) {
            if ($key === 'updated_at') {
                continue;
            }

            $oldValue = $oldData[$key] ?? '-';

            // Sembunyikan konten besar
            if ($key === 'badan_surat') {
                $changes[] = "kolom '{$key}' diubah (isi tidak ditampilkan)";
            } else {
                $changes[] = "kolom '{$key}' dari '{$oldValue}' menjadi '{$value}'";
            }
        }

        if (!empty($changes)) {
            $detailLog = "Dokumen '{$dokumen->nomor_dokumen}' diperbarui: " . implode(', ', $changes);

            \Log::info($detailLog);
        }

        /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

        return redirect()->route('dashboard')->with('success', 'Dokumen berhasil diperbarui');
    }

    /**
     * Simpan dokumen backdate dengan penomoran khusus.
     */
    public function storeBackdate(Request $request)
    {
        $request->validate([
            'tanggal_backdate' => 'required|date|before:today',
            'jenis_dokumen' => 'required|in:memo_internal,surat_keluar',
            'kode_spesifik' => 'required|string',
            'perihal' => 'required|string',
            'kepada' => 'nullable|string',
            'order' => 'nullable|string',
        ]);

        $tanggal = Carbon::parse($request->tanggal_backdate);
        $bulanRomawi = $this->getRomawi($tanggal->month);
        $tahun = $tanggal->year;

        $nomorSurat = '';
        $dataToCreate = [
            'jenis_dokumen' => $request->jenis_dokumen,
            'perihal' => $request->perihal,
            'kepada' => $request->kepada,
            'order' => $request->order,
            'pic' => Auth::user()->name,
            'tanggal' => $tanggal->toDateString(),
        ];

        if ($request->jenis_dokumen == 'memo_internal') {
            $unitKerjaCode = $this->unitKerjaMap[$request->kode_spesifik] ?? 'UNKNOWN';

            $nomorUrutHariItu = Dokumen::where('jenis_dokumen', 'memo_internal')->where('unit_kerja', $unitKerjaCode)->whereDate('tanggal', $tanggal->toDateString())->count();

            // LOGIKA BARU: Tambahkan '1' di belakang nomor urut
            $nomorUrutEmpatDigit = str_pad($nomorUrutHariItu + 1, 3, '0', STR_PAD_LEFT) . '1';

            $nomorSurat = "{$unitKerjaCode}-{$nomorUrutEmpatDigit}.{$tanggal->format('d')}/{$bulanRomawi}/{$tahun}";
            $dataToCreate['unit_kerja'] = $unitKerjaCode;
        } elseif ($request->jenis_dokumen == 'surat_keluar') {
            $kodeSuratCode = $this->kodeSuratMap[$request->kode_spesifik] ?? 'UNKNOWN';

            $nomorUrutHariItu = Dokumen::where('jenis_dokumen', 'surat_keluar')
                ->where('kode_surat', $kodeSuratCode)
                ->whereDate('tanggal', '!=', Carbon::now()->toDateString()) // Hitung semua backdate sebelumnya
                ->count();

            // LOGIKA BARU: Tambahkan '1' di belakang nomor urut
            $nomorUrutEmpatDigit = str_pad($nomorUrutHariItu + 1, 3, '0', STR_PAD_LEFT) . '1';

            $nomorSurat = "{$kodeSuratCode}-{$nomorUrutEmpatDigit}.{$tanggal->format('d')}/72425/{$bulanRomawi}/{$tahun}";
            $dataToCreate['kode_surat'] = $kodeSuratCode;
        }

        $dataToCreate['nomor_dokumen'] = $nomorSurat;
        Dokumen::create($dataToCreate);

        return redirect()
            ->route('dokumen.create.backdate')
            ->with('success', 'Dokumen backdate berhasil dibuat dengan nomor: ' . $nomorSurat);
    }

    public function createBackdate()
    {
        $unitKerja = array_keys($this->unitKerjaMap);
        $kodeSurat = array_keys($this->kodeSuratMap);
        return view('dokumen.create_backdate', compact('unitKerja', 'kodeSurat'));
    }

    public function downloadPdf(Dokumen $dokumen)
    {
        $safeFilename = str_replace(['/', '\\'], '-', $dokumen->nomor_dokumen);

        // Mapping template
        $templateMap = [
            'memo_internal' => 'dokumen.template.memo',
            'surat_keluar' => 'dokumen.template.surat_keluar',
        ];

        $view = $templateMap[$dokumen->jenis_dokumen] ?? 'dokumen.template.memo';

        $pdf = Pdf::loadView($view, compact('dokumen'))->setPaper('A4', 'portrait');

        return request()->boolean('preview')
            ? response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="dokumen-' . $safeFilename . '.pdf"')
            : $pdf->download('dokumen-' . $safeFilename . '.pdf');
    }
}
