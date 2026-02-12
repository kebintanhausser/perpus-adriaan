<?php
include '../incld/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perpustakaan Sekolah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid mt-5">
 <h1 class="d-flex justify-content-start">Kategori Buku</h1>
</div>

<div class="container">
  <div class="row justify-content-center gap-2">
    <?php
    // Ambil semua kategori dari database
    $kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY kategori_buku ASC");
    while ($row = mysqli_fetch_assoc($kategori)):
    ?>
      <div class="col-lg-1 ms-2">
        <div class="card" style="width: 110px; height: 180px; border-radius: 150px;">
          <div class="card-body d-flex flex-column justify-content-between">
            <img src="../uploads/<?= htmlspecialchars($row['foto'] ?: 'default.jpg'); ?>" class="mt-4"> 
             <p style="font-size: 12px;" class="text-center"><?= htmlspecialchars($row['kategori_buku']); ?></p>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<!-- Buku -->
<div class="container">
  <h1>Buku</h1>
</div>

<div class="container mt-4">
  <div class="row g-5">
    <?php
    // Ambil semua buku dan kategori terkait
    $buku = mysqli_query($conn, "
      SELECT b.*, k.kategori_buku 
      FROM buku b 
      LEFT JOIN kategori k ON b.id_kategori = k.id_kategori 
      ORDER BY b.id_buku DESC
    ");
    while ($row = mysqli_fetch_assoc($buku)):
    ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card bg-primary text-white mb-5" style="border-radius: 20px;">
          <div class="card-body d-flex flex-column justify-content-between">
            <img src="../uploads/<?= htmlspecialchars($row['cover'] ?: 'default.jpg'); ?>" 
                 alt="<?= htmlspecialchars($row['judul']); ?>" 
                 class="img-fluid rounded-5 mt-3 m-auto" 
                 style="width: auto; height: auto; object-fit: cover;">
            <h2 class="card-title fs-5 mt-3"><?= htmlspecialchars($row['judul']); ?></h2>
            <h2 class="card-title fs-6 mt-1">Stok: <?= (int)$row['stok']; ?></h2>
            <button class="btn btn-light text-primary w-100 mt-3 fw-semibold mb-2">
              Pinjam Buku
            </button>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

</body>
</html>
