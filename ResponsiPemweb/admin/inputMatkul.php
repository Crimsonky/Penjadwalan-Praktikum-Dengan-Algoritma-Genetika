<?php
    require '../function.php';
    if(isset($_SESSION["email"])){
    }
    else{
    header("Location: index.php");
    }

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai dari form
    $kode_mk = $_POST['kode_mk'];
    $nama_matakuliah = $_POST['nama_matakuliah'];

    $check_query = "SELECT * FROM tb_matakuliah WHERE kode_mk = '$kode_mk'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        echo '<script>alert("Kode Matakuliah Sudah Terdaftar"); window.location.href = "dashboard.php";</script>';
        exit(); 
    } else {
        $query = "CALL tambah_matakuliah('$kode_mk', '$nama_matakuliah')"; //Penerapan Stored Procedure (Bab1)
        if (mysqli_query($conn, $query)) {
            echo '<script>alert("Data berhasil ditambahkan"); window.location.href = "dashboard_matkul.php";</script>';
            exit(); 
        } else {
            echo '<script>alert("Data Gagal Ditambahkan"); window.location.href = "dashboard_matkul.php";</script>';
            exit(); 
        }
    }
}
?>

<div class="main-top">
    <h1>Tambah Matakuliah</h1>
</div>
<section class="main-course">
    <h1>Formulir Tambah Matakuliah</h1>
    <div class="course-box1">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="kode_mk">Kode Matakuliah:</label>
            <input class="inputAtribut" type="text" id="kode_mk" name="kode_mk"><br>

            <label for="nama_matakuliah">Nama Matakuliah:</label>
            <input class="inputAtribut" type="text" id="nama_matakuliah" name="nama_matakuliah"><br>

            <input type="submit" value="Tambah Matakuliah">
        </form>
    </div>
</section>
