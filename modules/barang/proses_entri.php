<?php
session_start(); // activate session

// Check user login session
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
  // Redirect to login page with warning message
  header('location: ../../login.php?pesan=2');
} else {
  // Connect to database
  require_once "../../config/database.php";

  // Check if form was submitted
  if (isset($_POST['simpan'])) {
    // Retrieve form data
    $id_barang          = mysqli_real_escape_string($mysqli, $_POST['id_barang']);
    $nama_barang        = mysqli_real_escape_string($mysqli, trim($_POST['nama_barang']));
    $jenis              = mysqli_real_escape_string($mysqli, $_POST['jenis']);
    $stok_minimum       = mysqli_real_escape_string($mysqli, $_POST['stok_minimum']);
    $material_number    = mysqli_real_escape_string($mysqli, $_POST['material_number']); // Add material_number input

    // SQL statement to insert data into "tbl_barang"
    $insert = mysqli_query($mysqli, "INSERT INTO tbl_barang(id_barang, nama_barang, jenis, stok_minimum, material_number) 
                                     VALUES('$id_barang', '$nama_barang', '$jenis', '$stok_minimum', '$material_number')")
                                     or die('Error on insert query: ' . mysqli_error($mysqli));

    // Check if insert was successful
    if ($insert) {
      // Redirect to barang page with success message
      header('location: ../../main.php?module=barang&pesan=1');
    }
  }
}
