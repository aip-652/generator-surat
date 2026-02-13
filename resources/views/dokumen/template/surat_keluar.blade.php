<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        @font-face {
            font-family: 'TT Commons';
            src: url("{{ public_path('fonts/tt-commons/TT-Commons-Regular.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'TT Commons';
            src: url("{{ public_path('fonts/tt-commons/TT-Commons-Bold.ttf') }}") format('truetype');
            font-weight: bold;
        }

        @font-face {
            font-family: 'TT Commons';
            src: url("{{ public_path('fonts/tt-commons/TT-Commons-Italic.ttf') }}") format('truetype');
            font-style: italic;
        }

        @page {
            margin-top: 180px;
            margin-bottom: 60px;
            margin-left: 0.6in;
            margin-right: 0.6in;
        }

        b {
            font-size: 11pt;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'TT Commons', sans-serif;
            font-size: 12pt;
        }

        /* HEADER */
        .pdf-header {
            position: fixed;
            top: -110px;
            left: 0;
            right: 0;
        }

        .pdf-header img {
            width: 100%;
            display: block;
        }

        /* FOOTER */
        .pdf-footer {
            position: fixed;
            bottom: -40px;
            right: 0;
            left: 0;
        }

        .pdf-footer img {
            width: 100%;
            display: block;
        }

        /* TABLE INFO */
        .surat-table {
            font-size: 12pt;
            line-height: 0.7;
            border-collapse: collapse;
            margin-top: -15px;
        }

        .surat-table td {
            vertical-align: top;
        }

        .tujuan-table {
            font-size: 12pt;
            line-height: 1;
            border-collapse: collapse;
        }

        /* BODY SURAT */
        .badan-surat {
            font-size: 12pt;
            line-height: 1.1;
            text-align: justify;
            margin-top: 15pt;
        }

        /* SIGNATURE */
        .ttd-area {
            margin-top: 40px;
        }

        /* TEMBUSAN */
        .tembusan {
            margin-top: 15pt;
            padding-left: 15pt;
            font-size: 12pt;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="pdf-header">
        <img src="{{ public_path('images/header-out.jpg') }}">
    </div>

    {{-- FOOTER --}}
    <div class="pdf-footer">
        <img src="{{ public_path('images/footer-out.jpg') }}">
    </div>

    {{-- TANGGAL --}}
    <div style="text-align:right; margin-bottom:10pt;">
        Bandung, {{ \Carbon\Carbon::parse($dokumen->tanggal)->translatedFormat('d F Y') }}
    </div>

    {{-- DATA SURAT --}}
    <table width="100%" class="surat-table">

        <tr>
            <td width="10%">Nomor</td>
            <td width="3%">:</td>
            <td>{{ $dokumen->nomor_dokumen }}</td>
        </tr>

        <tr>
            <td>Perihal</td>

            <td>:</td>
            <td>{{ $dokumen->perihal }}</td>
        </tr>

        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>{{ $dokumen->lampiran ?? '-' }}</td>
        </tr>

    </table>

    <br>

    {{-- TUJUAN --}}
    <div>
        <b>Kepada yth.</b><br>
        <table width="100%" class="tujuan-table">
            <tr>
              <td>{!! nl2br(e($dokumen->tNama)) !!}</td>
            </tr>
            <tr>
              <td>{!! nl2br(e($dokumen->tJabatan)) !!}</td>
            </tr>
            <tr>
              <td>{!! nl2br(e($dokumen->tujuan)) !!}</td>
            </tr>
            <tr>
              <td>{!! nl2br(e($dokumen->tPerusahaan)) !!}</td>
            </tr>
            <tr>
              <td>Di Tempat</td>
            </tr>
        </table>
    </div>


    {{-- ISI SURAT --}}
    <div class="badan-surat">
        @foreach (preg_split("/\n\s*\n/", trim($dokumen->badan_surat)) as $p)
            <p>{{ $p }}</p>
        @endforeach
    </div>

    {{-- TTD --}}
    <table width="100%" style="margin-top:10pt;">
        <tr>

            <td width="40%" valign="top">
                <p>Hormat kami,</p>

                <br>

                <div class="ttd-area">
                    <b>{{ $dokumen->order }}</b><br>
                    <b>{{ $dokumen->dari }}</b><br>
                    {{ $dokumen->jabatan ?? '' }}
                </div>
            </td>

        </tr>
    </table>

    <div class="tembusan">
        <div style="margin-bottom:2px;">Tembusan:</div>

        <table style="margin-top:0; border-spacing:0; line-height: 1;">
            @foreach ($dokumen->tembusan_array as $i => $tembusan)
                <tr>
                    <td style="width:5px; vertical-align:top; ">
                        {{ $i + 1 }}.
                    </td>
                    <td style="padding:0;">
                        {{ $tembusan }}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>


</body>

</html>
