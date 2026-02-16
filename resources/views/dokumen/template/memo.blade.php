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
            margin-top: 220px;
            /* ruang header */
            margin-bottom: 60px;
            margin-left: 0.6in;
            margin-right: 0.6in;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'TT Commons', sans-serif;
            font-size: 11pt;
        }

        .memo-table {
            font-size: 11pt;
            line-height: 1;
            border-collapse: collapse;
        }

        .memo-table td {
            //margin-top:10px;
            //padding: 1pt 0;
            vertical-align: top;
        }

        .memo-table {
            margin-top: -15px;
        }

        .badan-surat {
            margin: 0 0 0 0;
            font-size: 11pt;
            line-height: 1;
            text-align: justify;
        }

        .disposisi-box {
            //white-space: pre-line;
            border: 1px solid #000;
            padding: 3pt 3pt;
            font-size: 12pt;
        }

        .disposisi-area {
            height: 75pt;
        }

        .pdf-header {
            position: fixed;
            top: -180px;
            left: 0;
            right: 0;
        }

        .pdf-footer {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            font-size: 9pt;
            text-align: right;
        }

        .pdf-header img {
            width: 100%;
            height: auto;
            display: block;
        }

        b,
        strong {
            font-weight: 500;
        }
    </style>
</head>

<body>

    {{-- HEADER GAMBAR --}}
    <div class="pdf-header">
        <img src="{{ public_path('images/header-memo.jpg') }}" alt="Header Memo">
    </div>

    <!-- Footer -->
    <div class="pdf-footer">
        <i>FR001 Rev1 Dec 2025</i>
    </div>


    {{-- ISI MEMO --}}
    <table width="100%" class="memo-table">
        <tr>
            <td width="15%">Kepada Yth</td>
            <td width="5%">:</td>
            <td>{{ $dokumen->tujuan }}</td>
        </tr>
        <tr>
            <td>Dari</td>
            <td>:</td>
            <td>{{ $dokumen->dari }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($dokumen->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td>:</td>
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
        <tr>
            <td>Tembusan</td>
            <td>:</td>
            <td>{{ $dokumen->tembusan ?? '-' }}</td>
        </tr>
    </table>


    <hr style="width:100%; margin-top:10px;">

    <div class="badan-surat">
        {!! $dokumen->badan_surat !!}
    </div>

    <table width="100%" style="margin-top:10pt;">
        <tr>
            <!-- KIRI: TANDA TANGAN -->
            <td width="40%" valign="top">
                <p>Hormat kami,</p>

                <br>

                <div style="margin-top:15px;">
                    <b><u>{{ $dokumen->order }}</u></b><br>
                    <b>{{ $dokumen->dari }}</b>
                </div>
            </td>

            <!-- KANAN: CATATAN / DISPOSISI -->
            <td width="60%" valign="top">
                <div class="disposisi-box">
                    <div style="margin-bottom:4pt;">
                        Catatan/Disposisi:
                    </div>
                    <div class="disposisi-area"></div>
                </div>
            </td>
        </tr>
    </table>

</body>

</html>
