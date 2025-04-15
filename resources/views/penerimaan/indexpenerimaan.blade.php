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
                    <!-- Konten akan diisi via AJAX -->
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="#" id="exportDetailBtn" class="btn btn-primary" target="_blank">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </a>
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
        $(document).ready(function() {
            let table = $('#tabelPenerimaan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('penerimaan.list') }}",
                    data: function(d) {
                        d.tanggal = $('#filterTanggal').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        width: '5%'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        width: '10%',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_penerimaan',
                        name: 'kode_penerimaan',
                        width: '15%'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        width: '10%'
                    },
                    {
                        data: 'user',
                        name: 'user.name', // Sesuai dengan eager loading
                        width: '15%'
                    },
                    {
                        data: 'permintaan', // Sesuai dengan addColumn di controller
                        name: 'permintaan.kode_permintaan',
                        width: '15%'
                    },
                    {
                        data: 'grand_total',
                        name: 'grand_total',
                        width: '15%',
                    },

                    {
                        data: 'action',
                        name: 'action',
                        width: '15%',
                        orderable: false,
                        searchable: false,
                    }
                ]
            });

            $('#filterTanggal').change(function() {
                table.ajax.reload();
            });



            $(document).on('click', '.view-btn', function() {
                const id = $(this).data('id');
                const modal = $('#detailModal');

                // Tampilkan loading
                $('#detailContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat data...</p>
        </div>
    `);

                // Ambil data via AJAX
                $.ajax({
                    url: `/penerimaan/${id}/detail`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const p = response.data.penerimaan;

                            // Build HTML content
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

                            // Add items
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

                            $('#detailContent').html(html);
                            $('#exportDetailBtn').attr('href', `/penerimaan/${id}/export`);
                        } else {
                            $('#detailContent').html(`
                    <div class="alert alert-danger">
                        ${response.message}
                    </div>
                `);
                        }
                    },
                    error: function(xhr) {
                        $('#detailContent').html(`
                <div class="alert alert-danger">
                    Terjadi kesalahan: ${xhr.statusText}
                </div>
            `);
                    }
                });

                modal.modal('show');
            });
        });
    </script>
@endsection
