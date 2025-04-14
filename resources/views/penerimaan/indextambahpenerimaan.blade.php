@extends('layout.app')
@section('title', 'Tambah Penerimaan')
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

    /* Style untuk tombol hapus */
    .btn-remove {
        color: #dc3545;
        background: none;
        border: none;
        padding: 0;
    }
</style>

@section('main')
    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>Tambah Penerimaan</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            Form Tambah Penerimaan
                        </div>

                        <div class="card-body">
                            <form id="formPenerimaan" action="{{ route('penerimaan.simpan') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Permintaan</label>
                                            <select class="form-control" name="permintaan_id" id="select-permintaan">
                                                <option value="">Pilih Permintaan</option>
                                                @foreach ($permintaanList as $permintaan)
                                                    <option value="{{ $permintaan->id }}"
                                                        data-tanggal="{{ $permintaan->tanggal }}"
                                                        data-user="{{ optional($permintaan->user)->name }}">
                                                        {{ $permintaan->kode_pemesanan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">User</label>
                                            <input type="text" class="form-control" id="user-permintaan"
                                                value="{{ Auth::user()->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" id="tanggal-permintaan" name="tanggal"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kode Penerimaan</label>
                                            <input type="text" class="form-control" name="kode_penerimaan"
                                                value="{{ $kodePeneriman }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary" id="add-item-button">
                                        <i class="fas fa-plus"></i> Tambah Item
                                    </button>
                                </div>

                                <div class="table-responsive mt-3">
                                    <table class="table" id="tabel-sparepart">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Kode Sparepart</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Permintaan</th>
                                                <th class="text-center">Penerimaan</th>
                                                <th class="text-right">Harga</th>
                                                <th class="text-right">Total</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody id="items-container">
                                            <!-- Baris item akan diisi secara dinamis -->
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-right">
                                                    <strong>Grand Total:</strong>
                                                </td>
                                                <td class="text-right">
                                                    <span id="grand-total">0</span>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <button type="reset" class="btn btn-secondary ms-2">Batal</button>
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
        $(document).ready(function () {
            // Variabel untuk menyimpan data permintaan yang dipilih
            let selectedPermintaan = null;

            // Event ketika permintaan dipilih
            $('#select-permintaan').change(function () {
                const permintaanId = $(this).val();

                if (!permintaanId) {
                    // Reset form jika tidak ada permintaan yang dipilih
                    resetForm();
                    return;
                }

                // Ambil data dari atribut data-* pada option yang dipilih
                const tanggal = $(this).find('option:selected').data('tanggal');
                const user = $(this).find('option:selected').data('user');

                // Isi form
                $('#tanggal-permintaan').val(tanggal);
                $('#user-permintaan').val(user);

                // AJAX untuk mendapatkan detail permintaan
                $.ajax({
                    url: '/api/permintaan/' + permintaanId,
                    method: 'GET',
                    success: function (response) {
                        selectedPermintaan = response.data;
                        loadItems(selectedPermintaan.items);
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal memuat data permintaan'
                        });
                    }
                });
            });

            // Fungsi untuk memuat item permintaan ke tabel
            function loadItems(items) {
                $('#items-container').empty();
                let grandTotal = 0;

                items.forEach((item, index) => {
                    const total = item.qty * item.harga;
                    grandTotal += total;

                    const row = `
                            <tr class="item-row" data-id="${item.id}">
                                <td>
                                    <input type="text" class="form-control" 
                                        name="items[${index}][kode_sparepart]" 
                                        value="${item.kode_sparepart}" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control text-center" 
                                        name="items[${index}][qty]" 
                                        value="${item.qty}" min="1" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control text-center" 
                                        name="items[${index}][permintaan]" 
                                        value="${item.qty}" min="0" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control text-center penerimaan-input" 
                                        name="items[${index}][penerimaan]" 
                                        value="${item.qty}" min="0" max="${item.qty}" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control text-right" 
                                        name="items[${index}][harga]" 
                                        value="${item.harga}" min="0" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control text-right item-total" 
                                        value="${formatRupiah(total)}" readonly>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn-remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;

                    $('#items-container').append(row);
                });

                // Update grand total
                $('#grand-total').text(formatRupiah(grandTotal));

                // Hitung ulang total saat penerimaan diubah
                $('.penerimaan-input').on('input', function () {
                    calculateTotals();
                });
            }

            // Fungsi untuk reset form
            function resetForm() {
                $('#tanggal-permintaan').val('');
                $('#user-permintaan').val('');
                $('#items-container').empty();
                $('#grand-total').text('0');
                selectedPermintaan = null;
            }

            // Fungsi format rupiah
            function formatRupiah(angka) {
                return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Fungsi hitung total
            function calculateTotals() {
                let grandTotal = 0;

                $('.item-row').each(function () {
                    const penerimaan = parseInt($(this).find('.penerimaan-input').val()) || 0;
                    const harga = parseInt($(this).find('input[name*="[harga]"]').val()) || 0;
                    const total = penerimaan * harga;

                    $(this).find('.item-total').val(formatRupiah(total));
                    grandTotal += total;
                });

                $('#grand-total').text(formatRupiah(grandTotal));
            }

            // Event untuk tombol hapus item
            $(document).on('click', '.btn-remove', function () {
                $(this).closest('.item-row').remove();
                calculateTotals();
            });

            // Event untuk tombol tambah item
            $('#add-item-button').click(function () {
                if (!selectedPermintaan) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan pilih permintaan terlebih dahulu'
                    });
                    return;
                }

                // Tampilkan modal atau form untuk menambah item baru
                // Implementasi ini tergantung kebutuhan Anda
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: 'Fitur tambah item manual akan diimplementasikan sesuai kebutuhan'
                });
            });

            // Submit form
            $("#formPenerimaan").submit(function (e) {
                e.preventDefault();

                // Validasi penerimaan tidak melebihi permintaan
                let isValid = true;
                $('.penerimaan-input').each(function () {
                    const penerimaan = parseInt($(this).val()) || 0;
                    const permintaan = parseInt($(this).closest('tr').find('input[name*="[permintaan]"]').val()) || 0;

                    if (penerimaan > permintaan) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Jumlah penerimaan tidak boleh melebihi permintaan'
                    });
                    return;
                }

                // Tampilkan loading
                Swal.fire({
                    title: 'Menyimpan Data...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Kirim data via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Data penerimaan berhasil disimpan',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = response.redirect || '/penerimaan';
                        });
                    },
                    error: function (xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: errorMessage
                        });
                    }
                });
            });
        });
    </script>
@endsection