<?php
include "koneksi.php";
session_start();

if (isset($_POST["action"])) {
    if ($_POST["action"] == "register") {
        register();
    } else if ($_POST["action"] == "login") {
        login();
    }
}

function register()
{
    global $conn;
    $email = $_POST["email"];
    $nama = $_POST["nama"];
    $password = $_POST["password"];
    $nim = $_POST["nim"];

    if (empty($email) || empty($nama) || empty($password) || empty($nim)) {
        echo "Silahkan Isi Semua Formulir";
        exit;
    }

    $user = mysqli_query($conn, "SELECT * FROM tb_user WHERE email = '$email'");
    if (mysqli_num_rows($user) > 0) {
        echo "Email Sudah Terdaftar";
        exit;
    }

    $user = mysqli_query($conn, "SELECT * FROM tb_user WHERE nim = '$nim'");
    if (mysqli_num_rows($user) > 0) {
        echo "NIM Sudah Terdaftar";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $query = "INSERT INTO tb_user VALUES('$email', '$nama', '$nim', '$hashedPassword')";
    mysqli_query($conn, $query);
    echo "Register Berhasil";
}

function login()
{
    global $conn;
    $email = $_POST["email"];
    $password = $_POST["password"];

    $user = mysqli_query($conn, "SELECT * FROM tb_user WHERE email = '$email'");
    if (mysqli_num_rows($user) > 0) {
        $row = mysqli_fetch_assoc($user);
        if (password_verify($password, $row['password'])) {
            echo "Login Berhasil";
            $_SESSION["login"] = true;
            $_SESSION["email"] = $row["email"];
        } else {
            echo "Password Salah";
            exit;
        }
    } else {
        echo "User Tidak Terdaftar";
        exit;
    }
}
?>