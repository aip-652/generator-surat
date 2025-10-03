<!DOCTYPE html>
<html>

<head>
  <title>Buat Memo Internal</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
  <div class="container mt-5">
    <a href="/" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>
    <h2>Formulir Memo Internal</h2>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form action="{{ route('dokumen.store.memo') }}" method="POST">
      @csrf
      <div class="form-group">
        <label>Unit Kerja (Nama Lengkap):</label>
        <select name="unit_kerja" class="form-control" required>
          <!-- Menampilkan nama lengkap, mengirim nama lengkap (akan diubah jadi kode singkat di Controller) -->
          @foreach($unitKerja as $unit)
          <option value="{{ $unit }}" {{ old('unit_kerja') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Perihal:</label>
        <input type="text" name="perihal" class="form-control" value="{{ old('perihal') }}" required>
      </div>
      <div class="form-group">
        <label>Kepada:</label>
        <input type="text" name="kepada" class="form-control" value="{{ old('kepada') }}">
      </div>
      <div class="form-group">
        <label>Alamat:</label>
        <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}">
      </div>
      <div class="form-group">
        <label>Email Requestor:</label>
        <input type="email" name="email_requestor" class="form-control" value="{{ old('email_requestor') }}" required>
      </div>
      <button type="submit" class="btn btn-primary">Buat Memo</button>
    </form>
  </div>
</body>

</html>