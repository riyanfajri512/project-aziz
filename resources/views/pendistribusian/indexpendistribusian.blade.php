@extends('layout.app')
@section('title', 'Pendistribusian')

@section('main')

    <div class="modal fade" id="itemsModal" tabindex="-1" role="dialog" aria-labelledby="itemsModalLabel">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailModalLabel">Detail Pendistribusian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Kode Sparepart</th>
                                <th>Nama Sparepart</th>
                                <th>Jenis Kendaraan</th>
                                <th>Qty Distribusi</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>Pendistribusian</h3>
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
                                            <tr>
                                                <th>Kode Distribusi</th>
                                                <th>Tanggal</th>
                                                <th>User</th>
                                                <th>Unit</th>
                                                <th>Total Harga</th>
                                                <th>Aksi</th>
                                            </tr>
                                    </thead>
                                    <tbody>

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
@section('script')
    <script>
        $(document).ready(function() {
            // Inisialisasi datatable
            var table = $('#tabelPendistribusian').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('pendistribusian.list') }}",
                columns: [{
                        data: 'kode_distribusi',
                        name: 'kode_distribusi'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'user_name',
                        name: 'user.name'
                    },
                    {
                        data: 'unit_name',
                        name: 'unit.nama_lokasi'
                    },
                    {
                        data: 'total_harga',
                        name: 'total_harga'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Handle klik tombol lihat items
            $(document).on('click', '.view-items', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: "{{ url('pendistribusian/items') }}/" + id,
                    type: 'GET',
                    success: function(response) {
                        var items = response.data;
                        var tbody = $('#itemsTable tbody');
                        tbody.empty();

                        $.each(items, function(index, item) {
                            tbody.append(
                                '<tr>' +
                                '<td>' + item.kode_sparepart + '</td>' +
                                '<td>' + item.nama_sparepart + '</td>' +
                                '<td>' + item.jenis_kendaraan + '</td>' +
                                '<td>' + item.qty_distribusi + '</td>' +
                                '<td>Rp ' + formatRupiah(item.harga) + '</td>' +
                                '<td>Rp ' + formatRupiah(item.total) + '</td>' +
                                '</tr>'
                            );
                        });

                        $('#itemsModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat memuat data items');
                    }
                });
            });

            // Fungsi untuk format rupiah
            function formatRupiah(angka) {
                var number_string = angka.toString(),
                    split = number_string.split('.'),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah;
            }
        });
    </script>
@endsection
