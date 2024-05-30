<?php
    require '../function.php';
    if(isset($_SESSION["email"])){
    }
    else{
    header("Location: index.php");
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['old_kode_nim'];
    $new_nama_asprak = $_POST['new_nama_asprak'];

    $query = "CALL update_asisten_praktikum('$nim', ' $new_nama_asprak')"; //Penerapan Stored Procedure (Bab1)

    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Data berhasil diperbarui"); window.location.href = "dashboard_asprak.php";</script>';
        exit(); 
    } else {
        echo '<script>alert("Data Gagal Diperbarui"); window.location.href = "dashboard_asprak.php";</script>';
        exit(); 
    }
}

if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];
    $query_get_matkul = "SELECT nim, nama FROM tb_asisten_praktikum WHERE nim = '$nim'";
    $result_get_matkul = mysqli_query($conn, $query_get_matkul);
    $row_matkul = mysqli_fetch_assoc($result_get_matkul);
}

?>

<div class="main-top">
    <h1>Edit Matakuliah</h1>
    <i class="fas fa-user-cog"></i>
</div>
<section class="main-course">
    <h1>Formulir Edit Matakuliah</h1>
    <div class="course-box1">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="old_kode_nim" value="<?php echo $row_matkul['nim']; ?>">

            <label for="new_nama_asprak">Nama Asisten Praktikum Baru:</label>
            <input class="inputAtribut" type="text" id="new_nama_asprak" name="new_nama_asprak" value="<?php echo $row_matkul['nama']; ?>"><br>

            <input type="submit" value="Update">
        </form>
    </div>
</section>
