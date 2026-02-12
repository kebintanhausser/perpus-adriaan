<?php
include '../incld/koneksi.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM buku WHERE id_buku='$id'");
header("Location: buku.php");

mysqli_query($conn, "DELETE FROM guru WHERE id_guru='$id'");
header("Location: guru.php");

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori='$id'");
header("Location: kategori.php");


exit;
?>
