<?php
include "../../config/database.php";

if (isset($_GET['id'])) {
    $id_permintaan_detail = $_GET['id'];

    $query = "DELETE FROM tbl_permintaan_detail WHERE id_permintaan_detail='$id_permintaan_detail'";
    if (mysqli_query($mysqli, $query)) {
        header("Location: ../../main.php?module=permintaan_barang&pesan=2&id=" . $_GET['id_permintaan']);
    } else {
        echo "Gagal menghapus barang: " . mysqli_error($mysqli);
    }
}
?>
