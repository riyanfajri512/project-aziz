@extends('layout.app')
@section('title', 'Permintaan')

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
    </style>

    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>Tambah Permintaan</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            Form Tambah Permintaan
                        </div>

                        <div class="card-body">
                            <form id="formPermintaan">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">No Permintaan</label>
                                            <input type="text" class="form-control" name="kode_pemesanan"
                                                value=" 0001/voum-1" readonly>
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Unit (Pembuat)</label>
                                            <input type="text" class="form-control" name="unit" value="admin"
                                                readonly>
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Cabang</label>
                                            <input type="text" class="form-control" name="cabang">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">File</label>
                                            <input type="file" class="form-control" name="file">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Dibuat</label>
                                            <input type="date" class="form-control" name="tanggal_dibuat">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Lokasi</label>
                                            <select class="form-select" name="lokasi" id="lokasi" required>
                                                <option value="" selected disabled>-- Pilih Lokasi --</option>
                                                <option value="gudang_utama">Gudang Utama</option>
                                                <option value="gudang_cabang">Gudang Cabang</option>
                                                <option value="showroom">Showroom</option>
                                                <option value="workshop">Workshop</option>
                                                <option value="kantor">Kantor</option>
                                                <option value="lainnya">Lainnya</option>
                                            </select>
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Suplier</label>
                                            <select class="form-select" name="suplier_id" required>
                                                <option value="" selected disabled>-- Pilih Suplier --</option>
                                                <option value="1">PT Sumber Makmur</option>
                                                <option value="2">CV Tekno Mandiri</option>
                                                <option value="3">UD Sparepart Jaya</option>
                                                <!-- Tambahkan data lain sesuai kebutuhan -->
                                            </select>
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>

                                        <div id="pdf-preview-area" class="mt-3 d-none">
                                            <label class="form-label">Preview</label>
                                            <div class="card">
                                                <div class="card-body p-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                                            <span class="pdf-filename"></span>
                                                            <span class="pdf-size text-muted ms-2"></span>
                                                        </div>
                                                        <div>
                                                            <a href="#"
                                                                class="btn btn-sm btn-outline-primary view-pdf"
                                                                target="_blank">
                                                                <i class="fas fa-eye me-1"></i> Preview
                                                            </a>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger ms-1 remove-pdf">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table mt-3">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Jenis Kendaraaan</th>
                                                    <th scope="col">Kode Kendaraaan</th>
                                                    <th scope="col">Sparepart</th>
                                                    <th scope="col" class="text-center">Qty</th>
                                                    <th scope="col" class="text-right">Harga</th>
                                                    <th scope="col" class="text-right">Total</th>
                                                    <th scope="col" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="items-container">
                                                <!-- Dynamic content will be inserted here -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3">
                                                        <button type="button" class="btn btn-primary" id="add-item-button"
                                                            aria-label="Add new item">
                                                            <i class="fas fa-plus mr-2"></i>Add Item
                                                        </button>
                                                    </td>
                                                    <td class="text-right">
                                                        <div class="form-group mb-0">
                                                            <label for="total_payment" class="font-weight-bold mb-0">Total
                                                                Harga:</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">Rp</span>
                                                                </div>
                                                                <input type="text" class="form-control text-right"
                                                                    id="total_payment" name="total_payment"
                                                                    value="0" readonly aria-label="Total price">
                                                            </div>
                                                        </div>
                                                    </td>
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
                                    <div class="col-md-6">

                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
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

            // Preview PDF sebelum upload
            $('input[name="file"]').change(function(e) {
                const fileInput = $(this);
                const file = e.target.files[0];
                const previewArea = $('#pdf-preview-area');

                if (file && file.type === 'application/pdf') {
                    // Update preview area
                    previewArea.find('.pdf-filename').text(file.name);
                    previewArea.find('.pdf-size').text(formatFileSize(file.size));

                    // Buat object URL untuk preview
                    const fileURL = URL.createObjectURL(file);
                    previewArea.find('.view-pdf').attr('href', fileURL);

                    // Tampilkan preview area
                    previewArea.removeClass('d-none');

                } else if (file) {
                    alert('Hanya file PDF yang diperbolehkan');
                    fileInput.val('');
                    previewArea.addClass('d-none');
                } else {
                    previewArea.addClass('d-none');
                }
            });

            // Hapus file dan preview
            $(document).on('click', '.remove-pdf', function() {
                $('input[name="file"]').val('');
                $('#pdf-preview-area').addClass('d-none');
            });

            // Fungsi untuk format ukuran file
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat(bytes / Math.pow(k, i)).toFixed(2) + ' ' + sizes[i];
            }


            addNewItem();

            // Event listener untuk tombol tambah item
            $('#add-item-button').click(function() {
                addNewItem();
            });

            // Event delegation untuk menghapus item
            $(document).on('click', '.remove-item', function() {
                $(this).closest('tr').remove();
                calculateTotal();

                // Jika tidak ada item tersisa, tambahkan satu baris baru
                if ($('#items-container tr').length === 0) {
                    addNewItem();
                }
            });

            // Fungsi untuk menambahkan baris item baru
            function addNewItem() {
                const newItem = `
            <tr class="item-row">
                <td>
                    <select class="form-control vehicle-type" name="jenis_kendaraan[]" required>
                        <option value="">Pilih Jenis</option>
                        <option value="Motor">Motor</option>
                        <option value="Mobil">Mobil</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </td>
                <td>
                    <select class="form-control vehicle-code select2" name="kode_kendaraan[]" required>
                        <option value="">Pilih Kode</option>
                        <option value="KD001">KD001 - Honda Beat</option>
                        <option value="KD002">KD002 - Toyota Avanza</option>
                        <!-- Tambahkan opsi lainnya sesuai kebutuhan -->
                    </select>
                </td>
                <td>
                    <select class="form-control sparepart-code select2" name="kode_sparepart[]" required>
                        <option value="">Pilih Sparepart</option>
                        <option value="SP001">SP001 - Oli Mesin</option>
                        <option value="SP002">SP002 - Ban Dalam</option>
                        <!-- Tambahkan opsi lainnya sesuai kebutuhan -->
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control text-center sparepart-qty" name="qty[]" min="1" value="1" required>
                </td>
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="number" class="form-control text-right sparepart-price" name="harga[]" min="0" value="0" required>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control text-right item-total" value="0" readonly>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-item" aria-label="Remove item">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

                $('#items-container').append(newItem);

                // Inisialisasi select2 untuk kode kendaraan dan sparepart
                $('.vehicle-code.select2, .sparepart-code.select2').select2({
                    placeholder: "Cari...",
                    allowClear: true
                });

                // Hitung total saat ada perubahan quantity atau harga
                $('#items-container tr:last-child .sparepart-qty, #items-container tr:last-child .sparepart-price')
                    .on('input', function() {
                        calculateItemTotal($(this).closest('tr'));
                        calculateTotal();
                    });

                // Fokus ke input pertama di baris baru
                $('#items-container tr:last-child .vehicle-type').focus();
            }

            // Fungsi untuk menghitung total per item
            function calculateItemTotal(row) {
                const qty = parseInt(row.find('.sparepart-qty').val()) || 0;
                const price = parseInt(row.find('.sparepart-price').val()) || 0;
                const total = qty * price;
                row.find('.item-total').val(total.toLocaleString('id-ID'));
            }

            // Fungsi untuk menghitung total keseluruhan
            function calculateTotal() {
                let grandTotal = 0;

                $('.item-row').each(function() {
                    const qty = parseInt($(this).find('.sparepart-qty').val()) || 0;
                    const price = parseInt($(this).find('.sparepart-price').val()) || 0;
                    grandTotal += qty * price;

                    // Update total per item
                    calculateItemTotal($(this));
                });

                $('#total_payment').val(grandTotal.toLocaleString('id-ID'));
            }

            // Panggil calculateTotal saat pertama kali load
            calculateTotal();

            $("#formPermintaan").submit(function(event) {
                event.preventDefault(); // Mencegah reload halaman

                let isValid = true;

                // Loop setiap input dalam form
                $("#formPermintaan input, #formPermintaan textarea, #formPermintaan select").each(
                    function() {
                        if ($(this).val().trim() === "") {
                            $(this).siblings(".text-danger").removeClass(
                                "d-none"); // Tampilkan pesan error
                            isValid = false;
                        } else {
                            $(this).siblings(".text-danger").addClass(
                                "d-none"); // Sembunyikan pesan error jika sudah diisi
                        }
                    });

                if (!isValid) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Harap isi semua field yang wajib!",
                    });
                    return;
                }

                // Jika valid, tampilkan loading
                Swal.fire({
                    title: "Mengirim Permintaan...",
                    html: "Mohon tunggu sebentar.",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Simulasi delay request (contoh: request AJAX ke backend)
                setTimeout(function() {
                    Swal.fire({
                        icon: "success",
                        title: "Permintaan Berhasil Dikirim!",
                        text: "Data telah berhasil disimpan.",
                        timer: 2000,
                        showConfirmButton: false
                    });

                    $("#formPermintaan")[0].reset(); // Reset form setelah sukses
                }, 2000);
            });

            // Ketika user mulai mengetik, hilangkan pesan error
            $("#formPermintaan input, #formPermintaan textarea, #formPermintaan select").on("input", function() {
                $(this).siblings(".text-danger").addClass("d-none");
            });
        });
    </script>
@endsection
