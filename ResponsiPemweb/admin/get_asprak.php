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
    $query = "SELECT * FROM tb_asisten_praktikum LIMIT $offset, $limit_page";
} else {
    $query = "SELECT * FROM tb_asisten_praktikum where nama LIKE '%$search%'";
}

$fetch_query = mysqli_query($conn, $query);
$output = '';

$row = mysqli_num_rows($fetch_query);
if ($row > 0) {
    $output .= '<table border="1" class="tabelproduk">
    <tr>
        <th>NIM</th>
        <th>Nama Asisten Praktikum</th>
        <th>Jumlah Matakuliah Diampu</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>';

    while($res = mysqli_fetch_array($fetch_query)) {
        $output .= "<tr class='schedule-item'>
            <td>{$res['nim']}</td>
            <td>{$res['nama']}</td>
            <td>";
        
        $nim = $res['nim'];
        $sql_count = "SELECT count_matakuliah_ampu('$nim') AS jumlah_matakuliah";
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        
        $output .= $row_count['jumlah_matakuliah'];
        
        $output .= "</td>
        <td class='um_row'><a href='#' class='edit-btn' onclick='editAsprak(\"{$res['nim']}\"); return false;'>Edit</a></td>
        <td id='um_row'>
        <form method='post'>";
        $output .= "<input type='hidden' name='delete_nim' value='" . htmlspecialchars($res['nim']) . "'>
            <button id='um_del_but' type='submit' class='delete-btn'>Delete</button>
        </form>
    </td>
    </tr>";
    
    }
    
    $output .= "</table>";

    if (empty($search)) {
        $fetch_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_asisten_praktikum");
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
    echo "Tidak ada asisten praktikum yang ditemukan.";
}
?>