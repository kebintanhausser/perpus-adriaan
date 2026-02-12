<?php
include '../incld/koneksi.php';

$id = $_GET['id'];


mysqli_query($conn, "DELETE FROM ortu WHERE id_ortu='$id'");
header("Location: ortu.php");

exit;
?>
