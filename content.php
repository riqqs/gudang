<?php
// Prevent direct access to the PHP file from the browser, allowing access only through inclusion by other files
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  header('location: 404.html');
} else {
  require_once "config/database.php";

  // Select content based on the requested module
  if ($_GET['module'] == 'dashboard') {
    include "modules/dashboard/tampil_data.php";
  } 
  
  elseif ($_GET['module'] == 'barang') {
    include "modules/barang/tampil_data.php";
  } elseif ($_GET['module'] == 'form_entri_barang') {
    include "modules/barang/form_entri.php";
  } elseif ($_GET['module'] == 'form_ubah_barang') {
    include "modules/barang/form_ubah.php";
  } elseif ($_GET['module'] == 'tampil_detail_barang') {
    include "modules/barang/tampil_detail.php";
  } 
  
  elseif ($_GET['module'] == 'jenis') {
    include "modules/jenis/tampil_data.php";
  } elseif ($_GET['module'] == 'form_entri_jenis') {
    include "modules/jenis/form_entri.php";
  } elseif ($_GET['module'] == 'form_ubah_jenis') {
    include "modules/jenis/form_ubah.php";
  } 

  elseif ($_GET['module'] == 'customer') {
    include "modules/customer/tampil_data.php";
  } elseif ($_GET['module'] == 'form_entri_customer') {
    include "modules/customer/form_entri.php";
  } elseif ($_GET['module'] == 'form_ubah_customer') {
    include "modules/customer/form_ubah.php";
  } elseif ($_GET['module'] == 'tampil_detail_customer') {
    include "modules/customer/tampil_detail.php";
  } 
  
 elseif ($_GET['module'] == 'data_penjualan') {
  include "modules/penjualan-barang/tampil_data.php"; 
} elseif ($_GET['module'] == 'form_entri_penjualan_barang') {
  include "modules/penjualan-barang/form_entri.php"; 
} elseif ($_GET['module'] == 'tampil_detail_penjualan') {
  include "modules/penjualan-barang/tampil_detail.php";


  } elseif ($_GET['module'] == 'permintaan_barang') {
    include "modules/permintaan-barang/tampil_data.php"; 
  } elseif ($_GET['module'] == 'form_entri_permintaan_barang') {
    include "modules/permintaan-barang/form_entri.php"; 
  } elseif ($_GET['module'] == 'tampil_detail_permintaan') {
    include "modules/permintaan-barang/tampil_detail.php";
  }

    elseif ($_GET['module'] == 'perpindahan_barang') {
    include "modules/perpindahan-barang/tampil_data.php"; 
  } elseif ($_GET['module'] == 'tampil_detail_perpindahan') {
    include "modules/perpindahan-barang/tampil_detail.php";
  }
  

    elseif ($_GET['module'] == 'user') {
    include "modules/user/tampil_data.php";
  } elseif ($_GET['module'] == 'form_entri_user') {
    include "modules/user/form_entri.php";
  } elseif ($_GET['module'] == 'form_ubah_user') {
    include "modules/user/form_ubah.php";
  } elseif ($_GET['module'] == 'form_ubah_password') {
    include "modules/password/form_ubah.php";
  }
}
?>
