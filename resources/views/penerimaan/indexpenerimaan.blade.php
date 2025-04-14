@extends('layout.app')
@section('title', 'Penerimaan')

@section('main')
    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>Penerimaan</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="col-md-4">
                                <input type="date" class="form-control" id="filterTanggal" placeholder="Filter Tanggal">
                            </div>
                            <div>
                                <a href="{{ route('penerimaan.tambahan') }}" class="btn btn-primary">Tambah Penerimaan</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table table-striped table-hover" id="tabelPenerimaan"
                                style="width: max-content; min-width: 100%;">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Penerimaan</th>
                                        <th>Tanggal</th>
                                        <th>User</th>
                                        <th>Kode Permintaan</th>
                                        <th>Grand Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan diisi oleh DataTables -->
                                </tbody>
                            </table>
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
        const table = $('#tabelPenerimaan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('penerimaan.index') }}",
                data: function(d) {
                    d.tanggal = $('#filterTanggal').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'kode_penerimaan', name: 'kode_penerimaan' },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'kode_permintaan', name: 'kode_permintaan' },
                { data: 'grand_total', name: 'grand_total' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        $('#filterTanggal').change(function() {
            table.draw();
        });
    });
</script>
@endsection