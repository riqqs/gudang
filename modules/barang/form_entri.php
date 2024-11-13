<?php
// Prevent direct access to the PHP file
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  header('location: 404.html'); // Redirect to 404 page if accessed directly
} else { ?>
  <div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-4">
      <div class="page-header text-white">
        <h4 class="page-title text-white"><i class="fas fa-clone mr-2"></i> Barang</h4>
        <ul class="breadcrumbs">
          <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a href="?module=barang" class="text-white">Barang</a></li>
          <li class="separator"><i class="flaticon-right-arrow"></i></li>
          <li class="nav-item"><a>Entri</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div class="page-inner mt--5">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Entri Data Barang</div>
      </div>
      <form action="modules/barang/proses_entri.php" method="post" class="needs-validation" novalidate>
        <div class="card-body">
          <div class="row">
            <div class="col-md-7">
              <div class="form-group">
                <?php
                $query = mysqli_query($mysqli, "SELECT RIGHT(id_barang,4) as nomor FROM tbl_barang ORDER BY id_barang DESC LIMIT 1")
                                                or die('Error on query: ' . mysqli_error($mysqli));
                $rows = mysqli_num_rows($query);

                if ($rows != 0) {
                  $data = mysqli_fetch_assoc($query);
                  $nomor_urut = $data['nomor'] + 1;
                } else {
                  $nomor_urut = 1;
                }

                $id_barang = "B" . str_pad($nomor_urut, 4, "0", STR_PAD_LEFT);
                ?>
                <label>ID Barang <span class="text-danger">*</span></label>
                <input type="text" name="id_barang" class="form-control" value="<?php echo $id_barang; ?>" readonly>
              </div>

              <div class="form-group">
                <label>Material Name <span class="text-danger">*</span></label>
                <input type="text" name="nama_barang" class="form-control" autocomplete="off" required>
                <div class="invalid-feedback">Material name tidak boleh kosong.</div>
              </div>

              <div class="form-group">
                <label>Material Number <span class="text-danger">*</span></label>
                <input type="text" name="material_number" class="form-control" autocomplete="off" required>
                <div class="invalid-feedback">Material number tidak boleh kosong.</div>
              </div>

              <div class="form-group">
                <label>Jenis Barang <span class="text-danger">*</span></label>
                <select name="jenis" class="form-control chosen-select" autocomplete="off" required>
                  <option selected disabled value="">-- Pilih --</option>
                  <?php
                  $query_jenis = mysqli_query($mysqli, "SELECT * FROM tbl_jenis ORDER BY nama_jenis ASC")
                                                        or die('Error on query: ' . mysqli_error($mysqli));
                  while ($data_jenis = mysqli_fetch_assoc($query_jenis)) {
                    echo "<option value='$data_jenis[id_jenis]'>$data_jenis[nama_jenis]</option>";
                  }
                  ?>
                </select>
                <div class="invalid-feedback">Jenis Barang tidak boleh kosong.</div>
              </div>

              <div class="form-group">
                <label>Stok Minimum <span class="text-danger">*</span></label>
                <input type="text" name="stok_minimum" class="form-control" autocomplete="off" onKeyPress="return goodchars(event,'0123456789',this)" required>
                <div class="invalid-feedback">Stok minimum tidak boleh kosong.</div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-action">
          <input type="submit" name="simpan" value="Simpan" class="btn btn-secondary btn-round pl-4 pr-4 mr-2">
          <a href="?module=barang" class="btn btn-default btn-round pl-4 pr-4">Batal</a>
        </div>
      </form>
    </div>
  </div>
<?php } ?>
