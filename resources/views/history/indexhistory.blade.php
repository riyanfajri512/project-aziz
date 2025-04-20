@extends('layout.app')
@section('title', 'History')

@section('main')

    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>History</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="card-tools">
                                    <form action="{{ route('history') }}" method="GET"
                                        class="d-flex flex-wrap align-items-center gap-2">
                                        <select name="jenis" class="form-control flex-grow-1 flex-md-grow-0"
                                            style="min-width: 120px;">
                                            <option value="">Semua Jenis</option>
                                            <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk
                                            </option>
                                            <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>
                                                Keluar</option>
                                        </select>

                                        <div class="d-flex flex-grow-1 flex-md-grow-0 gap-2" style="min-width: 250px;">
                                            <input type="date" name="tanggal_awal" class="form-control"
                                                value="{{ request('tanggal_awal') }}">
                                            <input type="date" name="tanggal_akhir" class="form-control"
                                                value="{{ request('tanggal_akhir') }}">
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                            <a href="{{ route('history') }}" class="btn btn-outline-secondary">Reset</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" style="overflow-x: auto;">
                                <table class="table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jenis</th>
                                            <th>Kode Transaksi</th>
                                            <th>Kode Sparepart</th>
                                            <th>Nama Sparepart</th>
                                            <th>Jenis Kendaraan</th>
                                            <th>Qty</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($history as $item)
                                            <tr>
                                                <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $item->jenis_transaksi == 'masuk' ? 'success' : 'danger' }}">
                                                        {{ strtoupper($item->jenis_transaksi) }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->kode_transaksi }}</td>
                                                <td>{{ $item->kode_sparepart }}</td>
                                                <td>{{ $item->nama_sparepart }}</td>
                                                <td>{{ $item->jenis_kendaraan }}</td>
                                                <td
                                                    class="{{ $item->jenis_transaksi == 'masuk' ? 'text-success' : 'text-danger' }}">
                                                    {{ $item->qty }}
                                                </td>
                                                <td>Rp {{ number_format($item->harga, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div>
            </div>
        </div>
    </div>

@endsection

<script>
    $(document).ready(function() {
        $('#tabelHistory').DataTable({
            order: [
                [0, 'desc']
            ], // urutkan berdasarkan kolom No secara descending
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Tidak ada data ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
            }
        });
    });
</script>
