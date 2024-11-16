<?php
include "../../config/database.php";

if (isset($_GET['id'])) {
    $id_barang = $_GET['id'];

    $query = "DELETE FROM tbl_penjualan_detail WHERE id_barang='$id_barang'";
    if (mysqli_query($mysqli, $query)) {
        header("Location: ../../main.php?module=data_penjualan&pesan=2&id=" . $_GET['id_penjualan']);
    } else {
        echo "Gagal menghapus barang: " . mysqli_error($mysqli);
    }
}
?>
