@extends('layout.app')
@section('title', 'Penerimaan')

@section('main')

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailModalLabel">Detail Penerimaan</h5>
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
                <!-- Tombol akan disisipkan via JavaScript -->
            </div>
        </div>
    </div>
</div>

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
                            <input type="date" class="form-control" id="filterTanggal" placeholder="Filter Tanggal">
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
                                    <th>Status</th>
                                    <th>Kode Penerimaan</th>
                                    <th>Tanggal</th>
                                    <th>User</th>
                                    <th>Kode Permintaan</th>
                                    <th>Grand Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan diisi oleh DataTables -->
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
<script>
    $(document).ready(function () {
        let table = $('#tabelPenerimaan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('penerimaan.list') }}",
                data: function (d) {
                    d.tanggal = $('#filterTanggal').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%' },
                { data: 'status', name: 'status', width: '10%', orderable: false, searchable: false },
                { data: 'kode_penerimaan', name: 'kode_penerimaan', width: '15%' },
                { data: 'tanggal', name: 'tanggal', width: '10%' },
                { data: 'user', name: 'user.name', width: '15%' },
                { data: 'permintaan', name: 'permintaan.kode_permintaan', width: '15%' },
                { data: 'grand_total', name: 'grand_total', width: '15%' },
                { data: 'action', name: 'action', width: '15%', orderable: false, searchable: false }
            ]
        });

        $('#filterTanggal').change(function () {
            table.ajax.reload();
        });

        $(document).on('click', '.view-btn', function () {
            const id = $(this).data('id');
            const modal = $('#detailModal');

            $('#detailContent').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>
            `);

            $.ajax({
                url: `/penerimaan/${id}/detail`,
                type: 'GET',
                success: function (response) {
                    if (response.success) {
                        const p = response.data.penerimaan;

                        let html = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Informasi Penerimaan</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="30%">Kode Penerimaan</th>
                                            <td>${p.kode_penerimaan}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal</th>
                                            <td>${response.data.tanggal_formatted}</td>
                                        </tr>
                                        <tr>
                                            <th>User</th>
                                            <td>${p.user?.name || '-'}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-3">Informasi Permintaan</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="30%">Kode Permintaan</th>
                                            <td>${p.permintaan?.kode_pemesanan || '-'}</td>
                                        </tr>
                                        <tr>
                                            <th>Grand Total</th>
                                            <td>${response.data.grand_total_formatted}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <h5 class="mb-3">Daftar Barang</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kode Sparepart</th>
                                            <th>Nama Barang</th>
                                            <th class="text-end">Qty Diterima</th>
                                            <th class="text-end">Harga</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                        p.items.forEach(item => {
                            html += `
                                <tr>
                                    <td>${item.kode_sparepart}</td>
                                    <td>${item.nama_sparepart}</td>
                                    <td class="text-end">${item.qty_diterima}</td>
                                    <td class="text-end">Rp ${Number(item.harga).toLocaleString('id-ID')}</td>
                                    <td class="text-end">Rp ${Number(item.total_harga).toLocaleString('id-ID')}</td>
                                </tr>`;
                        });

                        html += `</tbody></table></div>`;

                        // Update body
                        $('#detailContent').html(html);

                        // Inject tombol ke footer
                        $('.modal-footer').html(`
                            <div>
                                <button onclick="window.location.href='/penerimaan/${p.id}/edit'" class="btn btn-sm btn-primary me-2">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${p.id}">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </button>
                            </div>
                            <div>
                                <a href="/penerimaan/${p.id}/export" class="btn btn-primary me-2" target="_blank">
                                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                                </a>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        `);

                    } else {
                        $('#detailContent').html(`<div class="alert alert-danger">${response.message}</div>`);
                    }
                },
                error: function (xhr) {
                    $('#detailContent').html(`
                        <div class="alert alert-danger">
                            Terjadi kesalahan: ${xhr.statusText}
                        </div>
                    `);
                }
            });

            modal.modal('show');
        });

        // Hapus data
        $(document).on('click', '.delete-btn', function (e) {
            e.preventDefault();
            const id = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data penerimaan akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/penerimaan/${id}`,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $('.delete-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menghapus...');
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    $('#detailModal').modal('hide');
                                    $('#tabelPenerimaan').DataTable().ajax.reload(null, false);
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message
                                });
                            }
                        },
                        error: function (xhr) {
                            let errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus data';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: errorMessage
                            });
                        },
                        complete: function () {
                            $('.delete-btn').prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Hapus');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
