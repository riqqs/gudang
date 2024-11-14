<?php
// mencegah direct access file PHP agar file PHP tidak bisa diakses secara langsung dari browser dan hanya dapat dijalankan ketika di include oleh file lain
// jika file diakses secara langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  // alihkan ke halaman error 404
  header('location: 404.html');
}
// jika file di include oleh file lain, tampilkan isi fil
?>
  <div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-5">
      <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
        <div class="page-header text-white">
          <!-- judul halaman -->
          <h4 class="page-title text-white"><i class="fas fa-home mr-2"></i> Dashboard</h4>
        </div>
      </div>
    </div>
  </div>
  <div class="page-inner mt--5">
    <div class="row row-card-no-pd mt--2">
      <!-- menampilkan informasi jumlah data barang -->
      <div class="col-sm-12 col-md-4">
        <div class="card card-stats card-round">
          <div class="card-body ">
            <div class="row">
              <div class="col-5">
                <div class="icon-big2 text-center">
                  <i class="flaticon-box-2 text-secondary"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">Data Barang</p>
                  <?php
                  // sql statement untuk menampilkan jumlah data pada tabel "tbl_barang"
                  $query = mysqli_query($mysqli, "SELECT * FROM tbl_barang")
                                                  or die('Ada kesalahan pada query jumlah data barang : ' . mysqli_error($mysqli));
                  // ambil jumlah data dari hasil query
                  $jumlah_barang = mysqli_num_rows($query);
                  ?>
                  <!-- tampilkan data -->
                  <h4 class="card-title"><?php echo number_format($jumlah_barang, 0, '', '.'); ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- menampilkan informasi jumlah data barang masuk -->
      <div class="col-sm-12 col-md-4">
        <div class="card card-stats card-round">
          <div class="card-body ">
            <div class="row">
              <div class="col-5">
                <div class="icon-big2 text-center">
                  <i class="flaticon-inbox text-success"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">Permintaan Sedang Dikirim</p>
                  <?php
                  // sql statement untuk menampilkan jumlah data pada tabel "tbl_barang_masuk"
                  $query = mysqli_query($mysqli, "SELECT * FROM tbl_permintaan_barang")
                                                  or die('Ada kesalahan pada query jumlah data permintaan barang : ' . mysqli_error($mysqli));
                  // ambil jumlah data dari hasil query
                  $jumlah_barang_masuk = mysqli_num_rows($query);
                  ?>
                  <!-- tampilkan data -->
                  <h4 class="card-title"><?php echo number_format($jumlah_barang_masuk, 0, '', '.'); ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- menampilkan informasi jumlah data barang keluar -->
      <div class="col-sm-12 col-md-4">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big2 text-center">
                  <i class="flaticon-archive text-warning"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">Data Perpindahan Barang</p>
                  <?php
                  // sql statement untuk menampilkan jumlah data pada tabel "tbl_barang_keluar"
                  $query = mysqli_query($mysqli, "SELECT * FROM tbl_perpindahan_barang")
                                                  or die('Ada kesalahan pada query jumlah data barang keluar : ' . mysqli_error($mysqli));
                  // ambil jumlah data dari hasil query
                  $jumlah_barang_keluar = mysqli_num_rows($query);
                  ?>
                  <!-- tampilkan data -->
                  <h4 class="card-title"><?php echo number_format($jumlah_barang_keluar, 0, '', '.'); ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    
    
    <!-- menampilkan informasi stok barang yang telah mencapai batas minimum -->
    <div class="card">
      <div class="card-header">
        <!-- judul tabel -->
        <div class="card-title"><i class="fas fa-info-circle mr-2"></i> Stok barang telah mencapai batas minimum</div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <!-- tabel untuk menampilkan data dari database -->
          <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th class="text-center">No.</th>  
                <th class="text-center">Material Name</th>
                <th class="text-center">Material Number</th>
                <th class="text-center">Jenis Barang</th>
                <th class="text-center">Stok</th>
            
              </tr>
            </thead>
            <tbody>
              <?php
              // variabel untuk nomor urut tabel
              $no = 1;
              // sql statement untuk menampilkan data dari tabel "tbl_barang", tabel "tbl_jenis", dan tabel "tbl_satuan" berdasarkan "stok"
              $query = mysqli_query($mysqli, "SELECT a.id_barang, a.nama_barang, a.material_number, a.jenis, a.stok_minimum, a.stok, b.nama_jenis
                                FROM tbl_barang as a 
                                INNER JOIN tbl_jenis as b  
                                ON a.jenis = b.id_jenis 
                                WHERE a.stok <= a.stok_minimum 
                                ORDER BY a.id_barang ASC")
                                or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));

              // ambil data hasil query
              while ($data = mysqli_fetch_assoc($query)) { ?>
                <!-- tampilkan data -->
                <tr>
                  <td width="5" class="text-center"><?php echo $no++; ?></td>
                  <td width="200" class="text-left"><?php echo $data['nama_barang']; ?></td>
                  <td width="80" class="text-center"><?php echo $data['material_number']; ?></td>
                  <td width="50" class="text-center"><?php echo $data['nama_jenis']; ?></td>
                  <td width="20" class="text-center"><span class="badge badge-warning"><?php echo $data['stok']; ?></span></td>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
