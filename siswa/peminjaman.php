<?php
session_start();
include '../incld/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Anda harus login terlebih dahulu'); window.location='../login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];

// --- Proses pengembalian langsung ---
if (isset($_POST['kembalikan'])) {
    $id_peminjaman = (int)$_POST['id_peminjaman'];

    // Ambil data peminjaman + buku
    $query = mysqli_query($conn, "
        SELECT p.*, b.id_buku, b.judul, p.tanggal_kembali
        FROM peminjaman p
        JOIN buku b ON p.id_buku = b.id_buku
        WHERE p.id_peminjaman = $id_peminjaman
    ");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $tanggal_hari_ini = date("Y-m-d");
        $denda_per_hari = 1000;
        $telat = 0;
        $denda = 0;

        if ($tanggal_hari_ini > $data['tanggal_kembali']) {
            $telat = ceil((strtotime($tanggal_hari_ini) - strtotime($data['tanggal_kembali'])) / 86400);
            $denda = $telat * $denda_per_hari;
        }

        // Update status peminjaman
        mysqli_query($conn, "
            UPDATE peminjaman 
            SET tanggal_dikembalikan = '$tanggal_hari_ini',
                status = 'Dikembalikan',
                denda = '$denda'
            WHERE id_peminjaman = $id_peminjaman
        ");

        // Tambah stok buku
        mysqli_query($conn, "
            UPDATE buku SET stok = stok + 1 
            WHERE id_buku = {$data['id_buku']}
        ");

        echo "<script>alert('Buku \"{$data['judul']}\" berhasil dikembalikan!'); window.location='peminjaman.php';</script>";
        exit;
    }
}

// Ambil data peminjaman
$peminjaman = mysqli_query($conn, "
    SELECT p.*, b.judul 
    FROM peminjaman p
    LEFT JOIN buku b ON p.id_buku = b.id_buku
    WHERE p.id_user = '$id_user'
    ORDER BY p.id_peminjaman DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Peminjaman Buku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="index.php">Perpustakaan</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <li class="nav-item">
          <a class="nav-link" href="index.php">Beranda</a>
        </li>

        <li class="nav-item">
          <a class="nav-link active fw-bold" href="peminjaman.php">Peminjaman Buku</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-warning fw-bold" href="../logout.php">Keluar</a>
        </li>

      </ul>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container mt-4">
  <h3 class="mb-4 fw-semibold">Data Peminjaman Buku</h3>

  <table class="table table-bordered table-hover bg-white shadow-sm">
    <thead class="table-primary">
      <tr class="text-center">
        <th>Judul Buku</th>
        <th>Tanggal Pinjam</th>
        <th>Tanggal Kembali</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>

    <tbody>
      <?php if (mysqli_num_rows($peminjaman) == 0): ?>
        <tr>
          <td colspan="5" class="text-center text-muted py-4">Belum ada peminjaman buku.</td>
        </tr>
      <?php endif; ?>

      <?php while ($row = mysqli_fetch_assoc($peminjaman)): ?>
      <tr class="align-middle text-center">

        <td><?= htmlspecialchars($row['judul']); ?></td>

        <td><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>

        <td><?= htmlspecialchars($row['tanggal_kembali']); ?></td>

        <td>
          <?php if ($row['status'] == "Dipinjam") { ?>
            <span class="badge bg-warning text-dark">Dipinjam</span>
          <?php } else { ?>
            <span class="badge bg-success">Dikembalikan</span>
          <?php } ?>
        </td>

        <td>
          <?php if ($row['status'] == "Dipinjam") { ?>
            <form method="POST" style="display:inline;" 
                  onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?')">
                <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman']; ?>">
                <button type="submit" name="kembalikan" class="btn btn-sm btn-danger">
                    Kembalikan
                </button>
            </form>
          <?php } else { ?>
            <span class="text-muted">-</span>
          <?php } ?>
        </td>

      </tr>
      <?php endwhile; ?>
    </tbody>

  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
