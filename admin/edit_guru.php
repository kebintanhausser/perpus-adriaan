<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../incld/koneksi.php';

// --- Ambil data ortu berdasarkan ID ---
if (!isset($_GET['id'])) {
  header("Location: guru.php");
  exit;
}

$id_ortu = (int)$_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM guru WHERE id_guru = $id_ortu");
$data = mysqli_fetch_assoc($query);

if (!$data) {
  echo "<script>alert('Data orang tua tidak ditemukan!');window.location='ortu.php';</script>";
  exit;
}

// --- Update ORTU ---
if (isset($_POST['update'])) {

  $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
  $alamat   = mysqli_real_escape_string($conn, $_POST['alamat']);
  $nohp  = mysqli_real_escape_string($conn, $_POST['nohp']);

  // Foto lama
  $foto_lama = $data['foto'];
  $foto_baru = $foto_lama;

  // Jika upload foto baru
  if (!empty($_FILES['foto']['name'])) {
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    
    $filename = time() . "_" . basename($_FILES["foto"]["name"]);
    $target_file = $target_dir . $filename;

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
      $foto_baru = $filename;
    }
  }

  // Update ke database
 

   $update = mysqli_query($conn, "
      UPDATE ortu SET 
        nama   = '$nama',
        alamat = '$alamat',
        nohp   = '$nohp',
        foto   = '$foto_baru'
      WHERE id_ortu = '$id_ortu'
  ");

  if ($update) {
    header("Location: guru.php");
    exit;
  } else {
    echo "<div class='alert alert-danger'>Gagal mengupdate data orang tua!</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>✏️ Edit Data Guru</title>
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
<?php include '../incld/navbar.php'; ?>

<div class="container mt-4">
  <div class="card shadow p-4">
    <h3 class="mb-4 text-center">✏️ Edit Data Guru</h3>

    <form method="POST" enctype="multipart/form-data">
      <div class="row">
        
        <!-- Kolom Kiri -->
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Guru</label>
            <input type="text" name="nama" class="form-control"
                   value="<?= htmlspecialchars($data['nama']); ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Alamat</label>
            <input type="text" name="alamat" class="form-control"
                   value="<?= htmlspecialchars($data['alamat']); ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">No Whatsapp</label>
            <input type="text" name="nohp" class="form-control"
                   value="<?= htmlspecialchars($data['nohp']); ?>" required>
          </div>

          <button type="submit" name="update" class="btn btn-success w-100 mt-3">💾 Simpan Perubahan</button>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Foto Guru</label>
          <div class="upload-box" onclick="document.getElementById('foto').click()">
            <?php if ($data['foto']): ?>
              <img id="preview" src="../uploads/<?= $data['foto']; ?>" alt="Foto Orang Tua">
            <?php else: ?>
              <p class="text-muted mb-0">Klik untuk pilih foto</p>
              <img id="preview" src="#" alt="Preview" style="display:none;">
            <?php endif; ?>
          </div>

          <input type="file" name="foto" id="foto" accept="image/*"
                 class="form-control mt-2" style="display:none;" onchange="previewImage(event)">
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
