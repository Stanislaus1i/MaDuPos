<?php
session_start();
include "dist/function/koneksi.php";

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM petugas WHERE username='$username' LIMIT 1";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        if (password_verify($password, $data['password'])) {

            $_SESSION['id_petugas'] = $data['id_petugas'];
            $_SESSION['nama_petugas'] = $data['nama'];

            $success = "Login berhasil!";
            echo "<script>
                setTimeout(function(){
                    window.location.href='pages/dashboard.php';
                }, 1500);
            </script>";

        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | MaDuPos</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        body {
            background: #e8f1ff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            width: 380px;
            background: #ffffffd9;
            backdrop-filter: blur(6px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .app-title {
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 13px;
            color: #555;
            margin-bottom: 20px;
        }

        .blurred-eye {
            filter: blur(1px);
            cursor: pointer;
            transition: 0.2s ease;
        }

        .blurred-eye:hover {
            filter: blur(0);
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="text-center">
            <div class="app-title">MaDuPos</div>
            <div class="subtitle">Manajemen Data Posyandu Desa Sempalwadak</div>
        </div>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" required autocomplete="off">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                    <input type="password" name="password" id="passwordField" class="form-control" required>
                    <span class="input-group-text blurred-eye" id="togglePassword">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-2">Login</button>

        </form>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Toggle password
        const toggle = document.getElementById("togglePassword");
        const passwordField = document.getElementById("passwordField");

        toggle.addEventListener("click", () => {
            const type = passwordField.type === "password" ? "text" : "password";
            passwordField.type = type;

            const icon = toggle.querySelector("i");
            icon.classList.toggle("bi-eye");
            icon.classList.toggle("bi-eye-slash");
        });

        // Toastr config
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "2500"
        };

        <?php if ($error): ?>
            toastr.error("<?= $error ?>");
        <?php endif; ?>

        <?php if ($success): ?>
            toastr.success("<?= $success ?>");
        <?php endif; ?>
    </script>

</body>
</html>
