<?php
require 'function.php';
if(isset($_SESSION["email"])){
    header("Location: index.php");
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    <h2>Register</h2>
    <form autocomplete="off" action="" method="post">
        <input type="hidden" id="action" value="register">
        <label for="">Email : </label>
        <input type="text" id="email" value=""> <br>
        <label for="">Nama : </label>
        <input type="text" id="nama" value=""> <br>
        <label for="">NIM : </label>
        <input type="text" id="nim" value=""> <br>
        <label for="">Password : </label>
        <input type="password" id="password" value="">
        <button type="button" onclick="submitData()">Register</button>
    </form>
    <br>
    <a href="index.php">Halaman Login</a>
    <?php require 'script.php';?>
    </div>
</body>
</html>