@extends('layout.app')
@section('title', 'History')
@section('main')
    <div class="content">
        <div class="container">
            <div class="page-title text-center mb-4">
                <h3 class="text-primary">History</h3>
            </div>
            <div class="row mb-3">
                <div class="col-md-12 text-end">
                    <a href="#" class="btn btn-primary btn-lg">Export PDF</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card">
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>User</th>
                                            <th>Nama Sparepart</th>
                                            <th>Quantity</th>
                                            <th>Type</th>
                                            <th>Tanggal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>John Doe</td>
                                            <td>Brake Pad</td>
                                            <td>2</td>
                                            <td>Replacement</td>
                                            <td>2023-10-01</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary">Edit</a>
                                                <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Jane Smith</td>
                                            <td>Oil Filter</td>
                                            <td>1</td>
                                            <td>Maintenance</td>
                                            <td>2023-10-02</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary">Edit</a>
                                                <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Michael Brown</td>
                                            <td>Air Filter</td>
                                            <td>3</td>
                                            <td>Replacement</td>
                                            <td>2023-10-03</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary">Edit</a>
                                                <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                            </td>
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