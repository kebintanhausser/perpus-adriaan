<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../incld/koneksi.php';

// --- Tambah Data Siswa ---
if (isset($_POST['tambah'])) {

    $username      = mysqli_real_escape_string($conn, $_POST['username']);
    $password      = mysqli_real_escape_string($conn, $_POST['password']);
    $nama_lengkap  = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $nisn          = mysqli_real_escape_string($conn, $_POST['nisn']);
    $kelas         = mysqli_real_escape_string($conn, $_POST['kelas']);
    $jurusan       = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $wa            = mysqli_real_escape_string($conn, $_POST['wa']);

    // cek username
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Username sudah terdaftar!";
    } else {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

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

        // INSERT benar
        $query = "INSERT INTO users 
        (username, password, role, nama_lengkap, nisn, kelas, jurusan, wa, foto)
        VALUES 
        ('$username', '$hashed', 'siswa', '$nama_lengkap', '$nisn', '$kelas', '$jurusan', '$wa', '$foto')";

        mysqli_query($conn, $query);

        header("Location: siswa.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Data Siswa</title>
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

<?php
include '../incld/navbar.php';
?>

<div class="container mt-4">
  <div class="card shadow p-4">

    <h3 class="mb-4 text-center">👨‍🎓 Manajemen Data Siswa</h3>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="row">

        <!-- Kolom Kiri -->
        <div class="col-md-6">

          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">NISN</label>
            <input type="text" name="nisn" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Kelas</label>
            <input type="text" name="kelas" class="form-control" placeholder="Contoh: XII RPL 1" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Jurusan</label>
            <input type="text" name="jurusan" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">WhatsApp</label>
            <input type="text" name="wa" class="form-control" placeholder="08xxx" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>

          <button type="submit" name="tambah" class="btn btn-success w-100 mt-3">
            💾 Simpan Data Siswa
          </button>

        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Upload Foto Siswa</label>
          <div class="upload-box" onclick="document.getElementById('foto').click()">
            <p class="text-muted mb-0">Klik untuk pilih foto</p>
            <img id="preview" src="#" alt="Preview" style="display:none;">
          </div>
          <input type="file" name="foto" id="foto" accept="image/*" class="form-control mt-2" style="display:none;" onchange="previewImage(event)">
        </div>

      </div>
    </form>

    <hr class="my-4">

    <!-- Tabel Data Siswa -->
    <table class="table table-bordered table-hover text-center align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Foto</th>
          <th>Nama</th>
          <th>NISN</th>
          <th>Kelas</th>
          <th>Jurusan</th>
          <th>WA</th>
          <th>Username</th>
          <th>Aksi</th>
        </tr>
      </thead>

      <tbody>
        <?php
        $no = 1;
        $result = mysqli_query($conn, "SELECT * FROM users WHERE role='siswa' ORDER BY id_user DESC");
        while ($row = mysqli_fetch_assoc($result)):
        ?>
        <tr>
          <td><?= $no++; ?></td>

          <td>
            <?php if ($row['foto']): ?>
              <img src="../uploads/<?= $row['foto']; ?>" width="60" class="rounded">
            <?php else: ?>
              <span class="text-muted">-</span>
            <?php endif; ?>
          </td>

          <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
          <td><?= htmlspecialchars($row['nisn']); ?></td>
          <td><?= htmlspecialchars($row['kelas']); ?></td>
          <td><?= htmlspecialchars($row['jurusan']); ?></td>
          <td><?= htmlspecialchars($row['wa']); ?></td>
          <td><?= htmlspecialchars($row['username']); ?></td>

          <td>
            <a href="edit_siswa.php?id=<?= $row['id_user']; ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="hapus_siswa.php?id=<?= $row['id_user']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
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
