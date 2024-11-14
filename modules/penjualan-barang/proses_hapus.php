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
        $id_permintaan = mysqli_real_escape_string($mysqli, $_GET['id']);

        // Delete related records in tbl_permintaan_detail first
        $delete_details = mysqli_query($mysqli, "DELETE FROM tbl_permintaan_detail WHERE id_permintaan='$id_permintaan'")
                          or die('Error deleting related records in tbl_permintaan_detail: ' . mysqli_error($mysqli));

        // Check if related records were successfully deleted or if none existed
        if ($delete_details) {
            // Delete related records in tbl_incoming_stock
            $delete_incoming_stock = mysqli_query($mysqli, "DELETE FROM tbl_incoming_stock WHERE id_permintaan='$id_permintaan'")
                                     or die('Error deleting related records in tbl_incoming_stock: ' . mysqli_error($mysqli));

            // Check if related records in tbl_incoming_stock were deleted
            if ($delete_incoming_stock) {
                // Delete the main record in tbl_permintaan_barang
                $delete = mysqli_query($mysqli, "DELETE FROM tbl_permintaan_barang WHERE id_permintaan='$id_permintaan'")
                             or die('Ada kesalahan pada query delete: ' . mysqli_error($mysqli));

                // Check if the deletion was successful
                if ($delete) {
                    // Redirect to the main module page with a success message
                    header('location: ../../main.php?module=permintaan_barang&pesan=2');
                    exit;
                }
            }
        } else {
            // Display an error if related records could not be deleted
            echo 'Error: Could not delete related records in tbl_permintaan_detail.';
        }
    } else {
        // Display an error if "id" is not set in the GET request
        echo 'Error: ID not found.';
    }
}
?>
