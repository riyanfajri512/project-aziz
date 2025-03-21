<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</head>

<body>


    @yield('sidebar')

    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <img src="assets/img/bootstraper-logo.png" alt="bootraper logo" class="app-logo">
            </div>
            <ul class="list-unstyled components text-secondary">
                <li>
                    <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>

                </li>
                <li>
                    <a href="#uielementsmenu" data-bs-toggle="collapse" aria-expanded="false"
                        class="dropdown-toggle no-caret-down"><i class="fas fa-layer-group"></i> MD</a>
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
                            <a href="{{ route('jenis-kendaraan') }}"><i class="fas fa-angle-right"></i> Jenis Kendaraan</a>
                        </li>
                        <li>
                            <a href="{{ route('lokasi') }}"><i class="fas fa-angle-right"></i>Lokasi</a>
                        </li>
                        <li>
                            <a href="{{ route('sp') }}"><i class="fas fa-angle-right"></i> SP</a>
                        </li>
                    </>
                </li>
        </nav>
        <div id="body" class="active">
            <!-- navbar navigation component -->
            <nav class="navbar navbar-expand-lg navbar-white bg-white">
                <button type="button" id="sidebarCollapse" class="btn btn-light">
                    <i class="fas fa-bars"></i><span></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <div class="nav-dropdown">
                                <a href="#" id="nav1" class="nav-item nav-link dropdown-toggle text-secondary"
                                    data-bs-toggle="dropdown" aria-expanded="false">
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
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <div class="nav-dropdown">
                                <a href="#" id="nav2" class="nav-item nav-link dropdown-toggle text-secondary"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user"></i> <span>John Doe</span> <i style="font-size: .8em;"
                                        class="fas fa-caret-down"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end nav-link-menu">
                                    <ul class="nav-list">
                                        <li><a href="" class="dropdown-item"><i class="fas fa-address-card"></i>
                                                Profile</a></li>
                                        <li><a href="" class="dropdown-item"><i class="fas fa-envelope"></i>
                                                Messages</a></li>
                                        <li><a href="" class="dropdown-item"><i class="fas fa-cog"></i>
                                                Settings</a></li>
                                        <div class="dropdown-divider"></div>
                                        <li><a href="" class="dropdown-item"><i class="fas fa-sign-out-alt"></i>
                                                Logout</a></li>
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
    <script src="{{ asset('js/sweetalert2.js') }}"></script>

    @yield('script')
</body>

</html>

