<?php
    require '../function.php';
    if(isset($_SESSION["email"])){
    }
    else{
    header("Location: index.php");
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_kode_mk = $_POST['old_kode_mk'];
    $new_matakuliah_name = $_POST['new_matakuliah_name']; 

    $query = "CALL update_nama_matakuliah('$old_kode_mk', ' $new_matakuliah_name')"; //Penerapan Stored Procedure (Bab1)

    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Data berhasil diperbarui"); window.location.href = "dashboard_matkul.php";</script>';
        exit(); 
    } else {
        echo '<script>alert("Data Gagal Diperbarui"); window.location.href = "dashboard_matkul.php";</script>';
        exit(); 
    }
}

if (isset($_GET['kode_mk'])) {
    $old_kode_mk = $_GET['kode_mk'];
    $query_get_matkul = "SELECT * FROM view_matakuliah WHERE kode_mk = '$old_kode_mk'";
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
            <input type="hidden" name="old_kode_mk" value="<?php echo $row_matkul['kode_mk']; ?>">

            <label for="new_matakuliah_name">Nama Matakuliah Baru:</label>
            <input class="inputAtribut" type="text" id="new_matakuliah_name" name="new_matakuliah_name" value="<?php echo $row_matkul['nama_matakuliah']; ?>"><br>

            <input type="submit" value="Update">
        </form>
    </div>
</section>
