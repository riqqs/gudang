<?php
// Cegah akses langsung ke file PHP
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    if (isset($_GET['pesan'])) {
        if ($_GET['pesan'] == 1) {
            echo '<div class="alert alert-notify alert-success alert-dismissible fade show" role="alert">
                    <span data-notify="icon" class="fas fa-check"></span> 
                    <span data-notify="title" class="text-success">Sukses!</span> 
                    <span data-notify="message">Data penjualan barang berhasil disimpan.</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        } elseif ($_GET['pesan'] == 2) {
            echo '<div class="alert alert-notify alert-success alert-dismissible fade show" role="alert">
                    <span data-notify="icon" class="fas fa-check"></span> 
                    <span data-notify="title" class="text-success">Sukses!</span> 
                    <span data-notify="message">Data penjualan barang berhasil dihapus.</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        } elseif ($_GET['pesan'] == 3) {
            echo '<div class="alert alert-notify alert-success alert-dismissible fade show" role="alert">
                    <span data-notify="icon" class="fas fa-check"></span> 
                    <span data-notify="title" class="text-success">Sukses!</span> 
                    <span data-notify="message">Jumlah barang berhasil diperbarui.</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
    }

    $id_penjualan = isset($_GET['id']) ? $_GET['id'] : null;

    if (!$id_penjualan) {
        echo "<div class='alert alert-danger'>ID penjualan tidak ditemukan.</div>";
        exit;
    }

    $query_penjualan = mysqli_query($mysqli, "SELECT * FROM tbl_penjualan_barang WHERE id_penjualan='$id_penjualan'");
    if (!$query_penjualan) {
        die("Query penjualan gagal: " . mysqli_error($mysqli));
    }

    $data_penjualan = mysqli_fetch_assoc($query_penjualan);

    if (!$data_penjualan) {
        echo "<div class='alert alert-warning'>Data penjualan barang tidak ditemukan untuk ID: $id_penjualan</div>";
        exit;
    }

    $query_detail = mysqli_query($mysqli, "SELECT d.id_penjualan_detail, d.id_barang, d.jumlah, b.nama_barang 
                                           FROM tbl_penjualan_detail AS d 
                                           JOIN tbl_barang AS b ON d.id_barang = b.id_barang
                                           WHERE d.id_penjualan = '$id_penjualan'");
    if (!$query_detail) {
        die("Query detail penjualan gagal: " . mysqli_error($mysqli));
    }
?>

<div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-45">
        <div class="page-header text-white">
            <h4 class="page-title text-white"><i class="fas fa-sign-in-alt mr-2"></i> penjualan Barang</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a href="?module=penjualan_barang" class="text-white">penjualan Barang</a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item">Detail <?php echo $data_penjualan['id_penjualan']; ?></li>
            </ul>
        </div>
    </div>
</div>
<div class="page-inner mt--5">
    <div class="card">
        <div class="card-header">
            <div class="card-title"><strong><?php echo $data_penjualan['id_penjualan']; ?> <span>|</span>
                    <?php echo date('d-m-Y', strtotime($data_penjualan['tanggal'])); ?></strong></div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">ID Barang</th>
                            <th class="text-center">Nama Barang</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($data_detail = mysqli_fetch_assoc($query_detail)) { ?>
                        <tr>
                            <td width="1" class="text-center"><?php echo $no; ?></td>
                            <td width="70" class="text-center"><?php echo $data_detail['id_barang']; ?></td>
                            <td width="150"><?php echo $data_detail['nama_barang']; ?></td>
                            <td width="70" class="text-center"><?php echo $data_detail['jumlah']; ?></td>
                            <td width="60" class="text-center">
                                <div>
                                    <button class="btn btn-icon btn-round btn-success btn-sm mr-md-1"
                                        data-toggle="tooltip" data-placement="top" title="Edit"
                                        onclick="openEditModal('<?php echo $data_detail['id_penjualan_detail']; ?>', '<?php echo $data_detail['jumlah']; ?>')">
                                        <i class="fas fa-pencil fa-sm"></i>
                                    </button>
                                    <a href="modules/penjualan-barang/proses_hapus_detail.php?id=<?php echo htmlspecialchars($data_detail['id_penjualan_detail']); ?>"
                                        onclick="return confirm('Anda yakin ingin menghapus barang ini?')"
                                        class="btn btn-icon btn-round btn-danger btn-sm" data-toggle="tooltip"
                                        data-placement="top" title="Hapus">
                                        <i class="fas fa-trash fa-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            $no++; 
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="modules/penjualan-barang/proses_edit.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Jumlah Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_penjualan_detail" id="edit_id_penjualan_detail">
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" id="edit_jumlah" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(id, jumlah) {
    document.getElementById('edit_id_penjualan_detail').value = id;
    document.getElementById('edit_jumlah').value = jumlah;
    $('#editModal').modal('show');
}
</script>
<?php } ?>