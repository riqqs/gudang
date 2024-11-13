<?php
// Assuming you have a connection to the database in $mysqli

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];
    $id_barang = $_POST['id_barang'];  // Array of barang IDs
    $jumlah = $_POST['jumlah'];        // Array of requested quantities

    // Generate the transaction ID (for simplicity, using auto-increment or custom logic)
    $id_transaksi = 'T' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);  // Example format T0001

    // Insert into tbl_permintaan_barang
    $query = "INSERT INTO tbl_permintaan_barang (id_permintaan, tanggal, status, keterangan) 
              VALUES ('$id_transaksi', '$tanggal', 'pending', '$keterangan')";

    if (mysqli_query($mysqli, $query)) {
        // Insert into tbl_incoming_stock for each requested item
        for ($i = 0; $i < count($id_barang); $i++) {
            $barang_id = $id_barang[$i];
            $qty = $jumlah[$i];

            // Insert into incoming stock
            $incoming_stock_query = "INSERT INTO tbl_incoming_stock (id_permintaan, id_barang, jumlah, tanggal)
                                     VALUES ('$id_transaksi', '$barang_id', '$qty', '$tanggal')";
            mysqli_query($mysqli, $incoming_stock_query);
        }
        // Return transaction ID as response
        echo json_encode(['transaction_id' => $id_transaksi]);
    } else {
        echo json_encode(['error' => 'Failed to save transaction']);
    }
}
?>
