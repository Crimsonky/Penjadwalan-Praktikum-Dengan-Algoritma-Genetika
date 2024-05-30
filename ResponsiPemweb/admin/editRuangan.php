<?php
    require '../function.php';
    if(isset($_SESSION["email"])){
    }
    else{
    header("Location: index.php");
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_kode_ruang = $_POST['old_kode_ruang'];
    $new_ruangan_name = $_POST['new_ruangan_name']; 

    $query = "CALL 	update_nama_ruangan('$old_kode_ruang', ' $new_ruangan_name')"; //Penerapan Stored Procedure (Bab1)

    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Data berhasil diperbarui"); window.location.href = "dashboard_ruangan.php";</script>';
        exit(); 
    } else {
        echo '<script>alert("Data Gagal Diperbarui"); window.location.href = "dashboard_ruangan.php";</script>';
        exit(); 
    }
}

if (isset($_GET['kode_ruang'])) {
    $old_kode_ruang = $_GET['kode_ruang'];
    $query_get_ruang = "SELECT * FROM view_ruangan WHERE kode_ruang = '$old_kode_ruang'";
    $result_get_ruang = mysqli_query($conn, $query_get_ruang);
    $row_ruang = mysqli_fetch_assoc($result_get_ruang);
}

?>

<div class="main-top">
    <h1>Edit Ruangan</h1>
    <i class="fas fa-user-cog"></i>
</div>
<section class="main-course">
    <h1>Formulir Edit Ruangan</h1>
    <div class="course-box1">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="old_kode_ruang" value="<?php echo $row_ruang['kode_ruang']; ?>">

            <label for="new_ruangan_name">Nama Ruangan Baru:</label>
            <input class="inputAtribut" type="text" id="new_ruangan_name" name="new_ruangan_name" value="<?php echo $row_ruang['nama_ruangan']; ?>"><br>

            <input type="submit" value="Update">
        </form>
    </div>
</section>
