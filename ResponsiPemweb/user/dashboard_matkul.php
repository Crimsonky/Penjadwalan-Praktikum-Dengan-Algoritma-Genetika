<?php
    require '../function.php';
    if(isset($_SESSION["email"])){
    }
    else{
    header("Location: index.php");
    }
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
            <h1>Daftar Matkul</h1>
        </div>
        <section class="main-course">
            <div class="course-box1">
                <input class="inputAtribut" type="text" name="search" id="search" placeholder="search" class="form-control">
                <div id="result"></div>
            </div>
        </section>
    </section>
</div>
<script>
    function loadContent(url, isInMain) {
          fetch(url)
            .then(response => response.text())
            .then(data => {
              if (isInMain) {
                document.querySelector(".main").innerHTML = data;
              } else {
                document.body.innerHTML = data;
              }
            })
            .catch(error => console.error('Error:', error));
    }
    // Tambahkan event listener untuk setiap tautan
    document.getElementById("home-link").addEventListener("click", function(event) {
        event.preventDefault();
        window.location.href = "dashboard.php";
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
<script type="text/javascript">
    $(document).ready(function() {
        showdata();
        liveSearch();
    });
    function liveSearch() {
        $(document).on("keyup","#search", function(){
            var search_data = $(this).val();
            $.ajax({
                url: 'get_matkul.php',
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
            url: 'get_matkul.php',
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
