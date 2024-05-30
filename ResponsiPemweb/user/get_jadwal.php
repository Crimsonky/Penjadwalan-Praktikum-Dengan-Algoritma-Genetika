<?php
    require '../function.php';
    if(isset($_SESSION["email"])){
    $email = $_SESSION["email"];
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tb_user WHERE email = '$email'"));
    }
    else{
    header("Location: index.php");
    }

$limit_page = 10;
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
    $query = "SELECT * FROM view_jadwal_detail LIMIT $offset, $limit_page";
} else {
    $query = "SELECT * FROM view_jadwal_detail WHERE nama_matakuliah LIKE '%$search%'";
}

$fetch_query = mysqli_query($conn, $query);
$output = '';

$row = mysqli_num_rows($fetch_query);
if ($row > 0) {
    $output .= ' <table border="1" class="tabelproduk">
        <tr>
            <th>Kelas Matakuliah</th>
            <th>Ruangan</th>
            <th>Hari</th>
            <th>Waktu</th>
            <th>Asisten Praktikum</th>
        </tr>';

    while ($res = mysqli_fetch_array($fetch_query)) {
        $output .= "<tr class='schedule-item'>
            <td>{$res['nama_matakuliah']}</td>
            <td>{$res['nama_ruangan']}</td>
            <td>{$res['hari']}</td>
            <td>{$res['jam']}</td>
            <td>{$res['nama_asisten']}</td>
        </tr>";
    }

    $output .= "</table>";

    if (empty($search)) {
        $fetch_query = mysqli_query($conn, "SELECT * FROM view_jadwal_detail");
        $row = mysqli_num_rows($fetch_query);
        $total_page = ceil($row / $limit_page);

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
    echo "Tidak ada jadwal yang ditemukan.";
}
?>