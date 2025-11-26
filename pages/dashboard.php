<?php
session_start();
require_once __DIR__ . '/../dist/function/koneksi.php';

// Cek sesi login
if (!isset($_SESSION['id_petugas'])) {
    header('Location: ../index.php');
    exit;
}

// Ambil nama petugas
$nama_petugas = '';
if (isset($koneksi) && $koneksi) {
    $stmt = $koneksi->prepare('SELECT nama FROM petugas WHERE id_petugas = ? LIMIT 1');
    $stmt->bind_param('i', $_SESSION['id_petugas']);
    $stmt->execute();
    $stmt->bind_result($nama);
    if ($stmt->fetch()) {
        $nama_petugas = htmlspecialchars($nama, ENT_QUOTES, 'UTF-8');
    }
    $stmt->close();
}

// Halaman aktif
$active = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | MaDuPos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background: #f4f7fb; overflow-x: hidden; }

        /* SIDEBAR */
        #sidebar {
            width: 225px;
            height: 100vh;
            background: #6592d6ff;
            position: fixed;
            top: 0; left: 0;
            padding-top: 20px;
            transition: all .3s ease;
        }
        #sidebar.collapsed { width: 70px; }
        #sidebar .logo-box { text-align: center; margin-bottom: 25px; }
        #sidebar .logo-box img { width: 65px; border-radius: 50%; transition: .3s; }
        #sidebar.collapsed .text-menu, #sidebar.collapsed .tree-menu { display: none !important; }

        /* MENU */
        #sidebar .nav-link {
            color: #0b2d4a;
            font-weight: 500;
            border-radius: 8px;
            margin: 3px 10px;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
            transition: .10s;
        }
        #sidebar .nav-link:hover { background: rgba(255,255,255,.35); color: #002d61; }
        #sidebar .nav-link.active { background: #fff; color: #002d61; font-weight: 600; box-shadow: 0 2px 6px rgba(0,0,0,.15); }
        .sidebar-line { border-top: 1px solid rgba(0,0,0,.4); margin: 17px 15px; }

        /* TREE MENU */
        .tree-menu { display: none; padding-left: 30px; }
        .tree-menu .nav-link { padding: 8px 14px; margin: 3px 0; }

        /* CONTENT */
        #content { margin-left: 70px; padding: 25px; transition: all .3s ease; }
        #sidebar:not(.collapsed) ~ #content { margin-left: 260px; }
        .content-wrapper { padding: 0; margin: 0; }

        /* NAVBAR */
        .top-nav { background: #fff; padding: 10px 20px; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,.1); }
        .heading-salam { font-size: 16px; font-weight: 600; color: #0d3559; margin-top: 10px; margin-bottom: 25px; padding-left: 5px; line-height: 1.4; }
        .avatar-small { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; }

        /* FOOTER */
        .footer {
            position: fixed; bottom: 0; left: 0; height: 40px;
            background: #f4f7fb; width: 100%;
            padding: 10px 25px;
            font-size: 14px; color: #444;
            display: flex; justify-content: space-between; align-items: center;
            border-top: 1px solid rgba(0,0,0,.15);
            z-index: 900; transition: all .3s ease;
        }
        #sidebar:not(.collapsed) ~ .footer { margin-left: 260px; width: calc(100% - 260px); }
        #sidebar.collapsed ~ .footer { margin-left: 70px; width: calc(100% - 70px); }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div id="sidebar" class="collapsed">
        <div class="logo-box">
            <img src="../dist/img/posyandu.png" alt="Logo">
        </div>
        <hr class="sidebar-line">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $active=='dashboard.php'?'active':'' ?>" href="dashboard.php">
                    <i class="bi bi-house"></i>
                    <span class="text-menu">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <div class="nav-link tree-toggle" data-target="#menuKunjungan">
                    <i class="bi bi-people"></i>
                    <span class="text-menu">Kunjungan</span>
                    <i class="bi bi-caret-down-fill float-end text-menu"></i>
                </div>
                <ul id="menuKunjungan" class="tree-menu list-unstyled">
                    <li><a class="nav-link <?= $active=='kunjungan-balita.php'?'active':'' ?>" href="kunjungan-balita.php">
                        <i class="bi bi-emoji-smile"></i> <span>Balita</span></a></li>
                    <li><a class="nav-link <?= $active=='kunjungan-lansia.php'?'active':'' ?>" href="kunjungan-lansia.php">
                        <i class="bi bi-person-walking"></i> <span>Lansia</span></a></li>
                </ul>
            </li>

            <li class="nav-item">
                <div class="nav-link tree-toggle" data-target="#menuMaster">
                    <i class="bi bi-folder"></i>
                    <span class="text-menu">Data Master</span>
                    <i class="bi bi-caret-down-fill float-end text-menu"></i>
                </div>
                <ul id="menuMaster" class="tree-menu list-unstyled">
                    <li><a class="nav-link <?= $active=='petugas.php'?'active':'' ?>" href="petugas.php">
                        <i class="bi bi-person-badge"></i> <span>Data Petugas</span></a></li>
                    <li><a class="nav-link <?= $active=='balita.php'?'active':'' ?>" href="balita.php">
                        <i class="bi bi-gender-ambiguous"></i> <span>Data Balita</span></a></li>
                    <li><a class="nav-link <?= $active=='lansia.php'?'active':'' ?>" href="lansia.php">
                        <i class="bi bi-person-walking"></i> <span>Data Lansia</span></a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $active=='kunjungan.php'?'active':'' ?>" href="kunjungan.php">
                    <i class="bi bi-calendar-check"></i>
                    <span class="text-menu">Laporan</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- CONTENT -->
    <div id="content">
        <div class="content-wrapper">
            <div class="top-nav d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-4">
                    <i class="bi bi-list fs-5" id="sidebarToggle" style="cursor:pointer"></i>
                    <span class="fw-semibold" id="tanggalHariIni" style="font-size:15px;color:#083b6b;"></span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-arrows-fullscreen fs-5" id="fullscreenToggle" style="cursor:pointer"></i>
                    <i class="bi bi-box-arrow-right fs-5" style="cursor:pointer" title="Logout"></i>
                </div>
            </div>

            <h4 class="heading-salam">Halo, <strong><?= $nama_petugas ?></strong></h4>
        </div>
    </div>

    <div class="footer">
        <span>© <?= date('Y') ?> MaDuPos</span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle
        document.getElementById("sidebarToggle").addEventListener("click", () => {
            document.getElementById("sidebar").classList.toggle("collapsed");
        });

        // Tree menu toggle
        document.querySelectorAll(".tree-toggle").forEach(toggle => {
            toggle.addEventListener("click", () => {
                const target = document.querySelector(toggle.dataset.target);
                target.style.display = target.style.display === "block" ? "none" : "block";
            });
        });

        // Fullscreen toggle
        document.getElementById("fullscreenToggle").addEventListener("click", () => {
            if (!document.fullscreenElement) document.documentElement.requestFullscreen();
            else document.exitFullscreen();
        });

        // Tanggal
        function formatTanggalIndo() {
            const hari = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
            const bulan = ["Januari","Februari","Maret","April","Mei","Juni",
                           "Juli","Agustus","September","Oktober","November","Desember"];
            const now = new Date();
            return `${hari[now.getDay()]}, ${now.getDate()} ${bulan[now.getMonth()]} ${now.getFullYear()}`;
        }
        document.getElementById("tanggalHariIni").innerText = formatTanggalIndo();
    </script>
</body>
</html>
