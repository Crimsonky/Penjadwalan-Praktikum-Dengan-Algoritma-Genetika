<?php
    require '../function.php';
    if(isset($_SESSION["email"])){
        $email = $_SESSION["email"];
    }
    else{
    header("Location: index.php");
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = $_POST['namabaru'];
    $newNim = $_POST['nimbaru'];
    $newPass = $_POST['passbaru'];

    $hashedPassword = password_hash($newPass, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE tb_user SET nama = ?, nim = ?, password = ? WHERE email = ?");
    $stmt->bind_param("ssss", $newName, $newNim, $hashedPassword, $email);

    if ($stmt->execute()) {
        echo '<script>alert("Data berhasil diperbarui"); window.location.href = "dashboard.php";</script>';
        exit();
    } else {
        echo '<script>alert("Data Gagal Diperbarui"); window.location.href = "dashboard.php";</script>';
        exit();
    }

    $stmt->close();
}

$query_get_configuration = "SELECT * FROM tb_user WHERE email = '$email'";
$result_get_configuration = mysqli_query($conn, $query_get_configuration);
$row_configuration = mysqli_fetch_assoc($result_get_configuration);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="dashboard.css" />
</head>

<body>
    <div class="container">
        <nav>
            <ul>
                <li><a href="#" class="logo">
                        <img id="logo_website" src="images/kistore.png" alt="">
                        <span>Dashboard</span>
                    </a></li>
                <li><a href="#" id="home-link">
                        <i class="fas fa-home"></i>
                        <span class="nav-item">Home</span>
                    </a></li>
                <li><a href="#" id="arsip-link">
                        <i class="fas fa-plus-circle product-icon"></i>
                        <span class="nav-item">Arsip Jadwal</span>
                    </a></li>
                <li>
                    <a href="#" id="asprak-link">
                        <i class="fas fa-plus-circle product-icon"></i>
                        <span class="nav-item">Daftar Asprak</span>
                    </a>
                </li>
                <li>
                    <a href="#" id="matkul-link">
                        <i class="fas fa-plus-circle product-icon"></i>
                        <span class="nav-item">Daftar Matkul</span>
                    </a>
                </li>
                <li>
                <a href="#" id="ruang-link">
                    <i class="fas fa-plus-circle product-icon"></i>
                    <span class="nav-item">Daftar Ruangan</span>
                </a>
                </li>
                <li>
                <a href="#" id="profile-link">
                    <i class="fas fa-plus-circle product-icon"></i>
                    <span class="nav-item">Profile Pengguna</span>
                </a>
                </li>
                <li>
                <a class="logout-btn" href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log Out</span>
                </a>
                </li>
            </ul>
        </nav>

        <section class="main">
            <div class="main-top">
                <h1>Profile</h1>
            </div>
            <section class="main-course">
                <h1>Formulir Profile Pengguna</h1>
                <div class="course-box1">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                        <input type="hidden">
                        <label for="articleType">Nama :</label>
                        <input class="inputAtribut" type="text" id="articleType" name="namabaru" value="<?php echo $row_configuration['nama']; ?>">

                        <label for="usageType">NIM :</label>
                        <input class="inputAtribut" type="text" id="usageType" name="nimbaru" value="<?php echo $row_configuration['nim']; ?>">

                        <label for="productDisplayName">Password :</label>
                        <input class="inputAtribut" type="password" id="productDisplayName" name="passbaru"><br>

                        <input type="submit" value="Update">
                    </form>
                </div>
            </section>
        </section>
    </div>
    <script>
        // Tambahkan event listener untuk setiap tautan
        document.getElementById("home-link").addEventListener("click", function(event) {
            event.preventDefault();
            window.location.href = "dashboard.php";
        });

        // Tambahkan event listener untuk setiap tautan
        document.getElementById("arsip-link").addEventListener("click", function(event) {
            event.preventDefault();
            window.location.href = "dashboard_arsip.php";
        });

        // Tambahkan event listener untuk setiap tautan
        document.getElementById("asprak-link").addEventListener("click", function(event) {
          event.preventDefault();
          window.location.href = "dashboard_asprak.php";
        });

        // Tambahkan event listener untuk setiap tautan
        document.getElementById("matkul-link").addEventListener("click", function(event) {
          event.preventDefault();
          window.location.href = "dashboard_matkul.php";
        });

        // Tambahkan event listener untuk setiap tautan
        document.getElementById("profile-link").addEventListener("click", function(event) {
          event.preventDefault();
          window.location.href = "dashboard_edit_profile.php";
        });

        // Tambahkan event listener untuk setiap tautan
        document.getElementById("ruang-link").addEventListener("click", function(event) {
          event.preventDefault();
          window.location.href = "dashboard_ruangan.php";
        });
    </script>
</body>

</html>