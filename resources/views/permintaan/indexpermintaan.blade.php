@extends('layout.app')
@section('title', 'Permintaan')

@section('main')

    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>Permintaan</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <label for="filterTanggal">Tanggal: </label>
                                <input type="date" id="filterTanggal" class="form-control d-inline-block"
                                    style="width: auto;">

                            </div>
                            <a href="{{ route('permintaan.formtambah') }}" class="btn btn-primary">Tambah Permintaan</a>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive" style="overflow-x: auto;">
                                <table class="table table-striped table-hover" id="tabelPermintaan"
                                    style="width: max-content; min-width: 100%;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 100px;">No.</th>
                                            <th style="width: 150px;">Unit (Pembuat)</th>
                                            <th style="width: 150px;">Cabang</th>
                                            <th style="width: 150px;">Lokasi</th>
                                            <th style="width: 150px;">Kode Pemesanan</th>
                                            <th style="width: 150px;">Lokasi ID</th>
                                            <th style="width: 150px;">Tanggal Dibuat</th>
                                            <th style="width: 200px;">Deskripsi/Catatan</th>
                                            <th style="width: 150px;">File</th>
                                            <th style="width: 100px;">Sp ID</th>
                                            <th style="width: 150px;">Suplier ID</th>
                                            <th style="width: 150px;">Sparepart ID</th>
                                            <th style="width: 150px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Unit A</td>
                                            <td>Cabang 1</td>
                                            <td>Gudang</td>
                                            <td>PMS001</td>
                                            <td>LOC123</td>
                                            <td>2025-03-26</td>
                                            <td>Pengadaan sparepart baru</td>
                                            <td>file.pdf</td>
                                            <td>SP123</td>
                                            <td>SPL001</td>
                                            <td>SPP456</td>
                                            <td><button class="btn btn-sm btn-primary">Edit</button> <button
                                                    class="btn btn-sm btn-danger">Delete</button></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Unit B</td>
                                            <td>Cabang 2</td>
                                            <td>Workshop</td>
                                            <td>PMS002</td>
                                            <td>LOC124</td>
                                            <td>2025-03-26</td>
                                            <td>Perbaikan mesin</td>
                                            <td>file2.pdf</td>
                                            <td>SP124</td>
                                            <td>SPL002</td>
                                            <td>SPP457</td>
                                            <td><button class="btn btn-sm btn-primary">Edit</button> <button
                                                    class="btn btn-sm btn-danger">Delete</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('script')

@endsection
