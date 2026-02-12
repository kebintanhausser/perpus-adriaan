<?php
session_start();
include '../incld/koneksi.php';


if (isset($_POST['tambah'])) {   
  $kategori_buku = mysqli_real_escape_string($conn, $_POST['kategori_buku']);
  $foto = null;
  if (!empty($_FILES['foto']['name'])) {
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir);
    $filename = time() . "_" . basename($_FILES["foto"]["name"]);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
      $foto = $filename;
    }
  }
  $query = "INSERT INTO kategori (kategori_buku, foto) VALUES ('$kategori_buku', '$foto')";
  mysqli_query($conn, $query);
  header("Location: kategori.php");
  exit;
}



?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Kategori Buku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card {
      border-radius: 15px;
    }
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
<?
include '../incld/navbar.php';
?>
<body class="bg-light">

<div class="container mt-4">
  <div class="card shadow p-4">
    <h3 class="mb-4">📚 Data Kategori Buku</h3>

    <!-- Form Tambah Kategori -->
    <form method="POST" enctype="multipart/form-data">
      <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Kategori Buku</label>
            <input type="text" name="kategori_buku" class="form-control" placeholder="Contoh: Novel, Komik, Pelajaran..." required>
          </div>
          <button type="submit" name="tambah" class="btn btn-success w-100 mt-3">💾 Simpan</button>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Upload Foto</label>
          <div class="upload-box" id="uploadBox" onclick="document.getElementById('foto').click()">
            <p class="text-muted">Klik untuk pilih foto</p>
            <img id="preview" src="#" alt="" style="display:none;">
          </div>
          <input type="file" name="foto" id="foto" class="form-control mt-2" accept="image/*" style="display:none;" onchange="previewImage(event)">
        </div>
      </div>
    </form>

    <hr class="my-4">

    <!-- Tabel Kategori -->
    <table class="table table-bordered table-striped text-center align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Kategori Buku</th>
          <th>Foto</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        $result = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id_kategori DESC");
        while ($row = mysqli_fetch_assoc($result)):
        ?>
        <tr>
          <td><?= $no++; ?></td>
          <td><?= htmlspecialchars($row['kategori_buku']); ?></td>
          <td>
            <?php if ($row['foto']): ?>
              <img src="../uploads/<?= $row['foto']; ?>" alt="" width="80" class="rounded">
            <?php else: ?>
              <span class="text-muted">Tidak ada</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="edit_kategori.php?id=<?= $row['id_kategori']; ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="hapus_kategori.php?id=<?= $row['id_kategori']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus kategori ini?')">Hapus</a>
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
