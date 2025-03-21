@extends('layout.app')
@section('title', 'Supplier')
@section('main')
    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>Supplier</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">Supplier</div>
                        <div class="card-body">
                            <p class="card-title">Add class <code>.table</code> inside table element</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nama Supplier</th>
                                            <th>Lokasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Motor</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Mobil</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Truk</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection