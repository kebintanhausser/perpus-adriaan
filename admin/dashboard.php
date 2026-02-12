<?php
session_start();
include '../incld/koneksi.php';
include '../incld/navbar.php';

// Hitung data statistik
$total_buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS jml FROM buku"))['jml'];
$total_kategori = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS jml FROM kategori"))['jml'];
$total_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS jml FROM users WHERE role='siswa'"))['jml'];
// $total_pinjam = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS jml FROM peminjaman"))['jml'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin - Perpustakaan Sekolah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card-stat {
      border: none;
      border-radius: 15px;
      transition: all 0.3s;
    }
    .card-stat:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .icon {
      font-size: 2rem;
      color: #fff;
      opacity: 0.85;
    }
    .bg-gradient-primary {
      background: linear-gradient(45deg, #007bff, #00bcd4);
    }
    .bg-gradient-success {
      background: linear-gradient(45deg, #28a745, #8bc34a);
    }
    .bg-gradient-warning {
      background: linear-gradient(45deg, #ffc107, #ff9800);
    }
    .bg-gradient-danger {
      background: linear-gradient(45deg, #dc3545, #ff5c93);
    }
  </style>
</head>
<body>

<div class="container my-4">
  <h3 class="fw-bold mb-4 text-center"> Dashboard Admin</h3>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card card-stat bg-gradient-primary text-white text-center p-4">
      
        <h4><?= $total_buku; ?></h4>
        <p>Total Buku</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-stat bg-gradient-success text-white text-center p-4">
       
        <h4><?= $total_kategori; ?></h4>
        <p>Kategori Buku</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-stat bg-gradient-warning text-white text-center p-4">
        
        <h4><?= $total_siswa; ?></h4>
        <p>Total Siswa</p>
      </div>
    </div>
    
    </div>
  </div>

  <hr class="my-5">

  <h5 class="fw-semibold mb-3"> Daftar Buku Terbaru</h5>
  <div class="card shadow-sm p-3">
    <div class="table-responsive">
      <table class="table table-striped align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>#</th>
            <th>Judul Buku</th>
            <th>Penulis</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Foto</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          $query = mysqli_query($conn, "SELECT b.*, k.kategori_buku 
                                       FROM buku b
                                       LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
                                       ORDER BY b.id_buku DESC LIMIT 5");
          while ($row = mysqli_fetch_assoc($query)):
          ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['judul']); ?></td>
            <td><?= htmlspecialchars($row['penulis']); ?></td>
            <td><?= htmlspecialchars($row['kategori_buku']); ?></td>
            <td><?= $row['stok']; ?></td>
            <td>
              <?php if ($row['cover']): ?>
              <img src="../uploads/<?= $row['cover']; ?>" alt="cover buku" width="70" class="rounded">
            <?php else: ?>
              <span class="text-muted">-</span>
            <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
