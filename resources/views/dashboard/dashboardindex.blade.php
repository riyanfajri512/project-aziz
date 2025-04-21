@extends('layout.app')
@section('title', 'Dashboard Sparepart')

@section('main')
    <div class="content">
        <div class="container">
            <!-- Header -->
            <div class="row">
                <div class="col-md-12 page-header">
                    <div class="page-pretitle">Monitoring</div>
                    <h2 class="page-title">Dashboard Stok Sparepart</h2>
                </div>
            </div>

            <!-- 3 Card Utama -->
            <div class="row">
                <!-- Card 1: Total Stok Tersedia -->
                <div class="col-sm-6 col-md-4 mt-3">
                    <div class="card">
                        <div class="content">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="icon-big text-center">
                                        <i class="teal fas fa-boxes"></i>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="detail">
                                        <p class="detail-subtitle">Stok Tersedia</p>
                                        <span class="number">{{ number_format($totalStokTersedia) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="footer">
                                <hr />
                                <div class="stats">
                                    <i class="fas fa-info-circle"></i> Total semua sparepart
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Permintaan Pending -->
                <div class="col-sm-6 col-md-4 mt-3">
                    <div class="card">
                        <div class="content">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="icon-big text-center">
                                        <i class="orange fas fa-clock"></i>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="detail">
                                        <p class="detail-subtitle">Permintaan Pending</p>
                                        <span class="number">{{ $totalPermintaanPending }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="footer">
                                <hr />
                                <div class="stats">
                                    <i class="fas fa-sync-alt"></i> Menunggu persetujuan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Barang Habis -->
                <div class="col-sm-6 col-md-4 mt-3">
                    <div class="card">
                        <div class="content">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="icon-big text-center">
                                        <i class="red fas fa-exclamation-triangle"></i>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="detail">
                                        <p class="detail-subtitle">Stok Habis</p>
                                        <span class="number">{{ $totalStokHabis }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="footer">
                                <hr />
                                <div class="stats">
                                    <i class="fas fa-list"></i> Perlu pengadaan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik dan Filter -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="content">
                            <div class="head">
                                <h5 class="mb-0">Distribusi Sparepart</h5>
                                <p class="text-muted">
                                    Pilih Bulan:
                                    <input type="month" class="form-control-sm" style="width: 200px;">
                                    <button id="resetFilter" class="btn btn-sm btn-secondary">Reset</button>
                                </p>
                            </div>
                            <div class="canvas-wrapper">
                                <canvas class="chart" id="distribusiChart" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Data untuk chart
        const chartData = {
            labels: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "Mei",
                "Jun",
                "Jul",
                "Ags",
                "Sep",
                "Okt",
                "Nov",
                "Des",
            ],
            datasets: [{
                    label: "Sparepart Keluar",
                    data: @json($keluarPerBulan),
                    backgroundColor: "rgba(255, 99, 132, 0.7)", // Merah
                    borderColor: "rgba(255, 99, 132, 1)",
                    borderWidth: 1,
                },
                {
                    label: "Sparepart Masuk",
                    data: @json($masukPerBulan),
                    backgroundColor: "rgba(75, 192, 192, 0.7)", // Hijau
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1,
                },
            ],
        };


        // Inisialisasi chart
        const distribusiChart = document.getElementById("distribusiChart");
        const myChart = new Chart(distribusiChart, {
            type: "bar",
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: "Distribusi Sparepart",
                        font: {
                            size: 16
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.dataset.label}: ${ctx.raw} unit`,
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: "Jumlah Unit"
                        },
                        ticks: {
                            stepSize: 20
                        },
                    },
                    x: {
                        title: {
                            display: true,
                            text: "Bulan"
                        },
                    },
                },
            },
        });

        // Filter bulan (contoh implementasi sederhana)
        document
            .querySelector('input[type="month"]')
            .addEventListener("change", (e) => {
                const [year, month] = e.target.value.split("-");

                // Contoh filter sederhana (dalam real project, ganti dengan data aktual)
                const monthIndex = parseInt(month) - 1;
                const filteredData = {
                    labels: [chartData.labels[monthIndex]],
                    datasets: [{
                            ...chartData.datasets[0],
                            data: [chartData.datasets[0].data[monthIndex]],
                        },
                        {
                            ...chartData.datasets[1],
                            data: [chartData.datasets[1].data[monthIndex]],
                        },
                    ],
                };

                myChart.data = filteredData;
                myChart.update();
            });

        document.getElementById("resetFilter").addEventListener("click", function() {
            try {
                myChart.data.labels = chartData.labels;
                myChart.data.datasets.forEach((dataset, i) => {
                    dataset.data = chartData.datasets[i].data;
                });
                myChart.update();
                document.querySelector('input[type="month"]').value = "";
            } catch (error) {
                location.reload();
            }
        });
    </script>
@endsection
