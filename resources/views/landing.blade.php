<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aplikasi Manajemen Dokumen</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <style>
    .card-link {
      text-decoration: none;
      color: inherit;
    }

    .card {
      transition: transform 0.2s ease-in-out;
    }

    .card:hover {
      transform: scale(1.05);
    }
  </style>
</head>

<body>
  <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="text-center">
      <h1 class="mb-4">Dashboard Utama</h1>
      <p class="lead mb-5">Pilih salah satu menu di bawah ini untuk melanjutkan.</p>

      <div class="row">
        <div class="col-md-4 mb-4">
          <a href="{{ route('dokumen.create.memo') }}" class="card-link">
            <div class="card p-4 shadow">
              <h3><i class="fas fa-file-alt"></i> Memo Internal</h3>
              <p class="mb-0">Buat memo baru</p>
            </div>
          </a>
        </div>
        <div class="col-md-4 mb-4">
          <a href="{{ route('dokumen.create.surat') }}" class="card-link">
            <div class="card p-4 shadow">
              <h3><i class="fas fa-envelope"></i> Surat Keluar</h3>
              <p class="mb-0">Buat surat keluar baru</p>
            </div>
          </a>
        </div>
        <div class="col-md-4 mb-4">
          <a href="{{ route('admin.dashboard') }}" class="card-link">
            <div class="card p-4 shadow">
              <h3><i class="fas fa-user-shield"></i> Admin Dashboard</h3>
              <p class="mb-0">Lihat semua dokumen</p>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>