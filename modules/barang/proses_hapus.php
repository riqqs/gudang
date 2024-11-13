<?php
session_start(); // Start the session

// Check if the user is logged in
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
  header('location: ../../login.php?pesan=2'); // Redirect to login with a message if not logged in
} else {
  require_once "../../config/database.php"; // Database connection

  // Check if "id_barang" is provided
  if (isset($_GET['id'])) {
    $id_barang = mysqli_real_escape_string($mysqli, $_GET['id']);

    // Prevent deletion if "barang" exists in "tbl_barang_masuk"
    $query = mysqli_query($mysqli, "SELECT barang FROM tbl_barang_masuk WHERE barang='$id_barang'")
                                    or die('Error on query: ' . mysqli_error($mysqli));
    $rows = mysqli_num_rows($query);

    if ($rows != 0) {
      header('location: ../../main.php?module=barang&pesan=4'); // Redirect with failure message if used in transactions
    } else {
      // SQL statement to delete "barang" from "tbl_barang"
      $delete = mysqli_query($mysqli, "DELETE FROM tbl_barang WHERE id_barang='$id_barang'")
                                       or die('Error on delete query: ' . mysqli_error($mysqli));

      // If delete is successful
      if ($delete) {
        header('location: ../../main.php?module=barang&pesan=3'); // Redirect with success message
      }
    }
  }
}
?>
