<?php 
session_start();
require '../config/functions.php';

if( isset($_COOKIE['id']) && isset($_COOKIE['key']) ) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    $result = mysqli_query($conn, "SELECT username, role FROM users WHERE id = $id");
    $row = mysqli_fetch_assoc($result);

    if( $key === hash('sha256', $row['username']) ) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
    }
}

if( isset($_SESSION["login"]) ) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

if( isset($_POST["login"]) ) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    if( mysqli_num_rows($result) === 1 ) {
        $row = mysqli_fetch_assoc($result);
        if( password_verify($password, $row["password_hash"]) ) {
            $_SESSION["login"] = true;
            $_SESSION["username"] = $row["username"];
            $_SESSION["role"] = $row["role"]; 

            if ($row['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        }
    }

    $error = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <link rel="stylesheet" href="../assest/login.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>

        <?php if( isset($error) ): ?>
            <p class="error">Username atau password salah!</p>
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
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </li>
                <li>
                    <button type="submit" name="login">Login</button>
                </li>
            </ul>
        </form>

        <div class="toggle">
            <p>Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
        </div>
    </div>
</body>
</html>
