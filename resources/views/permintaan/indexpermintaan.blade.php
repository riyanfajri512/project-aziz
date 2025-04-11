@extends('layout.app')
@section('title', 'Permintaan')

@section('main')

    <!-- Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- menggunakan modal-xl untuk lebar extra large -->
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailModalLabel">Detail Permintaan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBodyContent">
                    <!-- Konten akan diisi via AJAX -->
                    {{-- <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data...</p>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <div id="modalFooterActions" class="me-auto">
                        <!-- Tombol aksi akan muncul di sini -->
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

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
                                <table class="table table-striped table-hover" id="tabelPermintaan" style="width: 100%;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Status</th>
                                            <th>Kode Pemesanan</th>
                                            <th>Unit Pembuat</th>
                                            <th>Cabang</th>
                                            <th>Lokasi</th>
                                            <th>Tanggal</th>
                                            <th>Supplier</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
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
            $('#tabelPermintaan').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('permintaan.list') }}",
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'kode_pemesanan'
                    },
                    {
                        data: 'unit_pembuat'
                    },
                    {
                        data: 'lokasi_nama'
                    },
                    {
                        data: 'lokasi_unit'
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'suplier'
                    },
                    {
                        data: 'total_payment'
                    },
                    {
                        data: 'action'
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
                }
            });


            $(document).ready(function() {
                const STATUS = {
                    PENDING: 1,
                    APPROVED: 2,
                    REJECTED: 3,
                    BTB: 4,
                    SP_FINAL: 5
                };

                // View Button Click Handler
                $(document).on('click', '.view-btn', function() {
                    var id = $(this).data('id');
                    var modal = $('#detailModal');

                    // Show loading spinner
                    modal.find('#modalBodyContent').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat data...</p>
            </div>
        `);

                    // Clear footer actions temporarily
                    modal.find('#modalFooterActions').empty();

                    // Load content via AJAX
                    $.get('/permintaan/' + id, function(data) {
                        modal.find('#modalBodyContent').html(data);

                        // Find status element and get status ID
                        var statusElement = modal.find('[data-status-id]');
                        var statusId = statusElement.data('status-id');

                        // If status is pending, show action buttons
                        if (statusId == STATUS.PENDING) {
                            modal.find('#modalFooterActions').html(`
                    <button onclick="window.location.href='/permintaan/${id}/edit'" class="btn btn-sm btn-primary me-2">
                        <i class="fas fa-edit me-1"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${id}">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                `);
                        }
                    }).fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal memuat data',
                            text: 'Terjadi kesalahan saat memuat detail permintaan',
                            confirmButtonColor: '#3085d6',
                        });
                    });
                });

                // Delete Button Click Handler
                $(document).on('click', '#modalFooterActions .delete-btn', function() {
                    var id = $(this).data('id');

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda tidak akan dapat mengembalikan data ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/permintaan/' + id,
                                type: 'DELETE',
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function() {
                                    $('#detailModal').modal('hide');
                                    $('.dataTable').DataTable().ajax.reload();

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Terhapus!',
                                        text: 'Data permintaan telah dihapus.',
                                        confirmButtonColor: '#3085d6',
                                        timer: 2000,
                                        timerProgressBar: true
                                    });
                                },
                                error: function() {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: 'Terjadi kesalahan saat menghapus data',
                                        confirmButtonColor: '#3085d6',
                                    });
                                }
                            });
                        }
                    });
                });

                // Approve Button Click Handler (if you have approve button in your datatable)
                $(document).on('click', '.approve-btn', function() {
                    var id = $(this).data('id');

                    Swal.fire({
                        title: 'Setujui Permintaan?',
                        text: "Anda akan menyetujui permintaan ini",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Setujui!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post('/permintaan/' + id + '/approve', {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            }).done(() => {
                                Swal.fire('Disetujui!',
                                    'Permintaan telah disetujui', 'success');
                                $('.dataTable').DataTable().ajax.reload();
                            }).fail(() => {
                                Swal.fire('Error!', 'Gagal menyetujui permintaan',
                                    'error');
                            });
                        }
                    });
                });
            });


            // function showAlert(type, title, message) {
            //     const Toast = Swal.mixin({
            //         toast: true,
            //         position: 'top-end',
            //         showConfirmButton: false,
            //         timer: 3000,
            //         timerProgressBar: true,
            //         didOpen: (toast) => {
            //             toast.addEventListener('mouseenter', Swal.stopTimer)
            //             toast.addEventListener('mouseleave', Swal.resumeTimer)
            //         }
            //     });

            //     Toast.fire({
            //         icon: type,
            //         title: title,
            //         text: message
            //     });
            // }

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
