@extends('layout.app')
@section('title', 'Penerimaan')

@section('main')

    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>Penerimaan</h3>
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
                                <a href="{{ route('penerimaan.tambahan') }}" class="btn btn-primary">Tambah Penerimaan</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table table-striped table-hover" id="tabelPenerimaan"
                                style="width: max-content; min-width: 100%;">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Permintaan ID</th>
                                        <th>User ID</th>
                                        <th>Deskripsi</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal</th>
                                        <th>Balance</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>1001</td>
                                        <td>U001</td>
                                        <td>Sparepart Mesin A</td>
                                        <td>5</td>
                                        <td>2023-10-01</td>
                                        <td>10</td>
                                        <td>150000</td>
                                        <td>750000</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm">Edit</button>
                                            <button class="btn btn-danger btn-sm">Delete</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>1002</td>
                                        <td>U002</td>
                                        <td>Sparepart Mesin B</td>
                                        <td>3</td>
                                        <td>2023-10-02</td>
                                        <td>8</td>
                                        <td>200000</td>
                                        <td>600000</td>
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