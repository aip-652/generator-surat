<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>{{ $dokumen->nomor_dokumen }}</title>

  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      line-height: 1.6;
    }

    .header {
      text-align: center;
      border-bottom: 2px solid #000;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .header h1 {
      margin: 0;
      font-size: 16px;
      text-transform: uppercase;
    }

    .meta {
      margin-bottom: 20px;
    }

    .meta table {
      width: 100%;
      border-collapse: collapse;
    }

    .meta td {
      padding: 4px 0;
      vertical-align: top;
    }

    .content {
      margin-top: 20px;
      text-align: justify;
      min-height: 300px;
    }

    .footer {
      margin-top: 50px;
      width: 100%;
    }

    .signature {
      float: right;
      text-align: center;
      width: 200px;
    }
  </style>
</head>
<body>

  {{-- HEADER --}}
  <div class="header">
    <h1>Memo Internal</h1>
  </div>

  {{-- META DATA --}}
  <div class="meta">
    <table>
      <tr>
        <td width="120"><strong>Nomor</strong></td>
        <td>: {{ $dokumen->nomor_dokumen }}</td>
      </tr>
      <tr>
        <td><strong>Perihal</strong></td>
        <td>: {{ $dokumen->perihal }}</td>
      </tr>
      <tr>
        <td><strong>Kepada</strong></td>
        <td>: {{ $dokumen->kepada ?? '-' }}</td>
      </tr>
      <tr>
        <td><strong>PIC</strong></td>
        <td>: {{ $dokumen->pic }}</td>
      </tr>
      <tr>
        <td><strong>Tanggal</strong></td>
        <td>: {{ \Carbon\Carbon::parse($dokumen->tanggal)->translatedFormat('d F Y') }}</td>
      </tr>
    </table>
  </div>

  {{-- ISI / BADAN SURAT --}}
  <div class="content">
    {!! nl2br(e($dokumen->badan_surat ?? '')) !!}
  </div>

  {{-- TANDA TANGAN --}}
  <div class="footer">
    <div class="signature">
      <p>Hormat kami,</p>
      <br><br><br>
      <strong>{{ $dokumen->pic }}</strong>
    </div>
  </div>

</body>
</html>
