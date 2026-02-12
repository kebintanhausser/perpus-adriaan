<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../incld/koneksi.php';

if (!isset($_GET['id'])) {
    die("ID siswa tidak ditemukan.");
}

$id = $_GET['id'];

// Ambil data siswa
$q = mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id' AND role='siswa'");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    die("Data siswa tidak ditemukan.");
}

// Jika form disubmit
if (isset($_POST['update'])) {

    $nama_lengkap  = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $nisn          = mysqli_real_escape_string($conn, $_POST['nisn']);
    $kelas         = mysqli_real_escape_string($conn, $_POST['kelas']);
    $jurusan       = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $wa            = mysqli_real_escape_string($conn, $_POST['wa']);
    $username      = mysqli_real_escape_string($conn, $_POST['username']);

    // Update password jika diisi
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $passQuery = ", password='$password'";
    } else {
        $passQuery = "";
    }

    // Upload Foto
    $foto = $data['foto'];

    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $filename    = time() . "_" . basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {

            // Hapus foto lama
            if (!empty($foto) && file_exists("../uploads/" . $foto)) {
                unlink("../uploads/" . $foto);
            }

            $foto = $filename;
        }
    }

    // Update ke database
    $query = "
        UPDATE users SET 
            nama_lengkap='$nama_lengkap',
            nisn='$nisn',
            kelas='$kelas',
            jurusan='$jurusan',
            wa='$wa',
            username='$username',
            foto='$foto'
            $passQuery
        WHERE id_user='$id'
    ";

    mysqli_query($conn, $query);

    header("Location: siswa.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Data Siswa</title>
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
    .upload-box img { 
      max-height: 180px;
      border-radius: 10px;
    }
  </style>
</head>
<body>

<?php include '../incld/navbar.php'; ?>

<div class="container mt-4">
  <div class="card shadow p-4">

    <h3 class="mb-4 text-center">✏️ Edit Data Siswa</h3>

    <form method="POST" enctype="multipart/form-data">
      <div class="row">

        <!-- Kolom kiri -->
        <div class="col-md-6">

          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="<?= $data['nama_lengkap']; ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">NISN</label>
            <input type="text" name="nisn" class="form-control" value="<?= $data['nisn']; ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Kelas</label>
            <input type="text" name="kelas" class="form-control" value="<?= $data['kelas']; ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Jurusan</label>
            <input type="text" name="jurusan" class="form-control" value="<?= $data['jurusan']; ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">WhatsApp</label>
            <input type="text" name="wa" class="form-control" value="<?= $data['wa']; ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <input type="text" name="username" class="form-control" value="<?= $data['username']; ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Password (kosongkan jika tidak diganti)</label>
            <input type="password" name="password" class="form-control">
          </div>

          <button type="submit" name="update" class="btn btn-primary w-100 mt-3">
            ✔ Simpan Perubahan
          </button>

        </div>

        <!-- Kolom kanan -->
        <div class="col-md-6">
            <label class="form-label fw-semibold">Foto Siswa</label>

            <div class="upload-box" onclick="document.getElementById('foto').click()">
                <img id="preview" src="../uploads/<?= $data['foto']; ?>" alt="Foto Siswa">
            </div>

            <input type="file" name="foto" id="foto" accept="image/*" class="form-control mt-2" style="display:none;" onchange="previewImage(event)">
        </div>

      </div>
    </form>

  </div>
</div>

<script>
function previewImage(event) {
  const output = document.getElementById('preview');
  output.src = URL.createObjectURL(event.target.files[0]);
}
</script>

</body>
</html>
