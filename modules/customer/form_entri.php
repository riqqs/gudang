<?php
// mencegah direct access file PHP agar file PHP tidak bisa diakses secara langsung dari browser dan hanya dapat dijalankan ketika di include oleh file lain
// jika file diakses secara langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  // alihkan ke halaman error 404
  header('location: 404.html');
}
// jika file di include oleh file lain, tampilkan isi file
else { 
  // Query untuk mendapatkan ID customer terakhir
  $query = mysqli_query($mysqli, "SELECT RIGHT(id_customer,7) as nomor FROM tbl_customer ORDER BY id_customer DESC LIMIT 1")
  or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));

  $rows = mysqli_num_rows($query);

  // Menambah 1 pada nomor urut terakhir untuk ID baru
  $nomor_urut = ($rows <> 0) ? (int) mysqli_fetch_assoc($query)['nomor'] + 1 : 1;

  // Format ID baru, misalnya 'CUST-0000001'
  $id_customer = "CUST-" . str_pad($nomor_urut, 7, "0", STR_PAD_LEFT);
  ?>
  <div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-4">
      <div class="page-header text-white">
        <!-- judul halaman -->
        <h4 class="page-title text-white"><i class="fas fa-users mr-2"></i> Customer</h4>
        <!-- breadcrumbs -->
        <ul class="breadcrumbs">
          <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a href="?module=customer" class="text-white">Customer</a></li>
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
        <div class="card-title">Entri Data Customer</div>
      </div>
      <!-- form entri data -->
      <form action="modules/customer/proses_entri.php" method="post" class="needs-validation" novalidate>
        <div class="card-body">
          <div class="form-group">
            <label>ID Customer <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="text" name="id_customer" class="form-control" value="<?php echo $id_customer; ?>" readonly>
              <div class="input-group-append">
                <button type="button" id="saveTransaction" class="btn btn-primary">Simpan</button>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Nama <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control col-lg-5" autocomplete="off" required>
            <div class="invalid-feedback">Nama customer tidak boleh kosong.</div>
          </div>
          <div class="form-group">
            <label>Telepon <span class="text-danger">*</span></label>
            <input type="text" name="telepon" class="form-control col-lg-5" autocomplete="off" required>
            <div class="invalid-feedback">Telepon customer tidak boleh kosong.</div>
          </div>
          <div class="form-group">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control col-lg-5" autocomplete="off" required>
            <div class="invalid-feedback">Email customer tidak boleh kosong.</div>
          </div>
          <div class="form-group">
            <label>Alamat <span class="text-danger">*</span></label>
            <textarea name="alamat" class="form-control col-lg-5" rows="3" required></textarea>
            <div class="invalid-feedback">Alamat customer tidak boleh kosong.</div>
          </div>
        </div>
        <div class="card-action">
          <!-- tombol simpan data -->
          <input type="submit" name="simpan" value="Simpan" class="btn btn-secondary btn-round pl-4 pr-4 mr-2">
          <!-- tombol kembali ke halaman data customer -->
          <a href="?module=customer" class="btn btn-default btn-round pl-4 pr-4">Batal</a>
        </div>
      </form>
    </div>
  </div>
<?php } ?>
