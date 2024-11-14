<?php
include "../../config/database.php";

if (isset($_POST['id_permintaan_detail']) && isset($_POST['jumlah'])) {
    $id_permintaan_detail = $_POST['id_permintaan_detail'];
    $jumlah = $_POST['jumlah'];

    // Mendapatkan ID barang dari tbl_permintaan_detail berdasarkan id_permintaan_detail
    $query_barang = "SELECT id_barang FROM tbl_permintaan_detail WHERE id_permintaan_detail = '$id_permintaan_detail'";
    $result_barang = mysqli_query($mysqli, $query_barang);
    if ($result_barang) {
        $data_barang = mysqli_fetch_assoc($result_barang);
        $id_barang = $data_barang['id_barang'];

        // Update jumlah barang di tbl_permintaan_detail
        $query = "UPDATE tbl_permintaan_detail SET jumlah='$jumlah' WHERE id_permintaan_detail='$id_permintaan_detail'";
        if (mysqli_query($mysqli, $query)) {

            // Update jumlah barang di tbl_incoming_stock berdasarkan id_barang dan id_permintaan
            $query_incoming_stock = "UPDATE tbl_incoming_stock SET jumlah='$jumlah' WHERE id_permintaan_detail='$id_permintaan_detail' AND id_barang='$id_barang'";
            if (mysqli_query($mysqli, $query_incoming_stock)) {
                // Redirect jika berhasil
                header("Location: ../../main.php?module=permintaan_barang&pesan=3&id=" . $_POST['id_permintaan']);
            } else {
                echo "Gagal memperbarui jumlah barang di tbl_incoming_stock: " . mysqli_error($mysqli);
            }
        } else {
            echo "Gagal memperbarui jumlah barang di tbl_permintaan_detail: " . mysqli_error($mysqli);
        }
    } else {
        echo "Gagal mendapatkan ID Barang dari tbl_permintaan_detail.";
    }
}
?>
