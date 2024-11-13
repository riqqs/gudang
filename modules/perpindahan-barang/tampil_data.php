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
              <span data-notify="message">Data Perpindahan barang berhasil disimpan.</span>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
    } elseif ($_GET['pesan'] == 2) {
      echo '<div class="alert alert-notify alert-success alert-dismissible fade show" role="alert">
              <span data-notify="icon" class="fas fa-check"></span> 
              <span data-notify="title" class="text-success">Sukses!</span> 
              <span data-notify="message">Data Perpindahan barang berhasil dihapus.</span>
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
                <h4 class="page-title text-white"><i class="fas fa-sign-in-alt mr-2"></i> Perpindahan Barang</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
                    <li class="separator"><i class="flaticon-right-arrow"></i></li>
                    <li class="nav-item"><a href="?module=barang_masuk" class="text-white">Perpindahan Barang</a></li>
                    <li class="separator"><i class="flaticon-right-arrow"></i></li>
                    <li class="nav-item"><a>Data</a></li>
                </ul>
            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <a href="?module=form_entri_Perpindahan_barang" class="btn btn-secondary btn-round">
                    <span class="btn-label"><i class="fa fa-plus mr-2"></i></span> Entri Data
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Data Perpindahan Barang</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">ID Perpindahan</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($mysqli, "SELECT a.id_perpindahan, a.tanggal, b.status
                                FROM tbl_perpindahan_barang AS a
                                JOIN tbl_permintaan_barang AS b ON a.id_permintaan = b.id_permintaan
                                ORDER BY a.id_perpindahan ASC")

                        or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                        
                        while ($data = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td width="10" class="text-center"><?php echo $no++; ?></td>
                            <td width="70" class="text-center"><?php echo $data['id_perpindahan']; ?></td>
                            <td width="70" class="text-center"><?php echo date('d-m-Y', strtotime($data['tanggal'])); ?></td>
                            
                            <td width="30" class="text-center"><?php echo $data['status']; ?></td>
                            <td width="60" class="text-center">
                                <div>
                                    <a href="?module=tampil_detail_Perpindahan&id=<?php echo $data['id_perpindahan']; ?>"
                                       class="btn btn-icon btn-round btn-primary btn-sm mr-md-1" data-toggle="tooltip"
                                       data-placement="top" title="Detail">
                                        <i class="fas fa-clone fa-sm"></i>
                                    </a>
                                    <!-- Button to trigger the modal -->
                                    <button type="button" class="btn btn-icon btn-round btn-success btn-sm mr-md-1"
                                            data-toggle="modal" data-target="#confirmTransferModal"
                                            data-id_Perpindahan="<?php echo $data['id_perpindahan']; ?>"
                                            data-toggle="tooltip" data-placement="top" title="Proses Perpindahan">
                                        <i class="fas fa-chevron-circle-right fa-sm"></i>
                                    </button>
                                    <a href="modules/Perpindahan-barang/proses_hapus.php?id=<?php echo $data['id_perpindahan']; ?>"
                                       onclick="return confirm('Anda yakin ingin menghapus data Perpindahan barang <?php echo $data['id_perpindahan']; ?>?')"
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
<div class="modal fade" id="confirmTransferModal" tabindex="-1" role="dialog" aria-labelledby="confirmTransferModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmTransferModalLabel">Konfirmasi Tanggal Perpindahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="transferForm" method="POST" action="modules/Perpindahan-barang/proses_perpindahan.php">
                    <input type="hidden" id="id_Perpindahan_modal" name="id_Perpindahan" value="">
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
    // When the modal is triggered, set the id_Perpindahan value
    $('#confirmTransferModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id_Perpindahan = button.data('id_Perpindahan'); // Extract info from data-* attributes
        
        var modal = $(this);
        modal.find('#id_Perpindahan_modal').val(id_Perpindahan);
    });
</script>
