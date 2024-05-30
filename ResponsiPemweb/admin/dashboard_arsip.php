<?php
    require '../function.php';
    if(isset($_SESSION["email"])){
    }
    else{
    header("Location: index.php");
    }

$query_get_genfit = "SELECT best_generation, fitness_score FROM view_jadwal_detail"; //Penerapan View (Bab 3)
$result_get_genfit = mysqli_query($conn, $query_get_genfit);
$row_genfit = mysqli_fetch_assoc($result_get_genfit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <link rel="stylesheet" href="dashboard.css" />
    <script  src="https://code.jquery.com/jquery-3.7.1.js"></script>
</head>
<body>
<div class="container">
    <nav>
        <ul>
            <li>
                <a href="#" class="logo">
                    <img id="logo_website" src="images/logo.png" alt="">
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" id="home-link">
                    <i class="fas fa-home"></i>
                    <span class="nav-item">Home</span>
                </a>
            </li>
            <li>
                <a href="#" id="config-link">
                    <i class="fas fa-users user-icon"></i>
                    <span class="nav-item">Konfigurasi</span>
                </a>
            </li>
            <li>
                <a href="#" id="generate-link">
                <i class="fas fa-users user-icon"></i>
                <span class="nav-item">Generate Jadwal</span>
                </a>
            </li> 
            <li>
                <a href="#" id="arsip-link">
                    <i class="fas fa-plus-circle product-icon"></i>
                    <span class="nav-item">Arsip Jadwal</span>
                </a>
            </li>
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
            <h1>Arsip Jadwal Praktikum</h1>
        </div>
        <section class="main-course">
            <div class="course-box1">
                <input class="inputAtribut" type="text" name="search" id="search" placeholder="search" class="form-control">
                <?php if(isset($row_genfit['best_generation'])): ?>
                    <h5>Generasi Terbaik : <?php echo $row_genfit['best_generation']; ?></h5>
                <?php else: ?>
                    <h5>Generasi Terbaik : Data tidak tersedia</h5>
                <?php endif; ?>

                <?php if(isset($row_genfit['fitness_score'])): ?>
                    <h5>Fitness Score : <?php echo $row_genfit['fitness_score']; ?></h5>
                <?php else: ?>
                    <h5>Fitness Score : Data tidak tersedia</h5>
                <?php endif; ?>

                <div id="result"></div>
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

    document.getElementById("config-link").addEventListener("click", function(event) {
        event.preventDefault();
        window.location.href = "dashboard_config.php";
    });

    document.getElementById("generate-link").addEventListener("click", function(event) {
        event.preventDefault();
        window.location.href = "dashboard_generate.php";
    });

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
<script type="text/javascript">
    $(document).ready(function() {
        showdata();
        liveSearch();
    });

    function liveSearch() {
        $(document).on("keyup","#search", function(){
            var search_data = $(this).val();
            $.ajax({
                url: 'get_jadwal.php',
                method: 'post',
                data: {search:search_data},
                success: function(data) {
                    $("#result").html(data);
                }
            });
        });
    }
    function showdata(page) {
        $.ajax({
            url: 'get_jadwal.php',
            method: 'post',
            data: {page_no:page},
            success: function(result) {
                $("#result").html(result);
            }
        });
    }
    $(document).on("click",".pagination a", function() {
        var page = $(this).attr('id');
        showdata(page);
    })
</script>
</body>
</html>