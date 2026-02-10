<?php
if (!isset($_SESSION)) session_start();
?>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color:purple;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            Perpustakaan Sekolah
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="../admin/dashboard.php">🏠 Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/buku.php"> Data Buku</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/kategori.php">Kategori</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/siswa.php">Siswa</a></li>
                    <li class="nav-item"><a class="nav-link" href="../admin/guru.php">Guru</a></li>

                    <li class="nav-item"><a class="nav-link" href="../admin/ortu.php">Orangtua</a></li>

                <?php elseif ($_SESSION['role'] === 'siswa'): ?>
                    <li class="nav-item"><a class="nav-link" href="../siswa/dashboard.php"> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../siswa/buku.php">Buku</a></li>
                    <li class="nav-item"><a class="nav-link" href="../siswa/peminjaman.php">Peminjaman Saya</a></li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto">
                <a class="nav-item nav-link text-danger" href="../auth/logout.php">Keluar</a>

            </ul>
        </div>
    </div>
</nav>