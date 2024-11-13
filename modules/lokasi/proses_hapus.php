<?php
session_start();      // mengaktifkan session

// pengecekan session login user 
// jika user belum login
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
  // alihkan ke halaman login dan tampilkan pesan peringatan login
  header('location: ../../login.php?pesan=2');
}
// jika user sudah login, maka jalankan perintah untuk delete
else {
  // panggil file "database.php" untuk koneksi ke database
  require_once "../../config/database.php";

  // mengecek data GET "id_lokasi"
  if (isset($_GET['id'])) {
    // ambil data GET dari tombol hapus
    $id_lokasi = mysqli_real_escape_string($mysqli, $_GET['id']);

    // mengecek data lokasi barang untuk mencegah penghapusan data lokasi barang yang sudah digunakan di tabel "tbl_barang"
    // sql statement untuk menampilkan data "lokasi" dari tabel "tbl_barang" berdasarkan input "id_lokasi"
    $query = mysqli_query($mysqli, "SELECT lokasi FROM tbl_barang WHERE lokasi='$id_lokasi'")
                                    or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
    // ambil jumlah baris data hasil query
    $rows = mysqli_num_rows($query);

    // cek hasil query
    // jika data lokasi barang sudah ada di tabel "tbl_barang"
    if ($rows <> 0) {
      // alihkan ke halaman lokasi barang dan tampilkan pesan gagal hapus data
      header('location: ../../main.php?module=lokasi&pesan=5');
    }
    // jika data lokasi barang belum ada di tabel "tbl_barang"
    else {
      // sql statement untuk delete data dari tabel "tbl_lokasi" berdasarkan "id_lokasi"
      $delete = mysqli_query($mysqli, "DELETE FROM tbl_lokasi WHERE id_lokasi='$id_lokasi'")
                                       or die('Ada kesalahan pada query delete : ' . mysqli_error($mysqli));
      // cek query
      // jika proses delete berhasil
      if ($delete) {
        // alihkan ke halaman lokasi barang dan tampilkan pesan berhasil hapus data
        header('location: ../../main.php?module=lokasi&pesan=3');
      }
    }
  }
}
