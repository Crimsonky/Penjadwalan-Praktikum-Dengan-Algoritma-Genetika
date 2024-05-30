<?php
    require '../function.php';
    if(isset($_SESSION["email"])){
    }
    else{
    header("Location: index.php");
    }

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_ruangan = $_POST['nama_ruangan'];

    $check_query = "SELECT * FROM tb_ruangan WHERE nama_ruangan = '$nama_ruangan'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        echo '<script>alert("Ruangan Sudah Terdaftar"); window.location.href = "dashboard.php";</script>';
        exit(); 
    } else {
        $query = "CALL tambah_ruangan('$nama_ruangan')"; //Penerapan Stored Procedure (Bab1)
        if (mysqli_query($conn, $query)) {
            echo '<script>alert("Data berhasil ditambahkan"); window.location.href = "dashboard_ruangan.php";</script>';
            exit(); 
        } else {
            echo '<script>alert("Data Gagal Ditambahkan"); window.location.href = "dashboard_ruangan.php";</script>';
            exit(); 
        }
    }
}

?>

<div class="main-top">
    <h1>Tambah Ruangan</h1>
</div>
<section class="main-course">
    <h1>Formulir Tambah Ruangan</h1>
    <div class="course-box1">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="nama_ruangan">Nama Ruangan:</label>
            <input class="inputAtribut" type="text" id="nama_ruangan" name="nama_ruangan"><br>

            <input type="submit" value="Tambah Ruangan">
        </form>
    </div>
</section>
