<!DOCTYPE html>
<html>

<head>
    <title>Export Riwayat Transaksi</title>
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
        <h2 style="margin: 0;">Riwayat Transaksi Sparepart</h2>
    </div>
    <div class="section">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid #000;">
                    <th style="width: 10%; text-align: center; padding: 5px;">Tanggal</th>
                    <th style="width: 10%; text-align: left; padding: 5px;">Jenis</th>
                    <th style="width: 15%; text-align: left; padding: 5px;">Kode Transaksi</th>
                    <th style="width: 15%; text-align: left; padding: 5px;">Kode Sparepart</th>
                    <th style="width: 20%; text-align: left; padding: 5px;">Nama Sparepart</th>
                    <th style="width: 10%; text-align: center; padding: 5px;">Jenis Kendaraan</th>
                    <th style="width: 10%; text-align: center; padding: 5px;">Qty</th>
                    <th style="width: 10%; text-align: right; padding: 5px;">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $item)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="text-align: center; padding: 5px;">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                        <td style="padding: 5px;">{{ strtoupper($item->jenis_transaksi) }}</td>
                        <td style="padding: 5px;">{{ $item->kode_transaksi }}</td>
                        <td style="padding: 5px;">{{ $item->kode_sparepart }}</td>
                        <td style="padding: 5px;">{{ $item->nama_sparepart }}</td>
                        <td style="text-align: center; padding: 5px;">{{ $item->jenis_kendaraan }}</td>
                        <td style="text-align: center; padding: 5px;">{{ $item->qty }}</td>
                        <td style="text-align: right; padding: 5px;">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
