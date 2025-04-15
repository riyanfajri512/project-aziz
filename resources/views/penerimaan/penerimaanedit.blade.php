@extends('layout.app')
@section('title', 'Edit Penerimaan')
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
                <h3>Edit Penerimaan</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            Form Edit Penerimaan
                        </div>

                        <div class="card-body">
                        <form id="formPenerimaan" action="{{ route('penerimaan.update', $penerimaan->id) }}" method="POST" data-id="{{ $penerimaan->id }}">
                        @csrf
                        @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Permintaan</label>
                                            <input type="text" class="form-control" value="{{ $penerimaan->permintaan->kode_pemesanan }}" readonly>
                                            <input type="hidden" name="permintaan_id" value="{{ $penerimaan->permintaan_id }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">User</label>
                                            <input type="text" class="form-control" value="{{ $penerimaan->user->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" name="tanggal" 
                                                   value="{{ date('Y-m-d', strtotime($penerimaan->tanggal)) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kode Penerimaan</label>
                                            <input type="text" class="form-control" name="kode_penerimaan"
                                                   value="{{ $penerimaan->kode_penerimaan }}" readonly>
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
                                                <th class="text-center">Qty Permintaan</th>
                                                <th class="text-center">Qty Diterima</th>
                                                <th class="text-right">Harga</th>
                                                <th class="text-right">Total</th>
                                            </tr>
                                        </thead>

                                        <tbody id="items-container">
                                            @foreach($penerimaan->items as $item)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="items[{{ $item->id }}][kode_sparepart]" value="{{ $item->kode_sparepart }}">
                                                    {{ $item->kode_sparepart }}
                                                </td>
                                                <td>
                                                    <input type="hidden" name="items[{{ $item->id }}][jenis_kendaraan]" value="{{ $item->jenis_kendaraan }}">
                                                    {{ $item->jenis_kendaraan }}
                                                </td>
                                                <td>
                                                    <input type="hidden" name="items[{{ $item->id }}][nama_sparepart]" value="{{ $item->nama_sparepart }}">
                                                    {{ $item->nama_sparepart }}
                                                </td>
                                                <td class="text-center">
                                                    <input type="hidden" name="items[{{ $item->id }}][qty]" value="{{ $item->qty }}">
                                                    {{ $item->qty }}
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" name="items[{{ $item->id }}][qty_diterima]" 
                                                           class="form-control qty-diterima"
                                                           value="{{ $item->qty_diterima }}"
                                                           data-harga="{{ $item->harga }}"
                                                           data-max-qty="{{ $item->qty }}"
                                                           max="{{ $item->qty }}"
                                                           min="0">
                                                    <input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}">
                                                    <input type="hidden" name="items[{{ $item->id }}][harga]" value="{{ $item->harga }}">
                                                </td>
                                                <td class="text-right">
                                                    <input type="hidden" name="items[{{ $item->id }}][harga_display]" value="{{ $item->harga }}">
                                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                                </td>
                                                <td class="text-right item-total">
                                                    <input type="hidden" name="items[{{ $item->id }}][total_harga]" 
                                                           value="{{ $item->qty_diterima * $item->harga }}">
                                                    Rp {{ number_format($item->qty_diterima * $item->harga, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-right">
                                                    <strong>Grand Total:</strong>
                                                </td>
                                                <td class="text-right">
                                                    <span id="grand-total">Rp {{ number_format($penerimaan->grand_total, 0, ',', '.') }}</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi/Catatan</label>
                                        <textarea class="form-control" name="deskripsi">{{ $penerimaan->deskripsi }}</textarea>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('penerimaan.index') }}" class="btn btn-secondary ms-2">Batal</a>
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
            // Fungsi untuk menghitung ulang grand total
            function recalculateGrandTotal() {
                let grandTotal = 0;

                $('.item-total').each(function() {
                    const totalText = $(this).text().replace(/[^\d]/g, '');
                    grandTotal += parseFloat(totalText) || 0;
                });

                $('#grand-total').text('Rp ' + grandTotal.toLocaleString('id-ID'));
            }

            // Event listener untuk input qty diterima
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
                $(this).closest('tr').find('.item-total').text('Rp ' + total.toLocaleString('id-ID'));
                $(this).closest('tr').find('input[name*="[total_harga]"]').val(total);

                // Hitung ulang grand total
                recalculateGrandTotal();
            });

            // Submit form
            $("#formPenerimaan").submit(function(e) {
                e.preventDefault();

                // Validasi penerimaan tidak melebihi permintaan
                let isValid = true;
                $('.qty-diterima').each(function() {
                    const penerimaan = parseInt($(this).val()) || 0;
                    const permintaan = parseInt($(this).attr('max'));

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
                    title: 'Menyimpan Perubahan...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Ambil data form
                const formData = $(this).serializeArray();
                const penerimaanId = $(this).data('id');
                
                // Tambahkan method spoofing untuk PUT
                formData.push({name: '_method', value: 'PUT'});

                // Kirim data via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $.param(formData),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Data penerimaan berhasil diupdate',
                            timer: 2000,
                            showConfirmButton: false,
                            willClose: () => {
                                window.location.href = "{{ route('penerimaan.index') }}";
                            }
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menyimpan perubahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.statusText) {
                            errorMessage = xhr.statusText;
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