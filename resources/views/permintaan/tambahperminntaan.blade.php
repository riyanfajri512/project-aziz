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

    <div class="modal fade" id="searchSparepartModal" tabindex="-1" role="dialog" aria-labelledby="searchSparepartModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchSparepartModalLabel">Cari Sparepart yang Pernah Dibuat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="searchSparepartInput">Cari Sparepart:</label>
                        <input type="text" class="form-control" id="searchSparepartInput"
                            placeholder="Masukkan kode atau nama sparepart...">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Jenis Kendaraan</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="sparepartSearchResults">
                                <!-- Hasil pencarian akan muncul di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

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
                                            <input type="text" class="form-control" name="unit"
                                                value="{{ Auth::user()->name }}" readonly>
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Cabang</label>
                                            <select name="lokasi_id" id="lokasi" class="form-control" disabled>
                                                @foreach ($lokasiList as $lokasi)
                                                    <option value="{{ $lokasi->id }}" {{ $loop->first ? 'selected' : '' }}>
                                                        {{ $lokasi->nama }} - {{ $lokasi->unit }}
                                                    </option>
                                                @endforeach
                                            </select>
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
                                            <label class="form-label">Suplier</label>
                                            <select class="form-select" name="suplier_id" required>
                                                <option value="" selected disabled>-- Pilih Suplier --</option>
                                                suplierList
                                                @foreach ($suplierList as $supplier)
                                                <option value="{{ $supplier->id }}">
                                                    {{ $supplier->nama }}
                                                </option>
                                            @endforeach
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

                                    <datalist id="sparepart-list">
                                        <!-- Akan diisi dengan history kode sparepart -->
                                    </datalist>

                                    <div class="table-responsive">
                                        <table class="table mt-3">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Kode Sparepart</th>
                                                    <th scope="col">Jenis Kendaraan</th>
                                                    <th scope="col">Nama Sparepart</th>
                                                    <th scope="col" class="text-center">Qty</th>
                                                    <th scope="col" class="text-right">Harga per Pcs</th>
                                                    <th scope="col" class="text-right">Total Harga</th>
                                                    <th scope="col" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="items-container">
                                                <!-- Dynamic content will be inserted here -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5">
                                                        <button type="button" class="btn btn-primary"
                                                            id="add-item-button">
                                                            <i class="fas fa-plus mr-2"></i>Tambah Item
                                                        </button>
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
                                                                    id="total_payment" name="total_payment"
                                                                    value="0" readonly>
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
        let sparepartHistory = [{
                kode: "MOT-001",
                jenis: "Motor",
                nama: "Kampas Rem",
                harga: 50000
            },
            {
                kode: "MOB-001",
                jenis: "Mobil",
                nama: "Filter Oli",
                harga: 75000
            }
        ];

        function updateTotal() {
            let total = 0;
            $('#items-container tr').each(function() {
                let qty = parseInt($(this).find('.qty').val()) || 0;
                let harga = parseInt($(this).find('.harga').val()) || 0;
                total += qty * harga;
            });
            $('#total_payment').val(total.toLocaleString('id-ID'));
        }

        function slugify(str) {
            return str.toUpperCase().replace(/\s+/g, '-').replace(/[^A-Z0-9-]/g, '');
        }

        function generateKode(jenis) {
            let prefix = jenis.substring(0, 3).toUpperCase();

            // Hitung jumlah sparepart dengan jenis yang sama
            let count = sparepartHistory.filter(sp => sp.jenis === jenis).length + 1;

            return `${prefix}-${count.toString().padStart(3, '0')}`;
        }

        function generateRow() {
            return `
        <tr>
            <td><input type="text" class="form-control kode" list="sparepart-list"></td>
            <td>
                <select class="form-control jenis">
                    <option value="">Pilih</option>
                    <option value="Mobil">Mobil</option>
                    <option value="Motor">Motor</option>
                </select>
            </td>
            <td><input type="text" class="form-control nama"></td>
            <td class="text-center"><input type="number" class="form-control qty" min="1" value="1"></td>
            <td class="text-right"><input type="number" class="form-control harga text-right" value="0"></td>
            <td class="text-right"><input type="text" class="form-control total text-right" value="0" readonly></td>
            <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fas fa-trash"></i></button></td>
        </tr>
    `;
        }

        function populateDatalist() {
            let datalist = $('#sparepart-list');
            datalist.empty();
            sparepartHistory.forEach(sp => {
                datalist.append(`<option value="${sp.kode}">${sp.nama} - ${sp.jenis}</option>`);
            });
        }


        $(document).ready(function() {


            populateDatalist();

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


            $('#add-item-button').on('click', function() {
                $('#items-container').append(generateRow());
            });

            $('#items-container').on('input', '.qty, .harga', function() {
                let row = $(this).closest('tr');
                let qty = parseInt(row.find('.qty').val()) || 0;
                let harga = parseInt(row.find('.harga').val()) || 0;
                let total = qty * harga;
                row.find('.total').val(total.toLocaleString('id-ID'));
                updateTotal();
            });

            // Jika user pilih kode dari datalist
            $('#items-container').on('change', '.kode', function() {
                let kode = $(this).val();
                let row = $(this).closest('tr');
                let found = sparepartHistory.find(sp => sp.kode === kode);
                if (found) {
                    row.find('.jenis').val(found.jenis);
                    row.find('.nama').val(found.nama);
                    row.find('.harga').val(found.harga);
                    row.find('.qty').val(1).trigger('input');
                }
            });

            $('#items-container').on('change', '.jenis, .nama', function() {
                let row = $(this).closest('tr');
                let jenis = row.find('.jenis').val();
                let nama = row.find('.nama').val().trim();

                if (jenis && nama) {
                    // cek apakah kombinasi jenis + nama sudah ada
                    let existing = sparepartHistory.find(sp => sp.jenis === jenis && sp.nama === nama);
                    if (!existing) {
                        let kodeBaru = generateKode(jenis);
                        row.find('.kode').val(kodeBaru);

                        // tambahkan ke history
                        sparepartHistory.push({
                            kode: kodeBaru,
                            jenis: jenis,
                            nama: nama,
                            harga: 0
                        });

                        populateDatalist(); // update datalist
                    } else {
                        row.find('.kode').val(existing.kode);
                        row.find('.harga').val(existing.harga);
                    }
                }
            });

            $('#items-container').on('click', '.remove-item', function() {
                $(this).closest('tr').remove();
                updateTotal();
            });

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
