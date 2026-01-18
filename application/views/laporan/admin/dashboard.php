<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - APOTEK FRIENDLY</title>

    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">

    <style>
    body {
        background: #e9eef7;
        font-family: "Segoe UI", sans-serif;
    }

    /* ========== SIDEBAR ========== */
    .sidebar {
        width: 250px;
        height: 100vh;
        background: #111c44;
        color: white;
        padding: 25px;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
    }

    .sidebar h3 {
        font-weight: bold;
        margin-bottom: 35px;
        text-align: center;
    }

    .sidebar a {
        margin-bottom: 10px;
        padding: 12px 15px;
        color: white;
        background: rgba(255,255,255,0.08);
        border-radius: 10px;
        transition: .2s;
        font-weight: 500;
    }

    .sidebar a:hover {
        background: rgba(255,255,255,0.22);
        text-decoration: none;
    }

    /* ========== CONTENT ========== */
    .content {
        margin-left: 270px;
        padding: 35px;
    }

    h2.section-title {
        font-weight: 700;
        color: #111c44;
        margin-bottom: 25px;
    }

    /* ========== CARD WRAPPER ========== */
    .dashboard-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
        margin-top: 25px;
    }

    /* ========== CARD ========== */
    .info-card {
        flex: 1;
        min-width: 230px;
        padding: 28px;
        border-radius: 20px;
        font-weight: 600;
        color: black;
        box-shadow: 0 4px 12px rgba(0,0,0,0.10);
        transition: .2s ease;
        border: none;
    }

    .info-card:hover {
        transform: translateY(-4px);
    }

    .info-title {
        font-size: 16px;
        opacity: .7;
    }

    .info-value {
        font-size: 32px;
        font-weight: 800;
        margin-top: 8px;
    }

    .color-green { background:#b5f5c8; }
    .color-pink { background:#ffd6e8; }
    .color-yellow { background:#ffe8b3; }
    .color-blue { background:#c7dbff; }
    .color-softgreen { background:#d4ffea; }
    .color-red { background:#ffc8c8; }

    /* ========== GRAFIK ========== */
    .grafik-container {
        width: 45%;
        margin: 20px auto;
    }

    .grafik-container canvas {
        max-height: 220px;
    }

    .chart-box {
        width: 48%;
        background: white;
        padding: 20px;
        border-radius: 18px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.12);
        display: inline-block;
        vertical-align: top;
        margin-top: 20px;
    }

    .chart-box canvas {
        max-height: 260px;
    }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h3>APOTEK FRIENDLY</h3>

    <a href="<?= site_url('dashboard') ?>">📊 Dashboard</a>
    <a href="<?= site_url('obat') ?>">💊 Obat</a>
    <a href="<?= site_url('kategori') ?>">📂 Kategori</a>
    <a href="<?= site_url('pemasok') ?>">🚚 Pemasok</a>
    <a href="<?= site_url('penjualan') ?>">🧾 Penjualan</a>
    <a href="<?= site_url('pembelian') ?>">🛒 Pembelian</a>
    <a href="<?= site_url('laporan') ?>">📑 Laporan</a>
    <a href="<?= site_url('jamkerja') ?>">🕒 Jam Kerja</a>
</div>

<!-- CONTENT -->
<div class="content">

    <h2 class="section-title">Dashboard Sistem Informasi Apotek</h2>

    <!-- CARD ATAS -->
    <div class="dashboard-cards">

        <div class="info-card color-green">
            <div class="info-title">Total Obat</div>
            <div class="info-value"><?= $total_obat ?></div>
        </div>

        <div class="info-card color-pink">
            <div class="info-title">Total Kategori</div>
            <div class="info-value"><?= $total_kategori ?></div>
        </div>

        <div class="info-card color-yellow">
            <div class="info-title">Total Pemasok</div>
            <div class="info-value"><?= $total_pemasok ?></div>
        </div>

        <div class="info-card color-blue">
            <div class="info-title">Total Unit</div>
            <div class="info-value"><?= $total_unit ?></div>
        </div>

    </div>

    <!-- CARD BAWAH -->
    <div class="dashboard-cards">

        <div class="info-card color-softgreen">
            <div class="info-title">Total Penjualan</div>
            <div class="info-value">Rp <?= number_format($total_penjualan,0,",",".") ?></div>
        </div>

        <div class="info-card color-red">
            <div class="info-title">Total Pembelian</div>
            <div class="info-value">Rp <?= number_format($total_pembelian,0,",",".") ?></div>
        </div>

    </div>

    <!-- GRAFIK -->
    <div class="grafik-container">
        <canvas id="grafikPenjualan"></canvas>
    </div>

    <div class="chart-box">
        <h5>Grafik Penjualan Per Hari</h5>
        <canvas id="salesChartDaily"></canvas>
    </div>

    <div class="chart-box">
        <h5>Grafik Penjualan Per Bulan</h5>
        <canvas id="salesChartMonthly"></canvas>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const hariLabels = <?= json_encode(array_column($grafik_harian, 'hari')) ?>;
const hariData = <?= json_encode(array_column($grafik_harian, 'total')) ?>;

new Chart(document.getElementById('salesChartDaily'), {
    type: 'line',
    data: {
        labels: hariLabels,
        datasets: [{
            label: 'Penjualan Harian',
            data: hariData,
            borderColor: '#111c44',
            backgroundColor: 'rgba(17,28,68,0.2)',
            borderWidth: 3,
            tension: 0.35,
            fill: true
        }]
    }
});

const bulanLabels = <?= json_encode(array_column($grafik_bulanan, 'bulan')) ?>;
const bulanData = <?= json_encode(array_column($grafik_bulanan, 'total')) ?>;

new Chart(document.getElementById('salesChartMonthly'), {
    type: 'line',
    data: {
        labels: bulanLabels,
        datasets: [{
            label: 'Penjualan Bulanan',
            data: bulanData,
            borderColor: '#111c44',
            backgroundColor: 'rgba(17,28,68,0.2)',
            borderWidth: 3,
            tension: 0.35,
            fill: true
        }]
    }
});
</script>

</body>
</html>
