<?php
    require '../function.php';
    if(isset($_SESSION["email"])){
    }
    else{
    header("Location: index.php");
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];

    $check_query = "SELECT * FROM tb_asisten_praktikum where nim = '$nim'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        echo '<script>alert("Asisten Praktikum Sudah Terdaftar"); window.location.href = "dashboard.php";</script>';
        exit(); 
    } else {
        $query = "CALL tambah_asisten_praktikum('$nim', '$nama')"; //Penerapan Stored Procedure (Bab1)
        if (mysqli_query($conn, $query)) {
            echo '<script>alert("Data berhasil ditambahkan"); window.location.href = "dashboard_asprak.php";</script>';
            exit(); 
        } else {
            echo '<script>alert("Data Gagal Ditambahkan"); window.location.href = "dashboard_asprak.php";</script>';
            exit(); 
        }
    }
}

?>

<div class="main-top">
    <h1>Tambah Asisten Praktikum</h1>
</div>
<section class="main-course">
    <h1>Formulir Tambah Asisten Praktikum</h1>
    <div class="course-box1">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="nim">NIM:</label>
            <input class="inputAtribut" type="text" id="nim" name="nim"><br>

            <label for="nama">Nama:</label>
            <input class="inputAtribut" type="text" id="nama" name="nama"><br>

            <input type="submit" value="Tambah Asisten Praktikum">
        </form>
    </div>
</section>
