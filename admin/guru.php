<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../incld/koneksi.php';

// --- Tambah Buku ---
if (isset($_POST['tambah'])) {
  $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
  $alamat   = mysqli_real_escape_string($conn, $_POST['alamat']);
  $jabatan   = mysqli_real_escape_string($conn, $_POST['jabatan']);
  $nohp  = mysqli_real_escape_string($conn, $_POST['nohp']);
  // Upload foto
  $foto = null;
  if (!empty($_FILES['foto']['name'])) {
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    $filename = time() . "_" . basename($_FILES["foto"]["name"]);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
      $foto = $filename;
    }
  }
 
  $query = "INSERT INTO guru (nama, alamat, jabatan, nohp, foto)
            VALUES ('$nama', '$alamat', '$jabatan','$nohp', '$foto')";
  mysqli_query($conn, $query);
  header("Location: guru.php");
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
      object-fit: foto;
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
    <h3 class="mb-4 text-center">📘 Manajemen Data Guru</h3>

    <form method="POST" enctype="multipart/form-data">
      <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Alamat</label>
            <input type="text" name="alamat" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Jabatan</label>
            <input type="text" name="jabatan" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Whatasapp</label>
            <input type="text" name="nohp" class="form-control" required>
          </div>
          <button type="submit" name="tambah" class="btn btn-success w-100 mt-3">💾 Simpan Guru</button>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Upload Foto guru</label>
          <div class="upload-box" onclick="document.getElementById('foto').click()">
            <p class="text-muted mb-0">Klik untuk pilih foto</p>
            <img id="preview" src="#" alt="Preview" style="display:none;">
          </div>
          <input type="file" name="foto" id="foto" accept="image/*" class="form-control mt-2" style="display:none;" onchange="previewImage(event)">
        </div>
      </div>
    </form>

    <hr class="my-4">

    <!-- Tabel Buku -->
    <table class="table table-bordered table-hover text-center align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Alamat</th>
          <th>No Whatsapp</th>
         
          <th>foto</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        
         <?php
        $no = 1;
        $result = mysqli_query($conn, "SELECT * FROM guru ORDER BY id_guru DESC");
        while ($row = mysqli_fetch_assoc($result)):
        ?>
        <tr>
          <td><?= $no++; ?></td>
          <td><?= htmlspecialchars($row['nama']); ?></td>
          <td><?= htmlspecialchars($row['alamat']); ?></td>
          <td><?= htmlspecialchars($row['jabatan']); ?></td>
          <td><?= htmlspecialchars($row['nohp']); ?></td>
          <td>
            <?php if ($row['foto']): ?>
              <img src="../uploads/<?= $row['foto']; ?>" alt="" width="80" class="rounded">
            <?php else: ?>
              <span class="text-muted">Tidak ada</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="edit_guru.php?id=<?= $row['id_guru']; ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="hapus_guru.php?id=<?= $row['id_guru']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus buku ini?')">Hapus</a>
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
