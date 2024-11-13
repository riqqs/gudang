<?php
session_start(); // Mengaktifkan session

// Pengecekan session login user
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
  header('location: ../../login.php?pesan=2');
  exit;
} else {
  require_once "../../config/database.php";

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $id_permintaan = $_POST['id_permintaan'];
    
    // Decode JSON `itemList` menjadi array
    $itemList = json_decode($_POST['itemList'], true);

    // Pastikan `itemList` valid setelah decoding
    if (is_array($itemList)) {
      
      // Step 1: Insert ke `tbl_permintaan_barang`
      $query = "INSERT INTO tbl_permintaan_barang (id_permintaan, tanggal, status, keterangan) VALUES ('$id_permintaan', '$tanggal', 'pending', '$keterangan')";
      if (mysqli_query($mysqli, $query)) {

        // Step 2: Insert ke `tbl_permintaan_detail` dan `tbl_incoming_stock` untuk setiap item
        foreach ($itemList as $item) {
          $id_barang = $item['id_barang'];
          $jumlah = $item['jumlah'];

          // Insert into tbl_permintaan_detail
          $detailQuery = "INSERT INTO tbl_permintaan_detail (id_permintaan, id_barang, jumlah) VALUES ('$id_permintaan', '$id_barang', '$jumlah')";
          if (mysqli_query($mysqli, $detailQuery)) {

            // Get the last inserted id_permintaan_detail to reference in tbl_incoming_stock
            $id_permintaan_detail = mysqli_insert_id($mysqli);

            // Insert into tbl_incoming_stock with reference to id_permintaan_detail
            $incomingStockQuery = "INSERT INTO tbl_incoming_stock (id_permintaan, id_permintaan_detail, id_barang, jumlah, tanggal) VALUES ('$id_permintaan', '$id_permintaan_detail', '$id_barang', '$jumlah', NOW())";
            mysqli_query($mysqli, $incomingStockQuery);
          }
        }

        // Response JSON success
        $response = [
          "success" => true,
          "id_permintaan" => $id_permintaan
        ];
        echo json_encode($response);

      } else {
        // Response JSON error untuk `tbl_permintaan_barang`
        $response = [
          "success" => false,
          "message" => "Failed to save transaction."
        ];
        echo json_encode($response);
      }

    } else {
      // Response JSON error untuk `itemList` tidak valid
      $response = [
        "success" => false,
        "message" => "Data itemList tidak valid atau tidak ada."
      ];
      echo json_encode($response);
    }
  }
}
?>
