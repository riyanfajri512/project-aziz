<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>permintaan #{{ $permintaan->id }}</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .logo-container {
            flex: 0 0 23%;
            padding-right: 15px;
        }

        .logo {
            width: 100%;
            max-width: 120px;
            height: auto;
        }

        .company-info {
            flex: 0 0 75%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 90px;
        }

        .company-name {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 10pt;
            line-height: 1.3;
        }


        h2,
        h3,
        h4 {
            margin-bottom: 5px;
        }

        p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background-color: #eee;
        }

        .section {
            margin-bottom: 20px;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="logo-container">
            @php
                $path = public_path('template/assets/img/logo_indomaret.png');
                $base64 = file_exists($path)
                    ? 'data:image/' .
                        pathinfo($path, PATHINFO_EXTENSION) .
                        ';base64,' .
                        base64_encode(file_get_contents($path))
                    : '';
            @endphp
            @if ($base64)
                <img src="{{ $base64 }}" alt="Company Logo" class="logo">
            @endif
        </div>
        <div class="company-info">
            <div class="company-name">PT. XYZ</div>
            <div class="company-address">
                Jl. Adhyaksa Baru No. 40<br>
                Batam, Riau Islands
            </div>
        </div>
    </div>

    <div style="text-align: center; margin: 15px 0;">
        <h2 style="margin: 0;">PERMINTAAN PEMBELIAN SPAREPART</h2>
        <p style="margin: 5px 0; font-weight: bold;">No: {{ $permintaan->kode_pemesanan ?? '-' }}</p>
    </div>

    <div class="section">
        <table style="width: 100%; border: none; margin-bottom: 15px;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                    <h4>Informasi Permintaan</h4>
                    <table style="width: 100%; border: none; border-collapse: collapse;">
                        <tr>
                            <td style="width: 40%; padding: 3px 0; border: none;">Tanggal</td>
                            <td style="padding: 3px 0; border: none;">: {{ $permintaan->tanggal_dibuat->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; border: none;">Dibuat Oleh</td>
                            <td style="padding: 3px 0; border: none;">: {{ $permintaan->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; border: none;">Status</td>
                            <td style="padding: 3px 0; border: none;">: {{ $permintaan->status->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; border: none;">Lokasi</td>
                            <td style="padding: 3px 0; border: none;">: {{ $permintaan->lokasi->nama ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                    <h4>Informasi Supplier</h4>
                    <table style="width: 100%; border: none; border-collapse: collapse;">
                        <tr>
                            <td style="width: 40%; padding: 3px 0; border: none;">Nama Supplier</td>
                            <td style="padding: 3px 0; border: none;">: {{ $permintaan->suplier->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; border: none;">Alamat</td>
                            <td style="padding: 3px 0; border: none;">: {{ $permintaan->suplier->alamat ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h4 style="margin-bottom: 10px;">Daftar Sparepart</h4>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #000;">
                    <th style="width: 5%; text-align: center; padding: 5px;">No</th>
                    <th style="width: 15%; text-align: left; padding: 5px;">Kode</th>
                    <th style="width: 20%; text-align: left; padding: 5px;">Jenis Kendaraan</th>
                    <th style="width: 30%; text-align: left; padding: 5px;">Nama Sparepart</th>
                    <th style="width: 10%; text-align: center; padding: 5px;">Qty</th>
                    <th style="width: 20%; text-align: right; padding: 5px;">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permintaan->items as $index => $item)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="text-align: center; padding: 5px;">{{ $index + 1 }}</td>
                        <td style="padding: 5px;">{{ $item->kode_sparepart }}</td>
                        <td style="padding: 5px;">{{ $item->jenis_kendaraan }}</td>
                        <td style="padding: 5px;">{{ $item->nama_sparepart }}</td>
                        <td style="text-align: center; padding: 5px;">{{ $item->qty }}</td>
                        <td style="text-align: right; padding: 5px;">Rp {{ number_format($item->harga, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align: right; padding: 8px 5px; font-weight: bold;">TOTAL</td>
                    <td style="text-align: right; padding: 8px 5px; font-weight: bold;">
                        Rp {{ number_format($permintaan->items->sum('total_harga'), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>


    <div class="section" style="margin-top: 50px;">
        <table style="width: 100%; border: none; margin-top: 30px;">
            <tr>
                <!-- Kolom Dibuat Oleh -->
                <td style="width: 33%; text-align: center; border: none;">
                    <div style="height: 70px;"><!-- Space for signature --></div>
                    <p>Dibuat Oleh,</p>
                    <p style="margin-top: 50px;"><strong>{{ $permintaan->user->name ?? '-' }}</strong></p>
                    <p>{{ $permintaan->tanggal_dibuat->format('d/m/Y') }}</p>
                </td>

                <!-- Kolom Diketahui -->
                <td style="width: 33%; text-align: center; border: none;">
                    <div style="height: 70px;"><!-- Space for signature --></div>
                    <p>Diketahui,</p>
                    <p style="margin-top: 50px;"><strong>_________________________</strong></p>
                    <p>Manager/PIC Terkait</p>
                </td>

                <!-- Kolom Disetujui -->
                <td style="width: 33%; text-align: center; border: none;">
                    <div style="height: 70px;"><!-- Space for signature --></div>
                    <p>Disetujui,</p>
                    <p style="margin-top: 50px;"><strong>_________________________</strong></p>
                    <p>Atasan/Pimpinan</p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
