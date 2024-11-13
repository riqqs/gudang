<?php
session_start(); // Start the session

// Check if user is logged in
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    header('location: ../../login.php?pesan=2'); // Redirect to login if not logged in
} else {
    require_once "../../config/database.php"; // Database connection

    // Check if "id" parameter is passed
    if (isset($_GET['id'])) {
        $id_perpindahan = mysqli_real_escape_string($mysqli, $_GET['id']); // Get the transfer ID
        
        // Step 1: Get the related "id_permintaan" from tbl_perpindahan_barang
        $query = mysqli_query($mysqli, "SELECT id_permintaan FROM tbl_perpindahan_barang WHERE id_perpindahan='$id_perpindahan'");
        $result = mysqli_fetch_assoc($query);
        $id_permintaan = $result['id_permintaan']; // Get the related request ID
        
        // Step 2: Get the details of the transfer (items and quantities)
        $query_detail = mysqli_query($mysqli, "SELECT id_barang, jumlah FROM tbl_permintaan_detail WHERE id_permintaan='$id_permintaan'");
        
        // Step 3: Loop through the details and restore incoming stock, also reverting the real stock
        while ($detail = mysqli_fetch_assoc($query_detail)) {
            $id_barang = $detail['id_barang'];
            $jumlah = $detail['jumlah'];

            // Restore the incoming stock (if needed)
            mysqli_query($mysqli, "UPDATE tbl_incoming_stock SET jumlah = jumlah + $jumlah WHERE id_barang='$id_barang' AND id_permintaan='$id_permintaan'");

            // Since the transfer was canceled, revert the real stock by subtracting the quantity from tbl_barang
            mysqli_query($mysqli, "UPDATE tbl_barang SET stok = stok - $jumlah WHERE id_barang='$id_barang'");
        }

        // Step 4: Update the status of the request in tbl_permintaan_barang to 'pending'
        mysqli_query($mysqli, "UPDATE tbl_permintaan_barang SET status='Pending' WHERE id_permintaan='$id_permintaan'");

        // Step 5: Delete the transfer record from tbl_perpindahan_barang
        $delete = mysqli_query($mysqli, "DELETE FROM tbl_perpindahan_barang WHERE id_perpindahan='$id_perpindahan'");

        // Check if deletion was successful
        if ($delete) {
            // Redirect to the transfer page with a success message
            header('location: ../../main.php?module=perpindahan_barang&pesan=2');
        } else {
            // If delete failed, show error message
            echo "Error deleting transfer: " . mysqli_error($mysqli);
        }
    }
}
?>
