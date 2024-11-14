<?php
include "../../config/database.php";

// Check if the form is submitted via POST method and required data is present
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_permintaan']) && isset($_POST['tanggal_transfer'])) {
    $id_permintaan = $_POST['id_permintaan'];
    $tanggal_transfer = $_POST['tanggal_transfer'];

    // Generate a new transfer ID with format IT-0000001
    $query = mysqli_query($mysqli, "SELECT MAX(id_perpindahan) AS last_id FROM tbl_perpindahan_barang");
    $data = mysqli_fetch_assoc($query);
    $lastId = (int) str_replace("IT-", "", $data['last_id'] ?? "IT-0000000");
    $newId = "IT-" . str_pad($lastId + 1, 7, '0', STR_PAD_LEFT);

    // Process transfer details from the permintaan (request) items
    $query_detail = mysqli_query($mysqli, "SELECT * FROM tbl_permintaan_detail WHERE id_permintaan='$id_permintaan'");

    while ($detail = mysqli_fetch_assoc($query_detail)) {
        $id_barang = $detail['id_barang'];  // Get the item ID
        $jumlah = $detail['jumlah'];        // Get the quantity of the item

        // Update the actual stock in 'tbl_barang' by adding the requested quantity
        mysqli_query($mysqli, "UPDATE tbl_barang SET stok = stok + $jumlah WHERE id_barang='$id_barang'");

        // Update the incoming stock in 'tbl_incoming_stock' by subtracting the transferred quantity
        mysqli_query($mysqli, "UPDATE tbl_incoming_stock SET jumlah = jumlah - $jumlah WHERE id_barang='$id_barang' AND id_permintaan='$id_permintaan'");
    }

    // Insert a new record into 'tbl_perpindahan_barang' with the generated transfer ID and transfer date
    $query_insert = "INSERT INTO tbl_perpindahan_barang (id_perpindahan, id_permintaan, tanggal) 
                     VALUES ('$newId', '$id_permintaan', '$tanggal_transfer')";

    if (mysqli_query($mysqli, $query_insert)) {
        // Update the status of the permintaan to 'Dikirim'
        $query_update_status = "UPDATE tbl_permintaan_barang 
                                SET status = 'Diterima' 
                                WHERE id_permintaan = '$id_permintaan'";

        if (mysqli_query($mysqli, $query_update_status)) {
            // Redirect to the main page with a success message
            header("Location: ../../main.php?module=permintaan_barang&pesan=3");
            exit;
        } else {
            echo "Error updating status in tbl_permintaan_barang: " . mysqli_error($mysqli);
        }
    } else {
        echo "Error inserting transfer record: " . mysqli_error($mysqli);
    }
}
?>
