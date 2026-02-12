<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
  header("Location: ../auth/masuk.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Perpustakaan Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f4f4f4;
    }

    /* HEADER */
    .navbar-custom {
      background: linear-gradient(to right, #5f2eea, #7a5cff);
    }

    .navbar-custom .navbar-brand,
    .navbar-custom .nav-link {
      color: white !important;
      font-weight: 600;
    }

    /* CATEGORY CARD */
    .category-card {
      background: linear-gradient(to bottom, #5f2eea, #4a2bbf);
      border-radius: 20px;
      padding: 20px;
      text-align: center;
      color: white;
      transition: 0.3s;
    }

    .category-card:hover {
      transform: translateY(-5px);
    }

    .category-card img {
      width: 50px;
      margin-bottom: 10px;
    }

    /* BOOK CARD */
    .book-card {
      background: linear-gradient(to bottom, #5f2eea, #4a2bbf);
      border-radius: 20px;
      padding: 15px;
      color: white;
    }

    .book-card img {
      width: 100%;
      height: 140px;
      object-fit: cover;
      border-radius: 15px;
    }

    .book-title {
      font-size: 14px;
      font-weight: bold;
      margin-top: 10px;
    }

    .price {
      font-size: 13px;
    }

    .btn-status {
      border-radius: 20px;
      font-size: 13px;
      padding: 5px 15px;
    }

    .btn-pinjam {
      background-color: #7a5cff;
      color: white;
    }

    .btn-dipinjam {
      background-color: white;
      color: #5f2eea;
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-custom px-4">
    <div class="container-fluid">
      <a class="navbar-brand">PERPUSTAKAAN SMK PAB 2 HELVETIA</a>
      <div>
        <a href="#" class="nav-link d-inline me-3">CEK PINJAM</a>
        <a href="../auth/logout.php" class="nav-link d-inline">KELUAR</a>
      </div>
    </div>
  </nav>

  <div class="container mt-4">

    <!-- KATEGORI -->
    <h4 class="fw-bold">Kategori Buku</h4>

    <div class="row g-3 mt-2">
      <?php
      $kategori = ["Novel", "Romansa", "Horror", "Sejarah", "Komedi", "Biografi", "Fantasi"];
      foreach ($kategori as $k) {
      ?>
        <div class="col-md-2 col-6">
          <div class="category-card">
            <img src="../assets/<?= strtolower($k); ?>.png">

            <div><?= $k ?></div>
          </div>
        </div>
      <?php } ?>
    </div>

    <!-- STOCK -->
    <h4 class="fw-bold mt-5">Stock Buku</h4>

    <div class="row g-4 mt-2">

      <?php
      // Dummy data (replace with database later)
      for ($i = 1; $i <= 7; $i++) {
      ?>
        <div class="col-md-3 col-6">
          <div class="book-card">
            <img src="../assets/book.jpg" alt="">
            <div class="book-title">Judul Buku <?= $i ?></div>
            <div class="price">Rp. 50.000</div>
            <div class="text-end mt-2">
              <?php if ($i % 2 == 0) { ?>
                <button class="btn btn-status btn-dipinjam">Sedang Dipinjam</button>
              <?php } else { ?>
                <button class="btn btn-status btn-pinjam">Pinjam</button>
              <?php } ?>
            </div>
          </div>
        </div>
      <?php } ?>

    </div>

  </div>

</body>

</html>