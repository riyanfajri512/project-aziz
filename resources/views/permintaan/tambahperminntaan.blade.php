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
                                                value="{{ $kodePemesanan }}" readonly>
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
                                            <select name="lokasi_id" id="lokasi" class="form-control" readonly>
                                                @foreach ($lokasiList as $lokasi)
                                                    <option value="{{ $lokasi->id }}"
                                                        {{ $loop->first ? 'selected' : '' }}>
                                                        {{ $lokasi->nama }} - {{ $lokasi->unit }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <label class="form-label">Search Sparepart</label>
                                                <select class="form-control select2-sparepart" id="sparepart-search">
                                                    <option value="">-- Pilih Sparepart --</option>
                                                    @foreach ($spareparts as $sp)
                                                        <option value="{{ $sp->id }}" data-kode="{{ $sp->kode }}"
                                                            data-nama="{{ $sp->nama }}" data-harga="{{ $sp->harga }}"
                                                            data-jenis="{{ $sp->jenis }}">
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
                                            <label class="form-label">Tanggal Dibuat</label>
                                            <input type="date" class="form-control" name="tanggal_dibuat">
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Suplier</label>
                                            <select class="form-select" name="supplier_id" required>
                                                <option value="" selected disabled>-- Pilih Suplier --</option>
                                                @foreach ($suplierList as $supplier)
                                                    <option value="{{ $supplier->id }}">
                                                        {{ $supplier->nama }}
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
                                    <a href="{{ route('permintaan.index') }}" class="btn btn-secondary ms-2">Batal</a>
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
        $('.select2-sparepart').select2({
            placeholder: "Cari sparepart...",
            allowClear: true
        });

        // Variabel untuk menyimpan sparepart yang sudah dipilih
        let selectedSpareparts = [];
        let sparepartHistory = @json($spareparts->toArray());

        // Fungsi untuk menambahkan sparepart ke tabel
        $('#btn-add-sparepart').click(function() {
            const selectElement = $('#sparepart-search');
            const selectedOption = selectElement.find('option:selected');
            const sparepartId = selectedOption.val();

            if (!sparepartId) {
                Swal.fire({
                            icon: 'error',
                            title: 'Search kosong',
                            text: 'Silahkan pilih sparepart terlebih dahulu',
                            confirmButtonText: 'OK'
                        })
                return;
            }

            if (selectedSpareparts.includes(sparepartId)) {
                Swal.fire({
                            icon: 'error',
                            title: 'Duplikat sparepart',
                            text: 'Sparepart ini sudah ditambahkan!',
                            confirmButtonText: 'OK'
                        })
                return;
            }

            selectedSpareparts.push(sparepartId);

            const kode = selectedOption.data('kode');
            const nama = selectedOption.data('nama');
            const jenis = selectedOption.data('jenis');
            const harga = selectedOption.data('harga');

            const newRow = `
            <tr data-id="${sparepartId}">
                <td>
                    <input type="text" class="form-control kode" name="kode_sparepart[]" value="${kode}" readonly>
                </td>
                <td>
                    <input type="text" class="form-control jenis" name="jenis_kendaraan[]" value="${jenis}" readonly>
                </td>
                <td>
                    <input type="text" class="form-control nama" name="nama_sparepart[]" value="${nama}" readonly>
                </td>
                <td class="text-center">
                    <input type="number" class="form-control qty" name="qty[]" min="1" value="1">
                </td>
                <td class="text-right">
                    <input type="number" class="form-control harga text-right" name="harga[]" value="${harga}">
                </td>
                <td class="text-right">
                    <input type="text" class="form-control total text-right" name="total_harga[]" value="${harga}" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

            $('#items-container').append(newRow);
            selectElement.val('').trigger('change');
            updateTotal();
        });

        function generateRow() {
            return `
            <tr>
                <td>
                    <input type="text" class="form-control kode" name="kode_sparepart[]" list="sparepart-list">
                </td>
                <td>
                    <select class="form-control jenis" name="jenis_kendaraan[]">
                        <option value="">Pilih</option>
                        @foreach ($jenisList as $jenis)
                            <option value="{{ $jenis->nama }}">{{ $jenis->nama }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control nama" name="nama_sparepart[]">
                </td>
                <td class="text-center">
                    <input type="number" class="form-control qty" name="qty[]" min="1" value="1">
                </td>
                <td class="text-right">
                    <input type="number" class="form-control harga text-right" name="harga[]" value="0">
                </td>
                <td class="text-right">
                    <input type="text" class="form-control total text-right" name="total_harga[]" value="0" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        }

        // Fungsi untuk update total
        function updateTotal() {
            let total = 0;
            $('#items-container tr').each(function() {
                let qty = parseInt($(this).find('.qty').val()) || 0;
                let harga = parseInt($(this).find('.harga').val()) || 0;
                let rowTotal = qty * harga;
                $(this).find('.total').val(rowTotal.toLocaleString('id-ID'));
                total += rowTotal;
            });
            $('#total_payment').val(total.toLocaleString('id-ID'));
        }



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


            // Fungsi untuk menambahkan baris kosong
            $('#add-item-button').click(function() {
                $('#items-container').append(generateRow());
            });


            // Event delegation untuk input qty dan harga
            $('#items-container').on('input', '.qty, .harga', function() {
                updateTotal();
            });

            // Event delegation untuk tombol hapus
            $('#items-container').on('click', '.remove-item', function() {
                const row = $(this).closest('tr');
                const sparepartId = row.data('id');

                // Hapus dari array selected jika ada
                if (sparepartId) {
                    selectedSpareparts = selectedSpareparts.filter(id => id !== sparepartId);
                }

                row.remove();
                updateTotal();
            });

            // Fungsi untuk generate kode otomatis
            function generateKode(jenis) {
                let prefix = jenis.trim();
                let countFromHistory = sparepartHistory.filter(sp => sp.jenis === jenis).length;
                let countInTable = 0;

                $('#items-container tr').each(function() {
                    let rowJenis = $(this).find('.jenis').val();
                    let rowKode = $(this).find('.kode').val();
                    if (rowJenis === jenis && rowKode && rowKode.startsWith(prefix + '-')) {
                        countInTable++;
                    }
                });

                let totalCount = countFromHistory + countInTable + 1;
                return `${prefix}-${totalCount.toString().padStart(3, '0')}`;
            }

            // Jika user mengubah jenis atau nama pada baris kosong
            $('#items-container').on('change', '.jenis, .nama', function() {
                let row = $(this).closest('tr');
                let jenis = row.find('.jenis').val();
                let nama = row.find('.nama').val().trim();

                if (jenis && nama) {
                    let existing = sparepartHistory.find(sp => sp.jenis === jenis && sp.nama === nama);
                    if (!existing) {
                        let kodeBaru = generateKode(jenis);
                        row.find('.kode').val(kodeBaru);
                    } else {
                        row.find('.kode').val(existing.kode);
                        row.find('.harga').val(existing.harga);
                    }
                }
            });

            $('#formPermintaan').on('submit', function(e) {
                e.preventDefault();

                // Tampilkan loading spinner
                Swal.fire({
                    title: 'Menyimpan Data',
                    html: 'Sedang memproses permintaan...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Format data items
                let items = [];
                $('#items-container tr').each(function() {
                    items.push({
                        kode_sparepart: $(this).find('[name="kode_sparepart[]"]').val(),
                        jenis_kendaraan: $(this).find('[name="jenis_kendaraan[]"]').val(),
                        nama_sparepart: $(this).find('[name="nama_sparepart[]"]').val(),
                        qty: $(this).find('[name="qty[]"]').val(),
                        harga: $(this).find('[name="harga[]"]').val(),
                        total_harga: $(this).find('[name="total_harga[]"]').val()
                    });
                });

                // Buat FormData
                let formData = new FormData(this);
                formData.append('items', JSON.stringify(items));

                // Kirim ke backend
                $.ajax({
                    url: '/permintaan/simpan',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Permintaan berhasil disimpan',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                            confirmButtonText: 'Tutup'
                        });
                    }
                });
            });
        });
    </script>
@endsection
