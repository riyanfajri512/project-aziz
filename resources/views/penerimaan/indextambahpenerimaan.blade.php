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
                            <form id="formPenerimaan" action="/penerimaan/simpan">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Permintaan</label>
                                            <select class="form-control" name="permintaan_id" id="select-permintaan">
                                                <option value="">Pilih Permintaan</option>
                                                @foreach ($permintaanList as $permintaan)
                                                    <option value="{{ $permintaan->id }}">
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
                                            <input type="date" class="form-control" id="tanggal-permintaan"
                                                name="tanggal" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kode Penerimaan</label>
                                            <input type="text" class="form-control" name="kode_penerimaan"
                                                value="{{ $kodePeneriman }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive mt-3">
                                    <table class="table" id="tabel-sparepart">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Kode sparepart</th>
                                                <th>Jenis kendaraan</th>
                                                <th>Nama sparepart</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Qty diterima</th>
                                                <th class="text-right">Harga</th>
                                                <th class="text-right">Total</th>
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

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi/Catatan</label>
                                        <textarea class="form-control" name="deskripsi"></textarea>
                                        <small class="text-danger d-none">Field ini wajib diisi</small>
                                    </div>
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
        $(document).ready(function() {
            // Variabel untuk menyimpan data permintaan yang dipilih
            let selectedPermintaan = null;

            // Fungsi untuk menghitung ulang grand total
            function recalculateGrandTotal() {
                let grandTotal = 0;

                $('.item-total').each(function() {
                    const totalText = $(this).text().replace(/[^\d]/g, '');
                    grandTotal += parseFloat(totalText) || 0;
                });

                $('#grand-total').text(formatRupiah(grandTotal.toString()));
            }

            // Fungsi untuk format mata uang Rupiah
            function formatRupiah(angka) {
                return 'Rp ' + parseFloat(angka).toLocaleString('id-ID');
            }

            $('#select-permintaan').change(function() {
                const permintaanId = $(this).val();

                if (!permintaanId) {
                    $('#items-container').empty();
                    $('#grand-total').text(formatRupiah('0'));
                    return;
                }

                // AJAX untuk mendapatkan detail permintaan
                $.ajax({
                    url: '/penerimaan/getListItembyId',
                    data: {
                        id: permintaanId
                    },
                    method: 'GET',
                    success: function(response) {
                        selectedPermintaan = response.data;

                        // Kosongkan container items terlebih dahulu
                        $('#items-container').empty();

                        // Hitung grand total
                        let grandTotal = 0;

                        // Loop melalui setiap item dan tambahkan ke tabel
                        response.data.items.forEach(item => {
                            const totalHarga = parseFloat(item.total_harga);
                            grandTotal += totalHarga;

                            const row = `
                            <tr>
                                <td>
                                    <input type="hidden" name="items[${item.id}][kode_sparepart]" value="${item.kode_sparepart}">
                                    ${item.kode_sparepart}
                                </td>
                                <td>
                                    <input type="hidden" name="items[${item.id}][jenis_kendaraan]" value="${item.jenis_kendaraan}">
                                    ${item.jenis_kendaraan}
                                </td>
                                <td>
                                    <input type="hidden" name="items[${item.id}][nama_sparepart]" value="${item.nama_sparepart}">
                                    ${item.nama_sparepart}
                                </td>
                                <td class="text-center">
                                    <input type="hidden" name="items[${item.id}][qty]" value="${item.qty}">
                                    ${item.qty}
                                </td>
                                <td class="text-center">
                                    <input type="number" name="items[${item.id}][qty_diterima]" class="form-control qty-diterima"
                                        data-item-kodeSparepat="${item.kode_sparepart}"
                                        data-harga="${item.harga}"
                                        data-max-qty="${item.qty}"
                                        max="${item.qty}"
                                        min="0"
                                        value="0">
                                    <input type="hidden" name="items[${item.id}][id]" value="${item.id}">
                                    <input type="hidden" name="items[${item.id}][harga]" value="${item.harga}">
                                </td>
                                <td class="text-right">
                                    <input type="hidden" name="items[${item.id}][harga_display]" value="${item.harga}">
                                    ${formatRupiah(item.harga)}
                                </td>
                                <td class="text-right item-total">
                                    <input type="hidden" name="items[${item.id}][total_harga]" value="${item.total_harga}">
                                    ${formatRupiah(item.total_harga)}
                                </td>
                            </tr>
                        `;

                            $('#items-container').append(row);
                        });

                        // Update grand total
                        $('#grand-total').text(formatRupiah(grandTotal.toString()));

                        // Tambahkan event listener untuk input qty diterima
                        $(document).on('input', '.qty-diterima', function() {
                            const qtyDiterima = parseInt($(this).val()) || 0;
                            const maxQty = parseInt($(this).attr('max'));
                            const harga = parseFloat($(this).data('harga'));

                            // Validasi tidak melebihi qty permintaan
                            if (qtyDiterima > maxQty) {
                                $(this).val(maxQty);
                                return;
                            }

                            const total = qtyDiterima * harga;

                            // Update total per item
                            $(this).closest('tr').find('.item-total').text(formatRupiah(
                                total.toString()));

                            // Hitung ulang grand total
                            recalculateGrandTotal();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal memuat data permintaan'
                        });
                    }
                });
            });

            // Submit form
            $("#formPenerimaan").submit(function(e) {
                e.preventDefault();

                // Validasi penerimaan tidak melebihi permintaan
                let isValid = true;
                $('.penerimaan-input').each(function() {
                    const penerimaan = parseInt($(this).val()) || 0;
                    const permintaan = parseInt($(this).closest('tr').find(
                        'input[name*="[permintaan]"]').val()) || 0;

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
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message ||
                                'Data penerimaan berhasil disimpan',
                            timer: 2000,
                            showConfirmButton: false,
                            willClose: () => {
                                location
                            .reload(); // Reload halaman setelah SweetAlert ditutup
                            }
                        });
                    },
                    error: function(xhr) {
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
