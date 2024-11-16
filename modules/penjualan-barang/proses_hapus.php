<?php
session_start(); // Activate session

// Check if the user is not logged in
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    // Redirect to login page with a warning message
    header('location: ../../login.php?pesan=2');
    exit;
} else {
    // Include database connection file
    require_once "../../config/database.php";

    // Check if the GET variable "id" is set
    if (isset($_GET['id'])) {
        // Securely get the ID from the GET request
        $id_penjualan = mysqli_real_escape_string($mysqli, $_GET['id']);

        // Delete related records in tbl_penjualan_detail first
        $delete_details = mysqli_query($mysqli, "DELETE FROM tbl_penjualan_detail WHERE id_penjualan='$id_penjualan'")
                          or die('Error deleting related records in tbl_penjualan_detail: ' . mysqli_error($mysqli));

        // Check if related records were successfully deleted or if none existed
        if ($delete_details) {
            // Delete the main record in tbl_penjualan_barang
            $delete = mysqli_query($mysqli, "DELETE FROM tbl_penjualan_barang WHERE id_penjualan='$id_penjualan'")
                      or die('Error deleting the main record in tbl_penjualan_barang: ' . mysqli_error($mysqli));

            // Check if the deletion was successful
            if ($delete) {
                // Redirect to the main module page with a success message
                header('location: ../../main.php?module=data_penjualan&pesan=2');
                exit;
            } else {
                // Display an error if the main record could not be deleted
                echo 'Error: Could not delete the main record in tbl_penjualan_barang.';
            }
        } else {
            // Display an error if related records could not be deleted
            echo 'Error: Could not delete related records in tbl_penjualan_detail.';
        }
    } else {
        // Display an error if "id" is not set in the GET request
        echo 'Error: ID not found.';
    }
}
?>
