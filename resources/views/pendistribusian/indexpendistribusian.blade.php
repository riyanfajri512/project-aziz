@extends('layout.app')
@section('title', 'Master Data')

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
                                <a href="{{ route('pendistribusian.tambah') }}" class="btn btn-primary">Tambah Pendistribusian</a>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>D001</td>
                                        <td>1001</td>
                                        <td>U001</td>
                                        <td>SPD001</td>
                                        <td>2</td> <!-- Jumlah yang mengurangi balance -->
                                        <td>
                                            <button class="btn btn-primary btn-sm">Edit</button>
                                            <button class="btn btn-danger btn-sm">Delete</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>D002</td>
                                        <td>1002</td>
                                        <td>U002</td>
                                        <td>SPD002</td>
                                        <td>3</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm">Edit</button>
                                            <button class="btn btn-danger btn-sm">Delete</button>
                                        </td>
                                    </tr>
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