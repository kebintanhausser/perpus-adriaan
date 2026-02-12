<?php
include '../incld/koneksi.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM buku WHERE id_buku='$id'");
header("Location: buku.php");

exit;
?>
