<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- CSS -->
    <link href="{{ asset('template/assets/vendor/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/vendor/fontawesome/css/solid.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/vendor/fontawesome/css/brands.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/vendor/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/css/master.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/vendor/flagiconcss/css/flag-icon.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>


    @yield('sidebar')

    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <div class="container">
                    <img src="{{ asset('template/assets/img/logo_indomaret.png') }}"
                    style="width: 150px; height: auto;"
                         alt="bootraper logo" class="app-logo">
                </div>
            </div>
            <ul class="list-unstyled components text-secondary">
                <li>
                    <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('permintaan.index') }}"><i class="fas fa-file-alt"></i> Permintaan</a>
                </li>
                <li>
                    <a href="{{ route('penerimaan.index') }}"><i class="fas fa-box-open"></i> Penerimaan</a>
                </li>
                <li>
                    <a href="{{ route('pendistribusian') }}"><i class="fas fa-truck"></i> Pendistribusian</a>
                </li>
                <li>
                    <a href="{{ route('history') }}"><i class="fas fa-history"></i> History</a>
                </li>

                <li>
                    <a href="#uielementsmenu" data-bs-toggle="collapse" aria-expanded="false"
                        class="dropdown-toggle no-caret-down"><i class="fas fa-layer-group">
                        </i> MD</a>
                    <ul class="collapse list-unstyled" id="uielementsmenu">

                        <li>
                            <a href="{{ route('supplier') }}"><i class="fas fa-angle-right"></i> Supplier</a>
                        </li>
                        <li>
                            <a href="{{ route('kategori') }}"><i class="fas fa-angle-right"></i> Kategori</a>
                        </li>
                        <li>
                            <a href="{{ route('user') }}"><i class="fas fa-angle-right"></i> User</a>
                        </li>
                        <li>
                            <a href="{{ route('jenis-kendaraan') }}"><i class="fas fa-angle-right"></i> Jenis
                                Kendaraan</a>
                        </li>
                        <li>
                            <a href="{{ route('lokasi') }}"><i class="fas fa-angle-right"></i>Lokasi</a>
                        </li>
                        <li>
                            <a href="{{ route('sp') }}"><i class="fas fa-angle-right"></i> Sparepart</a>
                        </li>
                        </>
                </li>
        </nav>
        <div id="body" class="active">
            <!-- navbar navigation component -->
            <nav style="height: 85px" class="navbar navbar-expand-lg navbar-white bg-white">
                <button type="button" id="sidebarCollapse" class="btn btn-light">
                    <i class="fas fa-bars"></i><span></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            {{-- <div class="nav-dropdown">
                                <a href="#" id="nav1"
                                    class="nav-item nav-link dropdown-toggle text-secondary" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fas fa-link"></i> <span>Quick Links</span> <i style="font-size: .8em;"
                                        class="fas fa-caret-down"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end nav-link-menu" aria-labelledby="nav1">
                                    <ul class="nav-list">
                                        <li><a href="" class="dropdown-item"><i class="fas fa-list"></i> Access
                                                Logs</a></li>
                                        <div class="dropdown-divider"></div>
                                        <li><a href="" class="dropdown-item"><i class="fas fa-database"></i> Back
                                                ups</a></li>
                                        <div class="dropdown-divider"></div>
                                        <li><a href="" class="dropdown-item"><i
                                                    class="fas fa-cloud-download-alt"></i> Updates</a></li>
                                        <div class="dropdown-divider"></div>
                                        <li><a href="" class="dropdown-item"><i class="fas fa-user-shield"></i>
                                                Roles</a></li>
                                    </ul>
                                </div>
                            </div> --}}
                        </li>
                        <li class="nav-item dropdown">
                            <div class="nav-dropdown">
                                <a href="#" id="nav2"
                                    class="nav-item nav-link dropdown-toggle text-secondary" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fas fa-user"></i>
                                    <span>{{ Auth::user()->name }}</span>
                                    <i style="font-size: .8em;" class="fas fa-caret-down"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end nav-link-menu">
                                    <ul class="nav-list">
                                        {{-- <li><a href="" class="dropdown-item"><i
                                                    class="fas fa-address-card"></i>
                                                Profile</a></li>
                                        <li><a href="" class="dropdown-item"><i class="fas fa-envelope"></i>
                                                Messages</a></li>
                                        <li><a href="" class="dropdown-item"><i class="fas fa-cog"></i>
                                                Settings</a></li> --}}
                                        {{-- <div class="dropdown-divider"></div> --}}
                                        <li>
                                            <a href="javascript:void(0)" id="logoutBtn" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt"></i> Logout
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- end of navbar navigation -->
            @yield('main')
        </div>
    </div>
    <!-- JS -->
    <script src="{{ asset('template/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/chartsjs/Chart.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/dashboard-charts.js') }}"></script>
    <script src="{{ asset('template/assets/js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#logoutBtn").click(function(event) {
                event.preventDefault();

                Swal.fire({
                    title: "Anda yakin ingin logout?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Logout!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/logout",
                            type: "POST",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Logout Berhasil",
                                    text: "Anda akan dialihkan ke halaman login...",
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = response.redirect;
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: "error",
                                    title: "Terjadi Kesalahan",
                                    text: "Gagal logout, coba lagi."
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

    @yield('script')
</body>

</html>
