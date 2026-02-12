<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../incld/koneksi.php';

// --- Tambah Buku ---
if (isset($_POST['tambah'])) {
  $judul     = mysqli_real_escape_string($conn, $_POST['judul']);
  $penulis   = mysqli_real_escape_string($conn, $_POST['penulis']);
  $penerbit  = mysqli_real_escape_string($conn, $_POST['penerbit']);
  $tahun     = mysqli_real_escape_string($conn, $_POST['tahun']);
  $stok      = (int)$_POST['stok'];
  $id_kategori = (int)$_POST['id_kategori'];

  // Upload cover
  $cover = null;
  if (!empty($_FILES['cover']['name'])) {
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    $filename = time() . "_" . basename($_FILES["cover"]["name"]);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
      $cover = $filename;
    }
  }

  $query = "INSERT INTO buku (id_kategori, judul, penulis, penerbit, tahun, stok, cover)
            VALUES ('$id_kategori', '$judul', '$penulis', '$penerbit', '$tahun', '$stok', '$cover')";
  mysqli_query($conn, $query);
  header("Location: buku.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title> Data Buku - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f7f9fc; }
    .card { border-radius: 15px; }
    .upload-box {
      border: 2px dashed #ccc;
      border-radius: 15px;
      height: 250px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      cursor: pointer;
      transition: .3s;
    }
    .upload-box:hover {
      border-color: #28a745;
      background: #f8fff8;
    }
    .upload-box img {
      max-height: 180px;
      object-fit: cover;
      border-radius: 10px;
    }
  </style>
</head>
<body>
<?
include '../incld/navbar.php';
?>

<div class="container mt-4">
  <div class="card shadow p-4">
    <h3 class="mb-4 text-center">📘 Manajemen Data Buku</h3>

    <form method="POST" enctype="multipart/form-data">
      <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Upload Cover Buku</label>
          <div class="upload-box" onclick="document.getElementById('cover').click()">
            <p class="text-muted mb-0">Klik untuk pilih cover</p>
            <img id="preview" src="#" alt="Preview" style="display:none;">
          </div>
          <input type="file" name="cover" id="cover" accept="image/*" class="form-control mt-2" style="display:none;" onchange="previewImage(event)">
        </div>


        

        <!-- Kolom Kanan -->
         <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label fw-semibold">Judul Buku</label>
            <input type="text" name="judul" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Penulis</label>
            <input type="text" name="penulis" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Penerbit</label>
            <input type="text" name="penerbit" class="form-control" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Tahun</label>
              <input type="number" name="tahun" class="form-control" placeholder="2024" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Stok</label>
              <input type="number" name="stok" class="form-control" placeholder="0" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Kategori</label>
            <select name="id_kategori" class="form-select" required>
              <option value="">-- Pilih Kategori --</option>
              <?php
              $kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY kategori_buku ASC");
              while ($row = mysqli_fetch_assoc($kategori)) {
                  echo "<option value='{$row['id_kategori']}'>{$row['kategori_buku']}</option>";
              }
              ?>
            </select>
          </div>
          <button type="submit" name="tambah" class="btn btn-warning w-100 mt-3">💾 Simpan Buku</button>
        </div>
        
      </div>
    </form>

    <hr class="my-4">

    <!-- Tabel Buku -->
    <table class="table table-bordered table-hover text-center align-middle">
      <thead class="table table-dark table-striped">
        <tr class="table-danger">
          <th>#</th>
          <th>Judul</th>
          <th>Penulis</th>
          <th>Penerbit</th>
          <th>Tahun</th>
          <th>Stok</th>
          <th>Kategori</th>
          <th>Cover</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-warning">
        <?php
        $no = 1;
        $result = mysqli_query($conn, "SELECT b.*, k.kategori_buku 
                                       FROM buku b 
                                       LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
                                       ORDER BY b.id_buku DESC");
        while ($row = mysqli_fetch_assoc($result)):
        ?>
        <tr>
          <td><?= $no++; ?></td>
          <td><?= htmlspecialchars($row['judul']); ?></td>
          <td><?= htmlspecialchars($row['penulis']); ?></td>
          <td><?= htmlspecialchars($row['penerbit']); ?></td>
          <td><?= $row['tahun']; ?></td>
          <td><?= $row['stok']; ?></td>
          <td><?= $row['kategori_buku']; ?></td>
          <td>
            <?php if ($row['cover']): ?>
              <img src="../uploads/<?= $row['cover']; ?>" alt="cover buku" width="70" class="rounded">
            <?php else: ?>
              <span class="text-muted">-</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="edit_buku.php?id=<?= $row['id_buku']; ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="hapus_buku.php?id=<?= $row['id_buku']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus buku ini?')">Hapus</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function previewImage(event) {
  const reader = new FileReader();
  reader.onload = function(){
    const output = document.getElementById('preview');
    output.src = reader.result;
    output.style.display = 'block';
  };
  reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
