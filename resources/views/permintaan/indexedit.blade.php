@extends('layout.app')
@section('title', 'Edit Permintaan')

@section('main')
<!-- halaman edit permintaan -->
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
                <h3>Edit Permintaan</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            Form Edit Permintaan
                        </div>

                        <div class="card-body">
                        <form id="formPermintaan" action="{{ route('permintaan.update', $permintaan->id) 
                        }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                                <input type="hidden" name="id" value="{{ $permintaan->id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">No Permintaan</label>
                                            <input type="text" class="form-control" name="kode_pemesanan"
                                                value="{{ $permintaan->kode_pemesanan }}" readonly>
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Unit (Pembuat)</label>
                                            <input type="text" class="form-control" name="unit"
                                                value="{{ $permintaan->user->name }}" readonly>
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Cabang</label>
                                            <select name="lokasi_id" id="lokasi" class="form-control" readonly>
                                                @foreach ($lokasiList as $lokasi)
                                                    <option value="{{ $lokasi->id }}"
                                                        {{ $permintaan->lokasi_id == $lokasi->id ? 'selected' : '' }}>
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
                                            <input type="date" class="form-control" name="tanggal"
                                             value="{{ $permintaan->tanggal_dibuat->format('Y-m-d') }}" required>
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Suplier</label>
                                            <select class="form-select" name="supplier_id" required>
                                                <option value="" disabled>-- Pilih Suplier --</option>
                                                @foreach ($suplierList as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ $permintaan->supplier_id == $supplier->id ? 'selected' : '' }}>
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
                                            @if($permintaan->file_path)
                                                <small class="text-muted">File saat ini: 
                                                    <a href="{{ asset('storage/' . $permintaan->file_path) }}" 
                                                       target="_blank">{{ basename($permintaan->file_path) }}</a>
                                                </small>
                                            @endif
                                        </div>

                                        <div id="pdf-preview-area" class="mt-3 {{ $permintaan->file_path ? '' : 'd-none' }}">
                                            <label class="form-label">Preview</label>
                                            <div class="card">
                                                <div class="card-body p-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                                            <span class="pdf-filename">
                                                                @if($permintaan->file_path)
                                                                    {{ basename($permintaan->file_path) }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div>
                                                            @if($permintaan->file_path)
                                                                <a href="{{ asset('storage/' . $permintaan->file_path) }}"
                                                                    class="btn btn-sm btn-outline-primary view-pdf"
                                                                    target="_blank">
                                                                    <i class="fas fa-eye me-1"></i> Preview
                                                                </a>
                                                            @endif
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
                                                <!-- Isi dengan item yang sudah ada -->
                                                @foreach($permintaan->items as $item)
                                                <tr data-item-id="{{ $item->id }}">
                                                    <td>
                                                        <input type="text" class="form-control" name="kode_sparepart[]" 
                                                            value="{{ $item->kode_sparepart }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="jenis_kendaraan[]" 
                                                            value="{{ $item->jenis_kendaraan }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="nama_sparepart[]" 
                                                            value="{{ $item->nama_sparepart }}" readonly>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="number" class="form-control qty" name="qty[]" 
                                                            value="{{ $item->qty }}" min="1" required>
                                                    </td>
                                                    <td class="text-right">
                                                        <input type="number" class="form-control harga" name="harga[]" 
                                                            value="{{ $item->harga }}" readonly>
                                                    </td>
                                                    <td class="text-right">
                                                        <input type="text" class="form-control total" name="total_harga[]" 
                                                            value="{{ $item->total_harga }}" readonly>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm remove-item">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
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
                                                                    value="{{ number_format($permintaan->total_payment) }}" readonly>
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
                                            <textarea class="form-control" name="deskripsi">{{ $permintaan->deskripsi }}</textarea>
                                            <small class="text-danger d-none">Field ini wajib diisi</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="button" id="submit-button" class="btn btn-primary">Update</button>
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
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2-sparepart').select2({
                placeholder: "Cari sparepart...",
                allowClear: true
            });

            // Variabel untuk menyimpan sparepart yang sudah dipilih
            let selectedSpareparts = @json($permintaan->items->pluck('sparepart_id')->filter()->toArray());
            let sparepartHistory = @json($spareparts->toArray());

            // Inisialisasi total saat pertama kali load
            updateTotal();

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
                    });
                    return;
                }

                if (selectedSpareparts.includes(sparepartId)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Duplikat sparepart',
                        text: 'Sparepart ini sudah ditambahkan!',
                        confirmButtonText: 'OK'
                    });
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
                        <input type="text" class="form-control kode" name="kode_sparepart[]" value="${kode}"readonly>
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

            // Submit button event handler - FIXED
            $('#submit-button').on('click', function(e) {
                e.preventDefault();
                submitForm();
            });

            // Replace the submitForm function with this updated version
            function submitForm() {
                // Show loading state
                Swal.fire({
                    title: 'Menyimpan Data',
                    html: 'Sedang memproses...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                // Get the form
                const form = $('#formPermintaan')[0];
                const formData = new FormData(form);
                
                // Ensure tanggal is included
                const tanggal = $('input[name="tanggal"]').val();
                if (!tanggal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Tanggal dibuat wajib diisi',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                formData.append('tanggal_dibuat', tanggal);
                
                // Collect items data
                let items = [];
                $('#items-container tr').each(function() {
                    let itemId = $(this).data('item-id') || null;
                    
                    items.push({
                        id: itemId,
                        kode_sparepart: $(this).find('[name="kode_sparepart[]"]').val(),
                        jenis_kendaraan: $(this).find('[name="jenis_kendaraan[]"]').val(),
                        nama_sparepart: $(this).find('[name="nama_sparepart[]"]').val(),
                        qty: $(this).find('[name="qty[]"]').val(),
                        harga: $(this).find('[name="harga[]"]').val(),
                        total_harga: $(this).find('[name="total_harga[]"]').val().replace(/[.,]/g, '')
                    });
                });

                // Add items to formData
                formData.append('items', JSON.stringify(items));
                
                // Get the CSRF token
                const token = $('meta[name="csrf-token"]').attr('content');
                
                // Send AJAX request
                $.ajax({
                    url: $('#formPermintaan').attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Data berhasil diupdate',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed && response.redirect) {
                                window.location.href = "{{ route('permintaan.index') }}";
                            }
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan';
                        
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            
                            if (xhr.responseJSON.errors) {
                                errorMessage = Object.values(xhr.responseJSON.errors)
                                    .flat()
                                    .join('<br>');
                            }
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            html: errorMessage,
                            confirmButtonText: 'Tutup'
                        });
                    }
                });
            }
        });
    </script>
@endsection