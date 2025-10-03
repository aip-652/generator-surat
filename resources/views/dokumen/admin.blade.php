<!DOCTYPE html>
<html>

<head>
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <style>
    .filter-form {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .table-responsive {
      overflow-x: auto;
    }
  </style>
</head>

<body>
  <div class="container-fluid mt-5">
    <h2>Daftar Dokumen</h2>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <!-- Contoh link/tombol kembali atau logout -->
      <a href="{{ url('/') }}" class="btn btn-secondary">← Buat Dokumen Baru</a>
      <!-- Jika Anda memiliki rute logout yang sebenarnya, gunakan ini: -->
      <!-- <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-danger">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form> -->
    </div>

    <!-- Formulir Filter dan Pencarian -->
    <form action="{{ route('admin.dashboard') }}" method="GET" class="filter-form mb-4">
      <label for="jenis">Filter Jenis Dokumen:</label>
      <select name="jenis" id="jenis" class="form-control" onchange="this.form.submit()">
        <option value="">Semua</option>
        <option value="memo_internal" {{ ($filterJenis ?? null) == 'memo_internal' ? 'selected' : '' }}>Memo Internal</option>
        <option value="surat_keluar" {{ ($filterJenis ?? null) == 'surat_keluar' ? 'selected' : '' }}>Surat Keluar</option>
      </select>
      <label for="search">Cari:</label>
      <input type="text" name="search" id="search" class="form-control" placeholder="Cari perihal atau nomor surat..." value="{{ $search ?? '' }}">
      <button type="submit" class="btn btn-primary">Cari</button>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <!-- Kolom No -->
            <th>No</th>

            <!-- Kolom Jenis (Bisa diurutkan) -->
            <th>
              Jenis
              <a href="{{ route('admin.dashboard', ['jenis' => $filterJenis ?? '', 'search' => $search ?? '', 'order_by' => 'jenis_dokumen', 'sort' => ($orderBy == 'jenis_dokumen' && $sort == 'asc') ? 'desc' : 'asc']) }}">
                &uarr;&darr;
              </a>
            </th>

            <!-- Kolom Nomor Surat (Bisa diurutkan) -->
            <th>
              Nomor Surat
              <a href="{{ route('admin.dashboard', ['jenis' => $filterJenis ?? '', 'search' => $search ?? '', 'order_by' => 'nomor_dokumen', 'sort' => ($orderBy == 'nomor_dokumen' && $sort == 'asc') ? 'desc' : 'asc']) }}">
                &uarr;&darr;
              </a>
            </th>

            <!-- Kolom Tanggal (Bisa diurutkan) -->
            <th>
              Tanggal
              <a href="{{ route('admin.dashboard', ['jenis' => $filterJenis ?? '', 'search' => $search ?? '', 'order_by' => 'tanggal', 'sort' => ($orderBy == 'tanggal' && $sort == 'asc') ? 'desc' : 'asc']) }}">
                &uarr;&darr;
              </a>
            </th>

            <!-- Kolom Perihal (Bisa diurutkan) -->
            <th>
              Perihal
              <a href="{{ route('admin.dashboard', ['jenis' => $filterJenis ?? '', 'search' => $search ?? '', 'order_by' => 'perihal', 'sort' => ($orderBy == 'perihal' && $sort == 'asc') ? 'desc' : 'asc']) }}">
                &uarr;&darr;
              </a>
            </th>

            <!-- Kolom Kepada (Bisa diurutkan) -->
            <th>
              Kepada
              <a href="{{ route('admin.dashboard', ['jenis' => $filterJenis ?? '', 'search' => $search ?? '', 'order_by' => 'kepada', 'sort' => ($orderBy == 'kepada' && $sort == 'asc') ? 'desc' : 'asc']) }}">
                &uarr;&darr;
              </a>
            </th>

            <!-- Kolom Alamat (Bisa diurutkan) -->
            <th>
              Alamat
              <a href="{{ route('admin.dashboard', ['jenis' => $filterJenis ?? '', 'search' => $search ?? '', 'order_by' => 'alamat', 'sort' => ($orderBy == 'alamat' && $sort == 'asc') ? 'desc' : 'asc']) }}">
                &uarr;&darr;
              </a>
            </th>

            <!-- Kolom Email Requestor (Bisa diurutkan) -->
            <th>
              Email Requestor
              <a href="{{ route('admin.dashboard', ['jenis' => $filterJenis ?? '', 'search' => $search ?? '', 'order_by' => 'email_requestor', 'sort' => ($orderBy == 'email_requestor' && $sort == 'asc') ? 'desc' : 'asc']) }}">
                &uarr;&darr;
              </a>
            </th>

          </tr>
        </thead>
        <tbody>
          @if(($dokumens ?? collect())->isEmpty())
          <!-- Total Kolom sekarang adalah 8 -->
          <tr>
            <td colspan="8" class="text-center">Tidak ada data dokumen.</td>
          </tr>
          @else
          @foreach($dokumens as $dokumen)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $dokumen->jenis_dokumen == 'memo_internal' ? 'Memo Internal' : 'Surat Keluar' }}</td>
            <td>{{ $dokumen->nomor_dokumen }}</td>
            <!-- Format Tanggal DD/MM/YYYY menggunakan Carbon -->
            <td>{{ \Carbon\Carbon::parse($dokumen->tanggal)->format('d/m/Y') }}</td>
            <td>{{ $dokumen->perihal }}</td>
            <td>{{ $dokumen->kepada }}</td>
            <td>{{ $dokumen->alamat }}</td>
            <td>{{ $dokumen->email_requestor }}</td>
          </tr>
          @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>