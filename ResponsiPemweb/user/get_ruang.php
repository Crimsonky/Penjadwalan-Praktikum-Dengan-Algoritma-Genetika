<?php
    require '../function.php';
    if(isset($_SESSION["email"])){
    $email = $_SESSION["email"];
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tb_user WHERE email = '$email'"));
    }
    else{
    header("Location: index.php");
    }

$limit_page = 5;
$page = "";
$search = "";

if (isset($_POST['page_no'])) {
    $page = $_POST['page_no'];
} else {
    $page = 1;
}

if (isset($_POST['search'])) {
    $search = $_POST['search'];
} else {
    $search = "";
}

$offset = ($page - 1) * $limit_page;

if (empty($search)) {
    $query = "SELECT * FROM view_ruangan LIMIT $offset, $limit_page";
} else {
    $query = "SELECT * FROM view_ruangan where nama_ruangan LIKE '%$search%'";
}

$fetch_query = mysqli_query($conn, $query);
$output = '';

$row = mysqli_num_rows($fetch_query);
if ($row > 0) {
    $output .= '<table border="1" class="tabelproduk">
    <tr>
        <th>Kode Ruangan</th>
        <th>Nama Ruangan</th>
        <th>Kelas Terdaftar</th>
    </tr>';

    while($res = mysqli_fetch_array($fetch_query)) {
        $output .= "<tr class='schedule-item'>
            <td>{$res['kode_ruang']}</td>
            <td>{$res['nama_ruangan']}</td>
            <td>";
        
            $kode_ruang = $res["kode_ruang"];
            $sql_count = "SELECT count_matakuliah_ruang('$kode_ruang') AS jumlah_matakuliah"; //Penerapa function (Bab1)
            $result_count = $conn->query($sql_count);
            $row_count = $result_count->fetch_assoc();
        
        $output .= $row_count['jumlah_matakuliah'];
        
        $output .= "</td>";
        $output .= "</td></tr>";
    }
    
    $output .= "</table>";

    if (empty($search)) {
        $fetch_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM view_ruangan");
        $total_row = mysqli_fetch_assoc($fetch_query)['total'];
        $total_page = ceil($total_row / $limit_page);    

        $output .= '<ul class="pagination">';
        for ($i = 1; $i <= $total_page; $i++) {
            if ($i == $page) {
                $active = "active";
            } else {
                $active = "";
            }
            $output .= "<li class='$active'><a id='$i'>$i</a></li>";
        }
        $output .= '</ul>';
    }

    echo $output;
} else {
    echo "Tidak ada ruangan yang ditemukan.";
}
?>