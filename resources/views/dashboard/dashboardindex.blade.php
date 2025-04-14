@extends('layout.app')
@section('title', 'Dashboard Sparepart')

@section('main')
    <div class="content">
        <div class="container">
            <!-- Header -->
            <div class="row">
                <div class="col-md-12 page-header">
                    <div class="page-pretitle">Monitoring</div>
                    <h2 class="page-title">Dashboard Stok Sparepart</h2>
                </div>
            </div>

            <!-- 3 Card Utama -->
            <div class="row">
                <!-- Card 1: Total Stok Tersedia -->
                <div class="col-sm-6 col-md-4 mt-3">
                    <div class="card">
                        <div class="content">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="icon-big text-center">
                                        <i class="teal fas fa-boxes"></i>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="detail">
                                        <p class="detail-subtitle">Stok Tersedia</p>
                                        <span class="number">1,245</span>
                                    </div>
                                </div>
                            </div>
                            <div class="footer">
                                <hr />
                                <div class="stats">
                                    <i class="fas fa-info-circle"></i> Total semua sparepart
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Permintaan Pending -->
                <div class="col-sm-6 col-md-4 mt-3">
                    <div class="card">
                        <div class="content">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="icon-big text-center">
                                        <i class="orange fas fa-clock"></i>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="detail">
                                        <p class="detail-subtitle">Permintaan Pending</p>
                                        <span class="number">42</span>
                                    </div>
                                </div>
                            </div>
                            <div class="footer">
                                <hr />
                                <div class="stats">
                                    <i class="fas fa-sync-alt"></i> Menunggu persetujuan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Barang Habis -->
                <div class="col-sm-6 col-md-4 mt-3">
                    <div class="card">
                        <div class="content">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="icon-big text-center">
                                        <i class="red fas fa-exclamation-triangle"></i>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="detail">
                                        <p class="detail-subtitle">Stok Habis</p>
                                        <span class="number">18</span>
                                    </div>
                                </div>
                            </div>
                            <div class="footer">
                                <hr />
                                <div class="stats">
                                    <i class="fas fa-list"></i> Perlu pengadaan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik dan Filter -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="content">
                            <div class="head">
                                <h5 class="mb-0">Distribusi Sparepart</h5>
                                <p class="text-muted">
                                    Pilih Bulan:
                                    <input type="month" class="form-control-sm" style="width: 200px;">
                                    <button id="resetFilter" class="btn btn-sm btn-secondary">Reset</button>
                                </p>
                            </div>
                            <div class="canvas-wrapper">
                                <canvas class="chart" id="distribusiChart" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Permintaan Pending -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="content">
                            <div class="head">
                                <h5 class="mb-0">Daftar Permintaan Pending</h5>
                                <p class="text-muted">Permintaan terakhir yang belum diproses</p>
                            </div>
                            <div class="canvas-wrapper">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Pemohon</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>12/04/2023</td>
                                            <td>Oli Mesin 10W-40</td>
                                            <td>5</td>
                                            <td>Budi (Workshop A)</td>
                                            <td><span class="badge bg-warning">Pending</span></td>
                                        </tr>
                                        <tr>
                                            <td>11/04/2023</td>
                                            <td>Kampas Rem</td>
                                            <td>2</td>
                                            <td>Ani (Workshop B)</td>
                                            <td><span class="badge bg-warning">Pending</span></td>
                                        </tr>
                                        <!-- Data dummy lainnya -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
