<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/masuk.php");
    exit;
}
if ($_SESSION['role'] != 'siswa') {
    header("Location: ../admin/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card p-4 shadow-sm">
    <h3>Halo, <span class="text-primary"><?= $_SESSION['nama_lengkap']; ?></span></h3>
    <p>Anda login sebagai <b>Siswa</b></p>
    <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
  </div>
</div>

</body>
</html>
