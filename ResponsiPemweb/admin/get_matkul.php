<?php
    require '../function.php';
    if(isset($_SESSION["email"])){
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
    $query = "SELECT * FROM view_matakuliah LIMIT $offset, $limit_page";
} else {
    $query = "SELECT * FROM view_matakuliah where nama_matakuliah LIKE '%$search%'";
}

$fetch_query = mysqli_query($conn, $query);
$output = '';

$row = mysqli_num_rows($fetch_query);
if ($row > 0) {
    $output .= '<table border="1" class="tabelproduk">
    <tr>
        <th>Kode Matakuliah</th>
        <th>Nama Matakuliah</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>';

    while($res = mysqli_fetch_array($fetch_query)) {
        $output .= "<tr class='schedule-item'>
            <td>{$res['kode_mk']}</td>
            <td>{$res['nama_matakuliah']}</td>";
        
        $output .= "
        <td class='um_row'><a href='#' class='edit-btn' onclick='editMatkul(\"{$res['kode_mk']}\"); return false;'>Edit</a></td>
        <td id='um_row'>
        <form method='post'>";
        $output .= "<input type='hidden' name='delete_kode_mk' value='" . htmlspecialchars($res['kode_mk']) . "'>
            <button id='um_del_but' type='submit' class='delete-btn'>Delete</button>
        </form>
    </td>
    </tr>";

    }
    
    $output .= "</table>";

    if (empty($search)) {
        $fetch_query = mysqli_query($conn, "SELECT * FROM view_matakuliah");
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
    echo "Tidak ada matakuliah yang ditemukan.";
}
?>