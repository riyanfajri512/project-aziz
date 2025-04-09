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
                                            <th>No</th>
                                            <th>Kode Pemesanan</th>
                                            <th>Unit Pembuat</th>
                                            <th>Cabang</th>
                                            <th>Lokasi</th>
                                            <th>Tanggal</th>
                                            <th>Supplier</th>
                                            <th>Total</th>
                                            <th>Status</th>
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
    </div>
@endsection
@section('script')

    <script>
        $(document).ready(function() {
            $('#tabelPermintaan').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('permintaan.list') }}",
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'kode_pemesanan'
                    },
                    {
                        data: 'unit_pembuat'
                    },
                    {
                        data: 'lokasi.nama'
                    },
                    {
                        data: 'lokasi.unit'
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'supplier.nama'
                    },
                    {
                        data: 'total_payment'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'action'
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
                }
            });

            // Handle Delete Button
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Hapus Permintaan?',
                    text: "Data tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/permintaan/' + id,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                $('#permintaan-table').DataTable().ajax.reload();
                                Swal.fire('Terhapus!', response.message, 'success');
                            }
                        });
                    }
                });
            });

            // Handle Approve Button
            $(document).on('click', '.approve-btn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Setujui Permintaan?',
                    text: "Pastikan semua data sudah benar!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Setujui!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/permintaan/' + id + '/approve',
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                $('#permintaan-table').DataTable().ajax.reload();
                                Swal.fire('Disetujui!', response.message, 'success');
                            }
                        });
                    }
                });
            });
        });
    </script>

@endsection
