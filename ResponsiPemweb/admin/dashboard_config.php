<?php
    require '../function.php';
    if(isset($_SESSION["email"])){
    }
    else{
    header("Location: index.php");
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $populationSize = $_POST['populationSize'];
    $mutationRate = $_POST['mutationRate'];
    $generations = $_POST['generations'];

    $query = "CALL update_konfigurasi($populationSize, $mutationRate, $generations)"; //Penerapan Stored Procedure (Bab 1)

    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Konfigurasi Berhasil diperbarui"); window.location.href = "dashboard_config.php";</script>';
        exit(); 
    } else {
        echo '<script>alert("Konfigurasi Gagal Diperbarui"); window.location.href = "dashboard_config.php";</script>';
        exit(); 
    }
}

$query_get_configuration = "SELECT * FROM tb_konfigurasi ";
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
                <li><a href="#" id="config-link">
                        <i class="fas fa-users user-icon"></i>
                        <span class="nav-item">Konfigurasi</span>
                    </a></li>
                <li>
                    <a href="#" id="generate-link">
                    <i class="fas fa-users user-icon"></i>
                    <span class="nav-item">Generate Jadwal</span>
                    </a>
                </li> 
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
                <a class="logout-btn" href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log Out</span>
                </a>
                </li>
            </ul>
        </nav>

        <section class="main">
            <div class="main-top">
                <h1>Konfigurasi Penjadwalan</h1>
            </div>
            <section class="main-course">
                <h1>Formulir Konfigurasi</h1>
                <div class="course-box1">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                        <input type="hidden">
                        <label for="articleType">Population Size :</label>
                        <input class="inputAtribut" type="text" id="articleType" name="populationSize" value="<?php echo $row_configuration['populationSize']; ?>"><br>

                        <label for="usageType">Mutation Rate :</label>
                        <input class="inputAtribut" type="text" id="usageType" name="mutationRate" value="<?php echo $row_configuration['mutationRate']; ?>"><br>

                        <label for="productDisplayName">Generations :</label>
                        <input class="inputAtribut" type="text" id="productDisplayName" name="generations" value="<?php echo $row_configuration['generations']; ?>"><br>
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
        document.getElementById("config-link").addEventListener("click", function(event) {
            event.preventDefault();
            window.location.href = "dashboard_config.php";
        });

        document.getElementById("generate-link").addEventListener("click", function(event) {
            event.preventDefault();
            window.location.href = "dashboard_generate.php";
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
        document.getElementById("ruang-link").addEventListener("click", function(event) {
          event.preventDefault();
          window.location.href = "dashboard_ruangan.php";
        });
    </script>
</body>

</html>
