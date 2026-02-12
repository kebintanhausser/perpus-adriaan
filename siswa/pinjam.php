<?php
session_start();
include '../incld/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    die("Anda harus login terlebih dahulu.");
}

$id_user = $_SESSION['id_user'];

// Pastikan ada id buku
if (!isset($_GET['id'])) {
    die("ID Buku tidak ditemukan.");
}

$id_buku = (int)$_GET['id'];

// Ambil data buku
$buku = mysqli_query($conn, "SELECT * FROM buku WHERE id_buku = $id_buku");
$dataBuku = mysqli_fetch_assoc($buku);

if (!$dataBuku) {
    die("Buku tidak ditemukan.");
}

$tanggal_pinjam = date("Y-m-d"); 
// Default 7 hari masa pinjam (bisa disesuaikan)
$tanggal_kembali = date("Y-m-d", strtotime("+7 days"));

// Proses submit
if (isset($_POST['pinjam'])) {

    // Cek stok
    if ($dataBuku['stok'] <= 0) {
        echo "<script>alert('Stok buku habis, tidak bisa dipinjam!'); window.location='index.php';</script>";
        exit;
    }

    // Simpan peminjaman
    $query = "
        INSERT INTO peminjaman 
        (id_user, id_buku, tanggal_pinjam, tanggal_kembali, status, denda) 
        VALUES 
        ('$id_user', '$id_buku', '$tanggal_pinjam', '$tanggal_kembali', 'Dipinjam', 0)
    ";
    mysqli_query($conn, $query);

    // Kurangi stok
    mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id_buku = $id_buku");

    echo "<script>alert('Peminjaman berhasil!'); window.location='index.php';</script>";
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pinjam Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h3 class="mb-4">📘 Pinjam Buku</h3>

        <h4><?= htmlspecialchars($dataBuku['judul']); ?></h4>

        <p>Penulis: <strong><?= htmlspecialchars($dataBuku['penulis']); ?></strong></p>
        <p>Stok tersedia: <strong><?= $dataBuku['stok']; ?></strong></p>
        <p>Tanggal Pinjam: <strong><?= $tanggal_pinjam; ?></strong></p>
        <p>Tanggal Kembali: <strong><?= $tanggal_kembali; ?></strong></p>

        <?php if ($dataBuku['stok'] <= 0): ?>
            <div class="alert alert-danger mt-3">Stok habis! Tidak bisa dipinjam.</div>
        <?php else: ?>

        <form method="POST">
            <button type="submit" name="pinjam" class="btn btn-primary w-100 mt-3">
                Pinjam Sekarang
            </button>
        </form>

        <?php endif; ?>
    </div>
</div>

</body>
</html>
