<?php
session_start();

include '../incld/koneksi.php';

if (isset($_POST['register'])) {
    $username       = mysqli_real_escape_string($conn, $_POST['username']);
    $password       = mysqli_real_escape_string($conn, $_POST['password']);
    $nama_lengkap   = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $nisn           = mysqli_real_escape_string($conn, $_POST['nisn']);
    $kelas          = mysqli_real_escape_string($conn, $_POST['kelas']);
    $jurusan        = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $wa             = mysqli_real_escape_string($conn, $_POST['wa']);


    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Username sudah terdaftar!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $role = 'siswa'; // default role

        $query = "INSERT INTO users (username,password,role,nisn,nama_lengkap,kelas,jurusan,wa) VALUES
    ('$username', '$hashed', '$role', '$nisn', '$nama_lengkap','$kelas', '$jurusan','$wa')";

        if (mysqli_query($conn, $query)) {
            header('Location: ../auth/masuk.php');
            exit;
        } else {
            $error = "Terjadi Kesalahan: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body,
        html {
            height: 100%;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ===== LEFT SIDE ===== */
        .form-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .logo {
            width: 180px;
            margin-bottom: 25px;
        }

        .title {
            font-weight: 700;
            font-size: 28px;
        }

        .subtitle {
            font-size: 14px;
            color: #777;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 50px;
            padding: 12px 18px;
        }

        .confirm-btn {
            background-color: #4f46e5;
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-weight: 600;
            color: white;
            margin-top: 20px;
        }

        .confirm-btn:hover {
            opacity: 0.9;
        }

        .form-wrapper {
            width: 100%;
            max-width: 420px;
            padding-bottom: 70px;
            /* space so button doesn't overlap */
        }

        .back-btn {
            position: absolute;
            bottom: 5%;
            left: 0%;
            width: 45px;
        }

        .back-btn img {
            width: 100%;
            height: auto;
            cursor: pointer;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .back-btn img:hover {
            transform: scale(1.1);
            opacity: 0.85;
        }



        /* ===== RIGHT SIDE (UNCHANGED) ===== */
        .right-side {
            background-color: #3521b5;
            background-image: url('../assets/gambar/Vector.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            overflow: hidden;
        }

        .floating-img {
            position: absolute;
            max-width: 180px;
            height: auto;
        }

        .img-X {
            top: 20%;
            left: 23%;
        }

        .img-Graph {
            top: 40%;
            left: 30%;
            max-width: 300px;
        }

        .img-Rewards {
            top: 20%;
            right: 15%;
            max-width: 220px;
        }

        .img-Messenger {
            bottom: 25%;
            right: 20%;
            max-width: 70px;
        }

        .img-IG {
            top: 8%;
            right: 25%;
            max-width: 70px;
        }

        .right-text {
            position: absolute;
            bottom: 10%;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: white;
        }

        .right-text h4 {
            font-weight: 700;
        }

        .right-text p {
            font-size: 14px;
            opacity: 0.85;
        }
    </style>
</head>

<body>
    <div class="container-fluid vh-100">
        <div class="row h-100">

            <!-- LEFT SIDE REGISTER -->
            <div class="col-lg-6 d-flex justify-content-center align-items-center bg-white">
                <div class="form-wrapper">

                    <img src="https://smkpab1.wordpress.com/wp-content/uploads/2013/09/logo-pab-1.png?w=600" class="logo" alt="Logo">

                    <div class="title">DAFTAR</div>
                    <div class="subtitle">Membaca jendela dunia</div>

                    <form method="POST" action="">

                        <div class="row g-3">
                            <div class="col-6">
                                <input type="text" name="username" class="form-control" placeholder="Username">
                            </div>
                            <div class="col-6">
                                <input type="text" name="nisn" class="form-control" placeholder="NISN">
                            </div>

                            <div class="col-6">
                                <input type="text" name="nama_lengkap" class="form-control" placeholder="Name">
                            </div>
                            <div class="col-6">
                                <input type="text" name="kelas" class="form-control" placeholder="Class">
                            </div>

                            <div class="col-6">
                                <input type="text" name="wa" class="form-control" placeholder="Whatsapp Number">
                            </div>
                            <div class="col-6">
                                <input type="text" name="jurusan" class="form-control" placeholder="Major">
                            </div>

                            <div class="col-12">
                                <input type="password" name="password" class="form-control" placeholder="Password">
                            </div>
                        </div>

                        <button type="submit" name="register" class="w-100 confirm-btn">
                            Confirm
                        </button>

                    </form>

                    <div class="form-wrapper position-relative">

                        <a href="masuk.php" class="back-btn">
                            <img src="../assets/gambar/Return.png" alt="Back">
                        </a>
                    </div>
                </div>
            </div>


            <!-- RIGHT SIDE -->
            <div class="col-lg-6 right-side d-none d-lg-block">

                <img src="../assets/gambar/X.png" class="floating-img img-X" alt="">
                <img src="../assets/gambar/Graph.png" class="floating-img img-Graph" alt="">
                <img src="../assets/gambar/Rewards.png" class="floating-img img-Rewards" alt="">
                <img src="../assets/gambar/Messenger.png" class="floating-img img-Messenger" alt="">
                <img src="../assets/gambar/IG.png" class="floating-img img-IG" alt="">

                <div class="right-text">
                    <h4>Turn your ideas <br>into reality</h4>
                    <p>Consistent quality user experience across <br>all platform and device</p>
                </div>

            </div>

        </div>
    </div>
</body>

</html>