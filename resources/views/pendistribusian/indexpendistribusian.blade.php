@extends('layout.app')
@section('title', 'Pendistribusian')

@section('main')

    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>Pendistribusian</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="col-md-4">
                                <input type="date" class="form-control" placeholder="Filter Tanggal">
                            </div>
                            <div>
                                <a href="{{ route('pendistribusian.tambah') }}" class="btn btn-primary">Tambah
                                    Pendistribusian</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table table-striped table-hover" id="tabelPendistribusian"
                                style="width: max-content; min-width: 100%;">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Perbaikan</th>
                                        <th>User</th>
                                        <th>Sparepart Distribusi ID</th>
                                        <th>Jumlah</th>
                                        <th>Plat Nomor</th>
                                        <th>Tanggal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendistribusian as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->kode_perbaikan }}</td>
                                            <td>{{ $item->user->name }}</td>
                                            <td>{{ $item->sparepart_distribusi_id }}</td>
                                            <td>{{ $item->jumlah }}</td>
                                            <td>{{ $item->plat_nomor }}</td>
                                            <td>{{ $item->tanggal }}</td>
                                            <td>
                                                <a href="{{ route('pendistribusian.edit', $item->id) }}"
                                                    class="btn btn-warning">Edit</a>
                                                <form action="{{ route('pendistribusian.destroy', $item->id) }}"
                                                    method="POST" style="display: inline;">
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
