@extends('layout.app')
@section('title', 'Kategori')


@section('main')
    <div class="content">
        <div class="container">
            <div class="page-title">
                <h3>Kategori</h3>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">Kategori</div>
                        <div class="card-body">
                            <p class="card-title">Add class <code>.table</code> inside table element</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nomor</th>
                                            <th>Nama</th>
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