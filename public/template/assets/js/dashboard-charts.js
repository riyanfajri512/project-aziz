// Data untuk chart
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
    datasets: [
      {
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
        font: { size: 16 },
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
        title: { display: true, text: "Jumlah Unit" },
        ticks: { stepSize: 20 },
      },
      x: {
        title: { display: true, text: "Bulan" },
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
      datasets: [
        {
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

document.getElementById("resetFilter").addEventListener("click", function () {
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
