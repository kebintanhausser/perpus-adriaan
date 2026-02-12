<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../incld/koneksi.php';

// --- Ambil data buku berdasarkan ID ---
if (!isset($_GET['id'])) {
  header("Location: buku.php");
  exit;
}

$id_buku = (int)$_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM buku WHERE id_buku = $id_buku");
$data = mysqli_fetch_assoc($query);

if (!$data) {
  echo "<script>alert('Data buku tidak ditemukan!');window.location='buku.php';</script>";
  exit;
}

// --- Update Buku ---
if (isset($_POST['update'])) {
  $judul     = mysqli_real_escape_string($conn, $_POST['judul']);
  $penulis   = mysqli_real_escape_string($conn, $_POST['penulis']);
  $penerbit  = mysqli_real_escape_string($conn, $_POST['penerbit']);
  $tahun     = mysqli_real_escape_string($conn, $_POST['tahun']);
  $stok      = (int)$_POST['stok'];
  $id_kategori = (int)$_POST['id_kategori'];
  $cover_lama = $data['cover'];

  // Upload cover baru (jika ada)
  $cover_baru = $cover_lama;
  if (!empty($_FILES['cover']['name'])) {
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    $filename = time() . "_" . basename($_FILES["cover"]["name"]);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
      // Hapus file lama jika ada
      if ($cover_lama && file_exists("../uploads/" . $cover_lama)) {
        unlink("../uploads/" . $cover_lama);
      }
      $cover_baru = $filename;
    }
  }

  // Update ke database
  $update = mysqli_query($conn, "UPDATE buku SET 
                                  id_kategori = '$id_kategori',
                                  judul = '$judul',
                                  penulis = '$penulis',
                                  penerbit = '$penerbit',
                                  tahun = '$tahun',
                                  stok = '$stok',
                                  cover = '$cover_baru'
                                WHERE id_buku = '$id_buku'");

  if ($update) {
    header("Location: buku.php");
    exit;
  } else {
    echo "<div class='alert alert-danger'>Gagal mengupdate data buku!</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>✏️ Edit Buku</title>
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

<div class="container mt-4">
  <div class="card shadow p-4">
    <h3 class="mb-4 text-center">✏️ Edit Data Buku</h3>

    <form method="POST" enctype="multipart/form-data">
      <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label fw-semibold">Judul Buku</label>
            <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']); ?>" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Penulis</label>
            <input type="text" name="penulis" value="<?= htmlspecialchars($data['penulis']); ?>" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Penerbit</label>
            <input type="text" name="penerbit" value="<?= htmlspecialchars($data['penerbit']); ?>" class="form-control" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Tahun</label>
              <input type="number" name="tahun" value="<?= $data['tahun']; ?>" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-semibold">Stok</label>
              <input type="number" name="stok" value="<?= $data['stok']; ?>" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Kategori</label>
            <select name="id_kategori" class="form-select" required>
              <option value="">-- Pilih Kategori --</option>
              <?php
              $kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY kategori_buku ASC");
              while ($row = mysqli_fetch_assoc($kategori)) {
                $selected = ($row['id_kategori'] == $data['id_kategori']) ? 'selected' : '';
                echo "<option value='{$row['id_kategori']}' $selected>{$row['kategori_buku']}</option>";
              }
              ?>
            </select>
          </div>
          <button type="submit" name="update" class="btn btn-primary w-100 mt-3">💾 Update Buku</button>
          <a href="buku.php" class="btn btn-secondary w-100 mt-2">⬅️ Kembali</a>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Cover Buku</label>
          <div class="upload-box" onclick="document.getElementById('cover').click()">
            <?php if ($data['cover']): ?>
              <img id="preview" src="../uploads/<?= $data['cover']; ?>" alt="Cover Buku">
            <?php else: ?>
              <p class="text-muted mb-0">Klik untuk pilih cover</p>
              <img id="preview" src="#" alt="Preview" style="display:none;">
            <?php endif; ?>
          </div>
          <input type="file" name="cover" id="cover" accept="image/*" class="form-control mt-2" style="display:none;" onchange="previewImage(event)">
        </div>
      </div>
    </form>
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
