@extends('layout.app')
@section('title', 'History')

@section('main')

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailModalLabel">History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>History</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="col-md-12 text-end">
                                <a href="#" class="btn btn-primary btn-lg">Export PDF</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table table-striped table-hover" id="tabelHistory"
                                style="width: max-content; min-width: 100%;">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>User</th>
                                        <th>Nama Sparepart</th>
                                        <th>Quantity</th>
                                        <th>Type</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data dummy / dari controller -->
                                    <tr>
                                        <td>1</td>
                                        <td>Admin</td>
                                        <td>Filter Oli</td>
                                        <td>3</td>
                                        <td>Masuk</td>
                                        <td>2025-04-20</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Admin</td>
                                        <td>Kampas Rem</td>
                                        <td>5</td>
                                        <td>Keluar</td>
                                        <td>2025-04-19</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div>
        </div>
    </div>

@endsection

    <script>
        $(document).ready(function () {
            $('#tabelHistory').DataTable({
                order: [[0, 'desc']], // urutkan berdasarkan kolom No secara descending
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

