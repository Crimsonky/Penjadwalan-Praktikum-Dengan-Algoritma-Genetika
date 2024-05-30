<?php
require 'function.php';
if(isset($_SESSION["email"])) {
    if($_SESSION["email"] == "admin@gmail.com") {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: user/dashboard.php");
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    <h2>Login</h2>
    <form autocomplete="off" action="" method="post">
        <input type="hidden" id="action" value="login">
        <label for="">Email : </label>
        <input type="text" id="email" value=""> <br>
        <label for="">Password : </label>
        <input type="password" id="password" value="">
        <button type="button" onclick="submitData()">Login</button>
    </form>
    <br>
    <a href="register.php">Halaman Register</a>
    <?php require 'script.php';?>
    </div>
</body>
</html>