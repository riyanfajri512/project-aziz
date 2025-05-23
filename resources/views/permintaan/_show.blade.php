<div data-status-id="{{ $permintaan->status_id }}">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Permintaan</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Tanggal:</strong><br> {{ $permintaan->tanggal_dibuat->format('d/m/Y') }}</p>
                            <p><strong>Dibuat Oleh:</strong><br> {{ $permintaan->user->name ?? '-' }}</p>
                        </div>
                        <div class="col-6">
                            <p><strong>Status:</strong><br>
                                @php
                                    $badgeClasses = [
                                        1 => 'bg-warning', // Pending
                                        2 => 'bg-success', // Approved
                                        3 => 'bg-danger',  // Rejected
                                        4 => 'bg-primary', // BTB
                                        5 => 'bg-info'     // SP Final
                                    ];
                                    $statusId = $permintaan->status_id;
                                    $badgeClass = $badgeClasses[$statusId] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ $permintaan->status->nama ?? 'Unknown' }}
                                </span>
                            </p>
                            <p><strong>Lokasi:</strong><br> {{ $permintaan->lokasi->nama ?? '-' }} ({{ $permintaan->lokasi->unit ?? '-' }})</p>
                        </div>
                    </div>

                    <!-- Tambahan untuk menampilkan file -->
                    @if($permintaan->file_path)
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Dokumen Pendukung:</strong><br>
                                <a href="{{ asset('storage/' . $permintaan->file_path) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> Preview Dokumen
                                </a>
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0"><i class="fas fa-truck me-2"></i>Informasi Supplier</h6>
                </div>
                <div class="card-body">
                    <p><strong>Nama Supplier:</strong><br> {{ $permintaan->suplier->nama ?? '-' }}</p>
                    <p><strong>Alamat:</strong><br> {{ $permintaan->suplier->alamat ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h6 class="card-title mb-0"><i class="fas fa-list me-2"></i>Item Permintaan</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">Kode</th>
                            <th width="15%">Jenis Kendaraan</th>
                            <th width="25%">Nama Sparepart</th>
                            <th width="10%" class="text-center">Qty</th>
                            <th width="20%" class="text-end">Harga Satuan</th>
                            <th width="20%" class="text-end">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permintaan->items as $item)
                        <tr>
                            <td>{{ $item->kode_sparepart }}</td>
                            <td>{{ $item->jenis_kendaraan }}</td>
                            <td>{{ $item->nama_sparepart }}</td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-end">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="5" class="text-end">Total</th>
                            <th class="text-end">Rp {{ number_format($permintaan->items->sum('total_harga'), 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
