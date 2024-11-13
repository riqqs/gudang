<?php
// mencegah direct access file PHP agar file PHP tidak bisa diakses secara langsung dari browser dan hanya dapat dijalankan ketika di include oleh file lain
// jika file diakses secara langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  // alihkan ke halaman error 404
  header('location: 404.html');
}
// jika file di include oleh file lain, tampilkan isi file
else { ?>
  <div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-4">
      <div class="page-header text-white">
        <!-- judul halaman -->
        <h4 class="page-title text-white"><i class="fas fa-clone mr-2"></i> Lokasi Barang</h4>
        <!-- breadcrumbs -->
        <ul class="breadcrumbs">
          <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a href="?module=lokasi" class="text-white">Lokasi Barang</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Entri</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="page-inner mt--5">
    <div class="card">
      <div class="card-header">
        <!-- judul form -->
        <div class="card-title">Entri Data Lokasi Barang</div>
      </div>
      <!-- form entri data -->
      <form action="modules/Lokasi/proses_entri.php" method="post" class="needs-validation" novalidate>
        <div class="card-body">
          <div class="form-group">
            <label>Lokasi Barang <span class="text-danger">*</span></label>
            <input type="text" name="nama_Lokasi" class="form-control col-lg-5" autocomplete="off" required>
            <div class="invalid-feedback">Lokasi barang tidak boleh kosong.</div>
          </div>
        </div>
        <div class="card-action">
          <!-- tombol simpan data -->
          <input type="submit" name="simpan" value="Simpan" class="btn btn-secondary btn-round pl-4 pr-4 mr-2">
          <!-- tombol kembali ke halaman data Lokasi barang -->
          <a href="?module=Lokasi" class="btn btn-default btn-round pl-4 pr-4">Batal</a>
        </div>
      </form>
    </div>
  </div>
<?php } ?>