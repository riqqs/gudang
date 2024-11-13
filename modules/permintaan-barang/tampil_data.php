<?php

// mencegah direct access file PHP agar file PHP tidak bisa diakses secara langsung dari browser dan hanya dapat dijalankan ketika di include oleh file lain
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  header('location: 404.html');
} else {
  if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 1) {
      echo '<div class="alert alert-notify alert-success alert-dismissible fade show" role="alert">
              <span data-notify="icon" class="fas fa-check"></span> 
              <span data-notify="title" class="text-success">Sukses!</span> 
              <span data-notify="message">Data permintaan barang berhasil disimpan.</span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
    } elseif ($_GET['pesan'] == 2) {
      echo '<div class="alert alert-notify alert-success alert-dismissible fade show" role="alert">
              <span data-notify="icon" class="fas fa-check"></span> 
              <span data-notify="title" class="text-success">Sukses!</span> 
              <span data-notify="message">Data permintaan barang berhasil dihapus.</span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
    }
  }
?>
<div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-45">
        <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
            <div class="page-header text-white">
                <h4 class="page-title text-white"><i class="fas fa-sign-in-alt mr-2"></i> Permintaan Barang</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
                    <li class="separator"><i class="flaticon-right-arrow"></i></li>
                    <li class="nav-item"><a href="?module=barang_masuk" class="text-white">Permintaan Barang</a></li>
                    <li class="separator"><i class="flaticon-right-arrow"></i></li>
                    <li class="nav-item"><a>Data</a></li>
                </ul>
            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <a href="?module=form_entri_permintaan_barang" class="btn btn-secondary btn-round">
                    <span class="btn-label"><i class="fa fa-plus mr-2"></i></span> Entri Data
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Data Permintaan Barang</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">ID Permintaan</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($mysqli, "SELECT a.id_permintaan, a.tanggal, a.keterangan, a.status
                                                      FROM tbl_permintaan_barang as a 
                                                      ORDER BY a.id_permintaan ASC") 
                        or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                        
                        while ($data = mysqli_fetch_assoc($query)) {
                            $status = $data['status']; // Get status for each request
                            ?>
                        <tr>
                            <td width="10" class="text-center"><?php echo $no++; ?></td>
                            <td width="70" class="text-center"><?php echo $data['id_permintaan']; ?></td>
                            <td width="70" class="text-center"><?php echo date('d-m-Y', strtotime($data['tanggal'])); ?>
                            </td>
                            <td width="220"><?php echo $data['keterangan']; ?></td>
                            <td width="30" class="text-center"><?php echo $data['status']; ?></td>
                            <td width="60" class="text-center">
                                <div>
                                    <a href="?module=tampil_detail_permintaan&id=<?php echo $data['id_permintaan']; ?>"
                                        class="btn btn-icon btn-round btn-primary btn-sm mr-md-1" data-toggle="tooltip"
                                        data-placement="top" title="Detail">
                                        <i class="fas fa-clone fa-sm"></i>
                                    </a>

                                    <!-- Button to trigger the modal for 'Proses Perpindahan' -->
                                    <button type="button"
                                        class="btn btn-icon btn-round btn-sm mr-md-1 <?php echo ($status == 'diterima') ? 'btn-secondary' : 'btn-success'; ?>"
                                        data-toggle="modal" data-target="#confirmTransferModal"
                                        data-id_permintaan="<?php echo $data['id_permintaan']; ?>" data-toggle="tooltip"
                                        data-placement="top" title="Proses Perpindahan"
                                        <?php echo ($status == 'diterima') ? 'disabled' : ''; ?>>
                                        <i
                                            class="fas <?php echo ($status == 'diterima') ? 'fa-check-circle' : 'fa-chevron-circle-right'; ?> fa-sm"></i>
                                    </button>


                                    <!-- Delete Button -->
                                    <a href="modules/permintaan-barang/proses_hapus.php?id=<?php echo $data['id_permintaan']; ?>"
                                        onclick="return confirm('Anda yakin ingin menghapus data permintaan barang <?php echo $data['id_permintaan']; ?>?')"
                                        class="btn btn-icon btn-round btn-danger btn-sm" data-toggle="tooltip"
                                        data-placement="top" title="Hapus">
                                        <i class="fas fa-trash fa-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal for Confirmation -->
<div class="modal fade" id="confirmTransferModal" tabindex="-1" role="dialog"
    aria-labelledby="confirmTransferModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmTransferModalLabel">Konfirmasi Tanggal Perpindahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="transferForm" method="POST" action="modules/permintaan-barang/proses_perpindahan.php">
                    <input type="hidden" id="id_permintaan_modal" name="id_permintaan" value="">
                    <div class="form-group">
                        <label for="tanggal_transfer">Tanggal Perpindahan</label>
                        <input type="date" class="form-control" id="tanggal_transfer" name="tanggal_transfer" required>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success">Proses Perpindahan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<script>
// When the modal is triggered, set the id_permintaan value
$('#confirmTransferModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var id_permintaan = button.data('id_permintaan'); // Extract info from data-* attributes

    var modal = $(this);
    modal.find('#id_permintaan_modal').val(id_permintaan);
});
</script>