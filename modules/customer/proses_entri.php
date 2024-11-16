<?php
session_start();      // mengaktifkan session

// pengecekan session login user 
// jika user belum login
if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
  // alihkan ke halaman login dan tampilkan pesan peringatan login
  header('location: ../../login.php?pesan=2');
}
// jika user sudah login, maka jalankan perintah untuk insert
else {
  // panggil file "database.php" untuk koneksi ke database
  require_once "../../config/database.php";

  // mengecek data hasil submit dari form
  if (isset($_POST['simpan'])) {
    
    $id_customer = mysqli_real_escape_string($mysqli, trim($_POST['id_customer']));
    $nama = mysqli_real_escape_string($mysqli, trim($_POST['nama']));
    $telepon = mysqli_real_escape_string($mysqli, trim($_POST['telepon']));
    $email = mysqli_real_escape_string($mysqli, trim($_POST['email']));
    $alamat = mysqli_real_escape_string($mysqli, trim($_POST['alamat']));

    // mengecek email untuk mencegah data duplikat
    // sql statement untuk menampilkan data "email" dari tabel "tbl_customer" berdasarkan input "email"
    $query = mysqli_query($mysqli, "SELECT email FROM tbl_customer WHERE email='$email'")
                                    or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
    // ambil jumlah baris data hasil query
    $rows = mysqli_num_rows($query);

    // cek hasil query
    // jika email sudah ada di tabel "tbl_customer"
    if ($rows <> 0) {
      // alihkan ke halaman customer dan tampilkan pesan gagal simpan data
      header("location: ../../main.php?module=customer&pesan=4&email=$email");
    }
    // jika email belum ada di tabel "tbl_customer"
    else {
      // sql statement untuk insert data ke tabel "tbl_customer"
      $insert = mysqli_query($mysqli, "INSERT INTO tbl_customer(id_customer, nama, telepon, email, alamat) 
                                       VALUES('$id_customer', '$nama', '$telepon', '$email', '$alamat')")
                                       or die('Ada kesalahan pada query insert : ' . mysqli_error($mysqli));
      // cek query
      // jika proses insert berhasil
      if ($insert) {
        // alihkan ke halaman customer dan tampilkan pesan berhasil simpan data
        header('location: ../../main.php?module=customer&pesan=1');
      }
    }
  }
}
