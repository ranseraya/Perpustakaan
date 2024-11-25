<?php 
require '../config/functions.php';

if( isset($_POST["register"]) ) {
    $username = strtolower(trim($_POST["username"]));
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    if ($password !== $password2) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        $result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
        if (mysqli_num_rows($result) > 0) {
            $error = "Username sudah digunakan!";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO users (username, password_hash, role) VALUES ('$username', '$password_hash', 'anggota')");

            if (mysqli_affected_rows($conn) > 0) {
                echo "<script>
                        alert('User baru berhasil ditambahkan!');
                        window.location = 'login.php';
                      </script>";
            } else {
                $error = "Registrasi gagal. Silakan coba lagi.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Registrasi</title>
    <link rel="stylesheet" href="../assest/register.css">
</head>
<body>
    <div class="register-container">
        <h1>Registrasi</h1>

        <?php if( isset($error) ): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <ul>
                <li>
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                </li>
                <li>
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </li>
                <li>
                    <label for="password2">Konfirmasi Password:</label>
                    <input type="password" name="password2" id="password2" required>
                </li>
                <li>
                    <button type="submit" name="register">Daftar</button>
                </li>
            </ul>
        </form>

        <div class="toggle">
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</body>
</html>
