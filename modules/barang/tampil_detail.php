<?php
// Prevent direct access to this PHP file
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  header('location: 404.html');
} else {
  // Check if "id_barang" is set in GET parameters
  if (isset($_GET['id'])) {
    // Retrieve "id_barang" from URL
    $id_barang = $_GET['id'];

    // SQL statement to fetch data from "tbl_barang" and "tbl_jenis"
    $query = mysqli_query($mysqli, "SELECT a.id_barang, a.nama_barang, a.stok_minimum, a.stok, b.nama_jenis
                                    FROM tbl_barang as a 
                                    INNER JOIN tbl_jenis as b ON a.jenis = b.id_jenis
                                    WHERE a.id_barang = '$id_barang'")
                                    or die('Error on query: ' . mysqli_error($mysqli));
    // Fetch query data
    $data = mysqli_fetch_assoc($query);
  }
?>
<div class="panel-header bg-secondary-gradient">
  <div class="page-inner py-45">
    <div class="page-header text-white">
      <h4 class="page-title text-white"><i class="fas fa-clone mr-2"></i> Barang</h4>
      <ul class="breadcrumbs">
        <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a href="?module=barang" class="text-white">Barang</a></li>
        <li class="separator"><i class="flaticon-right-arrow"></i></li>
        <li class="nav-item"><a>Detail</a></li>
      </ul>
    </div>
    <div class="ml-md-auto py-2 py-md-0">
      <a href="?module=barang" class="btn btn-secondary btn-round">
        <span class="btn-label"><i class="far fa-arrow-alt-circle-left mr-2"></i></span> Kembali
      </a>
    </div>
  </div>
</div>

<div class="page-inner mt--5">
  <div class="row">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <div class="card-title">Detail Data Barang</div>
        </div>
        <div class="card-body">
          <table class="table table-striped">
            <tr>
              <td width="120">ID Barang</td>
              <td width="10">:</td>
              <td><?php echo $data['id_barang']; ?></td>
            </tr>
            <tr>
              <td>Nama Barang</td>
              <td>:</td>
              <td><?php echo $data['nama_barang']; ?></td>
            </tr>
            <tr>
              <td>Jenis Barang</td>
              <td>:</td>
              <td><?php echo $data['nama_jenis']; ?></td>
            </tr>
            <tr>
              <td>Stok Minimum</td>
              <td>:</td>
              <td><?php echo $data['stok_minimum']; ?></td>
            </tr>
            <tr>
              <td>Stok</td>
              <td>:</td>
              <td><?php echo $data['stok']; ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } ?>
