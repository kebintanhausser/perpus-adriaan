<?php
session_start();

include '../incld/koneksi.php';

if (isset($_POST['login'])) {
    $username       = mysqli_real_escape_string($conn, $_POST['username']);
    $password       = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' LIMIT 1");
    $data = mysqli_fetch_assoc($query);

    if ($data && password_verify($password, $data['password'])) {

        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
        $_SESSION['role'] = $data['role'];

        if ($data['role'] == 'admin') {
            header('Location: ../admin/dashboard.php');
        } else {
            header('Location: ../siswa/index.php');
        }
        exit;
    } else {
        $error = "username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body,
        html {
            height: 100%;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ===== LEFT SIDE ===== */
        .login-wrapper {
            width: 100%;
            max-width: 380px;
        }

        .logo {
            width: 180px;
            margin-bottom: 25px;
        }

        .login-title {
            font-weight: 700;
            font-size: 28px;
        }

        .login-sub {
            font-size: 14px;
            color: #777;
            margin-bottom: 25px;
        }

        .google-btn {
            border: 1px solid #ddd;
            border-radius: 50px;
            padding: 10px;
            background: white;
            font-weight: 500;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            font-size: 13px;
            color: #888;
            position: relative;
        }

        .divider span {
            background: #fff;
            /* same as left panel background */
            padding: 0 12px;
            position: relative;
            z-index: 1;
        }

        .divider::before,
        .divider::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 50%;
            height: 1px;
            background: #ddd;
            z-index: 0;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }


        .form-control {
            border-radius: 50px;
            padding: 12px 18px;
        }

        .form-check-label {
            font-size: 14px;
        }

        .login-btn {
            background-color: #4f46e5;
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-weight: 600;
        }

        .login-btn:hover {
            opacity: 0.9;
        }

        .bottom-text {
            font-size: 14px;
            margin-top: 20px;
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

            <!-- LEFT SIDE LOGIN -->
            <div class="col-lg-6 d-flex justify-content-center align-items-center bg-white">
                <div class="login-wrapper">

                    <img src="https://smkpab1.wordpress.com/wp-content/uploads/2013/09/logo-pab-1.png?w=600" class="logo" alt="Logo">

                    <div class="login-title">Login</div>
                    <div class="login-sub">Membaca jendela dunia</div>

                    <button class="w-100 google-btn mb-3">
                        <img src="../assets/gambar/Google.png" />
                        Sign in with Google
                    </button>

                    <div class="divider">
                        <span>or sign in with Email</span>
                    </div>


                    <label>Email</label>
                    <input type="email" class="form-control mb-3" placeholder="OGTTDMITHICS@example.com">

                    <label>Password</label>
                    <input type="password" class="form-control mb-2">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox">
                            <label class="form-check-label">Remember me</label>
                        </div>
                        <a href="#" style="font-size: 14px;">Forget password?</a>
                    </div>

                    <button class="w-100 login-btn text-white">Login</button>

                    <div class="bottom-text text-center">
                        Not register yet? <a href="daftar.php">Create an Account!</a>
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