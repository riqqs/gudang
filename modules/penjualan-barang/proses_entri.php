<?php
session_start(); // Mengaktifkan session

// Pengecekan session login user
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
  header('location: ../../login.php?pesan=2');
  exit;
} else {
  require_once "../../config/database.php";

  // Debugging: Check database connection
  if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $id_penjualan = $_POST['id_penjualan'];
    $quotation_no = $_POST['quotation_no'];
    $id_customer = $_POST['id_customer']; // New field for customer ID

    // Decode JSON `itemList` menjadi array
    $itemList = json_decode($_POST['itemList'], true);

    // Debugging: Check if itemList is valid and log the content
    if (is_array($itemList)) {
        error_log("itemList: " . print_r($itemList, true)); // Log the full array
    } else {
        error_log("itemList is not valid or missing.");
    }

    // Pastikan `itemList` valid setelah decoding
    if (is_array($itemList)) {
      
      // Step 1: Insert ke `tbl_penjualan_barang` dengan customer ID
      $query = "INSERT INTO tbl_penjualan_barang (id_penjualan, quotation_no, tanggal, status, keterangan, id_customer) 
                VALUES ('$id_penjualan', '$quotation_no', '$tanggal', 'Sedang Diproses', '$keterangan', '$id_customer')";
      
      // Debugging: Log the query being executed
      error_log("Query for tbl_penjualan_barang: " . $query);

      if (mysqli_query($mysqli, $query)) {

        // Step 2: Insert ke `tbl_penjualan_detail` untuk setiap item
        foreach ($itemList as $item) {
          $id_barang = $item['id_barang'];
          $jumlah = $item['jumlah'];

          // Debugging: Check if id_barang and jumlah are set correctly
          error_log("Inserting item - id_barang: " . $id_barang . ", jumlah: " . $jumlah);

          if (empty($id_barang) || empty($jumlah)) {
            error_log("Error: id_barang or jumlah is missing for item: " . print_r($item, true));
          }

          // Insert into tbl_penjualan_detail
          $detailQuery = "INSERT INTO tbl_penjualan_detail (id_penjualan, id_barang, jumlah) 
                          VALUES ('$id_penjualan', '$id_barang', '$jumlah')";
          
          // Debugging: Log the detail query
          error_log("Detail Query: " . $detailQuery);

          if (mysqli_query($mysqli, $detailQuery)) {
            // Get the last inserted id_penjualan_detail to reference in tbl_incoming_stock
            $id_penjualan_detail = mysqli_insert_id($mysqli);

            // Optionally insert into tbl_incoming_stock (if needed)
          } else {
            error_log("Failed to insert into tbl_penjualan_detail: " . mysqli_error($mysqli));
          }
        }

        // Response JSON success
        $response = [
          "success" => true,
          "id_penjualan" => $id_penjualan
        ];
        echo json_encode($response);

      } else {
        // Response JSON error untuk `tbl_penjualan_barang`
        error_log("Failed to save transaction to tbl_penjualan_barang: " . mysqli_error($mysqli));
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
