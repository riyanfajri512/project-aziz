@extends('layout.app')
@section('title', 'Tambah Penerimaan')

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
                            <form id="formPenerimaan">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Permintaan ID</label>
                                            <input type="text" class="form-control" name="permintaan_id" 
                                                   placeholder="Contoh: 1001" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">User ID</label>
                                            <input type="text" class="form-control" name="user_id" 
                                                   placeholder="Contoh: U001" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" name="tanggal" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Balance</label>
                                            <input type="number" class="form-control" name="balance" 
                                                   min="0" value="0" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive mt-3">
                                    <table class="table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Deskripsi</th>
                                                <th class="text-center">Jumlah</th>
                                                <th class="text-right">Harga</th>
                                                <th class="text-right">Total</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="items-container">
                                            <tr class="item-row">
                                                <td>
                                                    <input type="text" class="form-control" name="deskripsi[]" 
                                                           placeholder="Contoh: Sparepart Mesin A" required>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control text-center" name="jumlah[]" 
                                                           min="1" value="1" required>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input type="number" class="form-control text-right" name="harga[]" 
                                                               min="0" value="0" required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input type="text" class="form-control text-right item-total" 
                                                               value="0" readonly>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm remove-item">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-right">
                                                    <strong>Grand Total:</strong>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input type="text" class="form-control text-right" 
                                                               id="grand-total" value="0" readonly>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" id="add-item-button">
                                                        <i class="fas fa-plus"></i> Tambah Item
                                                    </button>
                                                </td>
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
    $(document).ready(function() {
        // Fungsi untuk menambahkan item baru
        function addNewItem() {
            const newItem = `
                <tr class="item-row">
                    <td>
                        <input type="text" class="form-control" name="deskripsi[]" 
                               placeholder="Contoh: Sparepart Mesin A" required>
                    </td>
                    <td>
                        <input type="number" class="form-control text-center" name="jumlah[]" 
                               min="1" value="1" required>
                    </td>
                    <td>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control text-right" name="harga[]" 
                                   min="0" value="0" required>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control text-right item-total" 
                                   value="0" readonly>
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#items-container').append(newItem);
        }

        // Tombol tambah item
        $('#add-item-button').click(function() {
            addNewItem();
        });

        // Hapus item
        $(document).on('click', '.remove-item', function() {
            $(this).closest('tr').remove();
            calculateTotals();
        });

        // Hitung total per item
        function calculateItemTotal(row) {
            const jumlah = parseInt(row.find('input[name="jumlah[]"]').val()) || 0;
            const harga = parseInt(row.find('input[name="harga[]"]').val()) || 0;
            const total = jumlah * harga;
            row.find('.item-total').val(total.toLocaleString('id-ID'));
            return total;
        }

        // Hitung grand total
        function calculateTotals() {
            let grandTotal = 0;
            $('.item-row').each(function() {
                grandTotal += calculateItemTotal($(this));
            });
            $('#grand-total').val(grandTotal.toLocaleString('id-ID'));
        }

        // Event perubahan jumlah atau harga
        $(document).on('input', 'input[name="jumlah[]"], input[name="harga[]"]', function() {
            calculateTotals();
        });

        // Submit form
        $("#formPenerimaan").submit(function(e) {
            e.preventDefault();
            
            // Validasi
            let isValid = true;
            $('input[required]').each(function() {
                if ($(this).val().trim() === '') {
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
                    text: 'Harap isi semua field yang wajib!'
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

            // Simulasi AJAX (ganti dengan AJAX real)
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data penerimaan berhasil disimpan',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '/penerimaan'; // Redirect ke halaman penerimaan
                });
            }, 1500);
        });

        // Reset validasi saat input
        $('input').on('input', function() {
            $(this).removeClass('is-invalid');
        });
    });
</script>
@endsection