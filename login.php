<?php
session_start();
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Gunakan prepared statement untuk mencegah SQL injection
    $query_sql = "SELECT * FROM tbl_users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query_sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login berhasil
        $_SESSION['user_email'] = $email;
        header("Location: tuk.php");
        exit();
    } else {
        // Login gagal
        echo "<center><h1>Email atau Password Anda Salah. Silahkan Coba Login Kembali.</h1>
              <button><strong><a href='login.html'>Login</a></strong></button></center>";
    }

    $stmt->close();
}
$conn->close();
?>