@extends('layout.app')
@section('title', 'Tambah Pendistribusian')

@section('main')


    <style>
        /* Untuk input yang disabled */
        input:disabled,
        input[disabled],
        select:disabled,
        select[disabled],
        textarea:disabled,
        textarea[disabled] {
            background-color: #f2f2f2 !important;
            color: #666 !important;
            cursor: not-allowed;
        }

        /* Untuk input yang readonly */
        input[readonly],
        select[readonly],
        textarea[readonly] {
            background-color: #e9ecef !important;
            color: #495057 !important;
            border-color: #ced4da !important;
        }

        /* Khusus untuk input group (yang ada Rp-nya) */
        .input-group-text {
            background-color: #e9ecef;
        }

        .select2-container .select2-selection--single {
            height: 38px;
            padding-top: 5px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>

    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>Tambah Pendistribusian</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            Form Tambah Pendistribusian
                        </div>

                        <div class="card-body">
                            <form id="formPendistribusian">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- <div class="mb-3">
                                            <label class="form-label">Nomor Perbaikan</label>
                                            <select class="form-control" name="no_perbaikan" id="select-perbaikan" required>
                                                <option value="">Pilih Nomor Perbaikan</option>
                                                <!-- Data akan diisi secara dinamis -->
                                            </select>
                                        </div> --}}
                                        <div class="mb-3">
                                            <label class="form-label">User</label>
                                            <input type="text" class="form-control" id="user-perbaikan"
                                                value="{{ Auth::user()->name }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">NIK User</label>
                                            <input type="text" class="form-control" name="nik_user" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Nopol</label>
                                            <input type="text" class="form-control" name="nopol" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Unit Tujuan</label>
                                            <select class="form-control" name="unit_id" required>
                                                <option value="">Pilih Unit Tujuan</option>
                                                @foreach ($lokasiList as $lokasi)
                                                    <option value="{{ $lokasi->id }}">
                                                        {{ $lokasi->nama }} - {{ $lokasi->unit }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <label class="form-label">Search Sparepart</label>
                                                <select class="form-control select2-sparepart" id="sparepart-search">
                                                    <option value="">-- Pilih Sparepart --</option>
                                                    @foreach ($spareparts as $sp)
                                                        <option value="{{ $sp->id }}" data-kode="{{ $sp->kode }}"
                                                            data-nama="{{ $sp->nama }}" data-harga="{{ $sp->harga }}"
                                                            data-jenis="{{ $sp->jenis }}"
                                                            data-stok="{{ $sp->stok }}">
                                                            {{ $sp->kode }} - {{ $sp->nama }}
                                                            ({{ $sp->jenis }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-primary" id="btn-add-sparepart">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" name="tanggal" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kode Distribusi</label>
                                            <input type="text" class="form-control" name="kode_distribusi"
                                                value="{{ $kodeDistribusi }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Departemen</label>
                                            <input type="text" class="form-control" name="departemen" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive mt-3">
                                    <table class="table" id="tabel-sparepart-distribusi">
                                        <thead class="thead-light">
                                            <tr>
                                              
                                                <th>Jenis Kendaraan</th>
                                                <th>Nama Sparepart</th>
                                                <th class="text-center">Stok Tersedia</th>
                                                <th class="text-center">Qty Distribusi</th>
                                                <th>Jenis Kerusakan</th>
                                                <th class="text-right">Harga</th>
                                                <th class="text-right">Total</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="items-container-distribusi">
                                            <!-- Baris item akan diisi secara dinamis -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">
                                                </td>
                                                <td class="text-right" colspan="2">
                                                    <div class="form-group mb-0">
                                                        <label for="total_payment" class="font-weight-bold mb-0">Total
                                                            Harga:</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp</span>
                                                            </div>
                                                            <input type="text" class="form-control text-right"
                                                                id="total_payment" name="total_payment" value="0"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ route('pendistribusian') }}" class="btn btn-secondary ms-2">Batal</a>
                                </div>
                            </form>
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

            function unformatNumber(numberStr) {
                return parseFloat(numberStr.replace(/\./g, '')) || 0;
            }

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }

            // Inisialisasi select2
            $('.select2-sparepart').select2({
                placeholder: "-- Pilih Sparepart --",
                width: '100%'
            });

            const addedSpareparts = new Set();

            function updateRowTotal(row) {
                const qty = parseInt(row.find('.qty-distribusi').val()) || 0;
                const harga = unformatNumber(row.find('.harga-sparepart').text());
                const total = qty * harga;

                row.find('.total-harga').text(formatRupiah(total));
                calculateTotal();
            }

            function calculateTotal() {
                let total = 0;

                $('#tabel-sparepart-distribusi tbody tr').each(function() {
                    total += unformatNumber($(this).find('.total-harga').text());
                });

                $('#total_payment').val(formatRupiah(total));
            }

            $('#btn-add-sparepart').click(function() {
                const selectedSparepart = $('#sparepart-search option:selected');
                const sparepartId = selectedSparepart.val();
                const kode = selectedSparepart.data('kode');
                const nama = selectedSparepart.data('nama');
                const harga = selectedSparepart.data('harga');
                const jenis = selectedSparepart.data('jenis');
                const stok = selectedSparepart.data('stok');

                if (!sparepartId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pilih sparepart terlebih dahulu!'
                    });
                    return;
                }

                if (addedSpareparts.has(sparepartId)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Sparepart ini sudah ditambahkan!'
                    });
                    return;
                }

                const newRow = `
                    <tr data-sparepart-id="${sparepartId}">
                        <td>${jenis}</td>
                        <td>${nama}</td>
                        <td class="text-center">${stok}</td>
                        <td class="text-center">
                            <input type="number" class="form-control qty-distribusi"
                                value="1" min="1" max="${stok}"
                                style="width: 80px; margin: 0 auto;">
                        </td>
                        <td>
                            <input type="text" name="jenis_kerusakan" class="form-control jenis_kerusakan" placeholder="Jenis kerusakan">
                        </td>
                        <td class="text-right harga-sparepart">${formatRupiah(harga)}</td>
                        <td class="text-right total-harga">${formatRupiah(harga)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm btn-remove-sparepart">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                $('#items-container-distribusi').append(newRow);
                addedSpareparts.add(sparepartId);
                $('#sparepart-search').val('').trigger('change');
            });

            $(document).on('click', '.btn-remove-sparepart', function() {
                const row = $(this).closest('tr');
                const sparepartId = row.data('sparepart-id');

                Swal.fire({
                    title: 'Hapus Sparepart?',
                    text: "Apakah Anda yakin ingin menghapus sparepart ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        addedSpareparts.delete(sparepartId);
                        row.remove();
                        calculateTotal();
                    }
                });
            });

            $(document).on('change', '.qty-distribusi', function() {
                const row = $(this).closest('tr');
                const maxStok = parseInt($(this).attr('max'));
                const qty = parseInt($(this).val()) || 0;

                if (qty < 1) {
                    $(this).val(1);
                } else if (qty > maxStok) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Tidak Cukup',
                        text: `Stok tersedia hanya ${maxStok} unit`
                    });
                    $(this).val(maxStok);
                }

                updateRowTotal(row);
            });



            // Form submission
            $("#formPendistribusian").submit(function(e) {
                e.preventDefault();

                // Validasi form
                let isValid = true;
                $(this).find('[required]').each(function() {
                    if ($(this).val().trim() === '') {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Validasi minimal 1 item sparepart
                const itemCount = $('#tabel-sparepart-distribusi tbody tr').length;
                if (itemCount === 0) {
                    isValid = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Harap tambahkan minimal 1 sparepart!'
                    });
                    return;
                }

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Harap isi semua field yang wajib!'
                    });
                    return;
                }

                // Prepare form data
                const formData = {
                    tanggal: $('[name="tanggal"]').val(),
                    kode_distribusi: $('[name="kode_distribusi"]').val(),
                    nik_user: $('[name="nik_user"]').val(),
                    nopol: $('[name="nopol"]').val(),
                    departemen: $('[name="departemen"]').val(),
                    unit_id: $('[name="unit_id"]').val(),
                    deskripsi: $('[name="deskripsi"]').val(),
                    items: []
                };

                // Collect items data
                $('#tabel-sparepart-distribusi tbody tr').each(function() {
                    const row = $(this);
                    formData.items.push({
                        sparepart_id: row.data('sparepart-id'),
                        jenis_kerusakan:row.find('input.jenis_kerusakan').val(),
                        qty_distribusi: parseInt(row.find('input.qty-distribusi').val())
                    });
                });

                // Show loading indicator
                Swal.fire({
                    title: 'Menyimpan Data...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send data to server
                $.ajax({
                    url: '/pendistribusian/store',
                    method: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data pendistribusian berhasil disimpan. Kode: ' +
                                response.kode_distribusi,
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage
                        });
                    }
                });
            });

            // Reset form validation on input
            $('input').on('input', function() {
                $(this).removeClass('is-invalid');
            });
        });
    </script>
@endsection
