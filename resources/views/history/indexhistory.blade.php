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
                                    <form action="{{ route('history') }}" method="GET" class="row g-3 align-items-end">
                                        {{-- Tanggal Awal --}}
                                        <div class="col-md-4">
                                            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                                            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control"
                                                value="{{ request('tanggal_awal') }}">
                                        </div>

                                        {{-- Tanggal Akhir --}}
                                        <div class="col-md-4">
                                            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                            <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                                                class="form-control" value="{{ request('tanggal_akhir') }}">
                                        </div>

                                        {{-- Jenis --}}
                                        <div class="col-md-4">
                                            <label for="jenis" class="form-label">Jenis Transaksi</label>
                                            <select name="jenis" id="jenis" class="form-select">
                                                <option value="">Semua Jenis</option>
                                                <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>
                                                    Masuk</option>
                                                <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>
                                                    Keluar</option>
                                            </select>
                                        </div>

                                        {{-- Tombol Aksi --}}
                                        <div class="col-12 d-flex flex-wrap gap-2">
                                            <button type="submit" class="btn btm-sm btn-primary">Filter</button>
                                            <a href="{{ route('history') }}" class="btn btm-sm btn-outline-secondary">Reset</a>
                                            <button type="button" class="btn btm-sm btn-danger">Export PDF</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" style="overflow-x: auto;">
                                <table class="table" id="tabelHistory">
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
                                                    <span
                                                        class="badge bg-{{ $item->jenis_transaksi == 'masuk' ? 'success' : 'danger' }}">
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

@section('script')
    <script>
        $(document).ready(function() {
            $('#tabelHistory').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });
        });
    </script>
@endsection
