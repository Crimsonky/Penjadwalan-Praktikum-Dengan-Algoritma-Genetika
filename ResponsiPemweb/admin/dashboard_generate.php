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
        <h1>Generate Jadwal Praktikum</h1>
      </div>
      <section class="main-course">
        <div class="course-box1">
          <p>Silahkan Tekan Tombol Untuk Generate Jadwal:</p>
          </br>
          <button class = "searchBut" id="generate-jadwal-button" >
            <span> Generate Jadwal</span>
          </button>
          <table border="1" class="tabelproduk">
          </table>
        </div>
      </section>  
    </section>
  </div>
  <script>
    // Fungsi untuk memuat konten melalui AJAX
    function loadContent(url, isInMain) {
      fetch(url)
        .then(response => response.text())
        .then(data => {
          if (isInMain) {
            document.querySelector(".tabelproduk").innerHTML = data;
          } else {
            document.body.innerHTML = data;
          }
        })
        .catch(error => console.error('Error:', error));
    }

    // Tambahkan event listener untuk setiap tautan
    document.getElementById("generate-jadwal-button").addEventListener("click", function(event) {
      event.preventDefault();
      loadContent("index2.php", true);
    });

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
