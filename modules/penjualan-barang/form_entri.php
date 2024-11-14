<?php
// mencegah akses langsung file PHP ini
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  header('location: 404.html');
  exit;
} else { ?>
<div id="pesan"></div>

<div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-4">
        <div class="page-header text-white">
            <h4 class="page-title text-white"><i class="fas fa-sign-in-alt mr-2"></i> Pesanan Barang</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a href="?module=penjualan_barang" class="text-white">Pesanan Barang</a></li>
                <li class="separator"><i class="flaticon-right-arrow"></i></li>
                <li class="nav-item"><a>Entri</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="row">
        <!-- Left column for entry form -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Entri Data Pesanan Barang</div>
                </div>
                <form id="entryForm" method="post" class="needs-validation" novalidate>
                    <div class="card-body">
                        <div class="form-group">
                            <?php
$query = mysqli_query($mysqli, "SELECT RIGHT(id_penjualan,7) as nomor FROM tbl_penjualan_barang ORDER BY id_penjualan DESC LIMIT 1")
  or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));

$rows = mysqli_num_rows($query);

// Cast 'nomor' as integer before adding 1
$nomor_urut = ($rows <> 0) ? (int) mysqli_fetch_assoc($query)['nomor'] + 1 : 1;

$id_penjualan = "PES-" . str_pad($nomor_urut, 7, "0", STR_PAD_LEFT);
?>
                            <label>ID Pesanan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="id_penjualan" class="form-control"
                                    value="<?php echo $id_penjualan; ?>" readonly>
                                <div class="input-group-append">
                                    <button type="button" id="saveTransaction" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tanggal <span class="text-danger">*</span></label>
                            <input type="text" name="tanggal" id="tanggal" class="form-control date-picker"
                                value="<?php echo date("Y-m-d"); ?>" required>
                            <div class="invalid-feedback">Tanggal tidak boleh kosong.</div>
                        </div>

                        <!-- Customer Selection -->
                        <div class="form-group">
                            <label>Customer <span class="text-danger">*</span></label>
                            <select id="data_customer" name="customer" class="form-control chosen-select" required>
                                <option selected disabled value="">-- Pilih --</option>
                                <?php
                  $query_customer = mysqli_query($mysqli, "SELECT id_customer, nama FROM tbl_customer ORDER BY id_customer ASC")
                    or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                  while ($data_customer = mysqli_fetch_assoc($query_customer)) {
                    echo "<option value='$data_customer[id_customer]'>$data_customer[nama]</option>";
                  }
                  ?>
                            </select>
                            <div class="invalid-feedback">Customer tidak boleh kosong.</div>
                        </div>

                        <!-- Quotation Number -->
                        <div class="form-group">
                            <label>No. Quotation <span class="text-danger">*</span></label>
                            <input type="text" name="quotation_no" class="form-control" required>
                            <div class="invalid-feedback">No. Quotation tidak boleh kosong.</div>
                        </div>

                        <div class="form-group">
                            <label>Barang <span class="text-danger">*</span></label>
                            <select id="data_barang" name="barang" class="form-control chosen-select" required>
                                <option selected disabled value="">-- Pilih --</option>
                                <?php
                  $query_barang = mysqli_query($mysqli, "SELECT id_barang, nama_barang FROM tbl_barang ORDER BY id_barang ASC")
                    or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                  while ($data_barang = mysqli_fetch_assoc($query_barang)) {
                    echo "<option value='$data_barang[id_barang]'>$data_barang[id_barang] - $data_barang[nama_barang]</option>";
                  }
                  ?>
                            </select>
                            <div class="invalid-feedback">Barang tidak boleh kosong.</div>
                        </div>

                        <div class="form-group">
                            <label>Stok yang Bisa Dijual <span class="text-danger">*</span></label>
                            <input type="text" id="data_stok" name="stok" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Pesanan <span class="text-danger">*</span></label>
                            <input type="text" id="jumlah" name="jumlah" class="form-control"
                                onKeyPress="return goodchars(event,'0123456789',this)" required>
                            <div class="invalid-feedback">Jumlah pesanan tidak boleh kosong.</div>
                        </div>

                    </div>

                    <div class="card-action">
                        <button type="button" id="addItem" class="btn btn-secondary">Tambah Barang</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right column for displaying added items -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Daftar Barang yang Dipesan</div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody id="itemList">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Pesanan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                    <textarea id="keterangan" name="keterangan" rows="4" class="form-control"
                        placeholder="Masukkan keterangan di sini..." required style="resize: none;"></textarea>
                    <div class="invalid-feedback">Keterangan wajib diisi.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmSave" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Function to update the dropdown options and disable already selected items
    function updateItemList() {
        // Get the list of items already added
        var addedItems = [];
        $('#itemList tr').each(function() {
            var id_barang = $(this).find('td:eq(0)').text();
            addedItems.push(id_barang);
        });

        // Enable all items in the dropdown and disable already added items
        $('#data_barang option').each(function() {
            var option = $(this);
            var itemId = option.val();
            if (addedItems.includes(itemId)) {
                // Disable the item if it is already added
                option.prop('disabled', true);
            } else {
                // Enable the item if it is not in the list
                option.prop('disabled', false);
            }
        });
    }

    $('#data_barang').change(function() {
        var id_barang = $('#data_barang').val();
        $.ajax({
            type: "GET",
            url: "modules/penjualan-barang/get_barang.php",
            data: { id_barang: id_barang },
            dataType: "JSON",
            success: function(data) {
                $('#data_stok').val(data.stok);
            }
        });
    });

    $('#addItem').click(function() {
        var id_barang = $('#data_barang').val();
        var jumlah = $('#jumlah').val();
        var stok = $('#data_stok').val();

        if (jumlah == "" || id_barang == "" || stok == "") {
            alert("Silakan pilih barang dan masukkan jumlah terlebih dahulu.");
            return false;
        }

        // Check if quantity exceeds available stock
        if (parseInt(jumlah) > parseInt(stok)) {
            alert("Jumlah pesanan melebihi stok yang tersedia!");
            return false;
        }

        // Add item to the list if validation passes
        var itemRow = `<tr>
                <td>${id_barang}</td>
                <td>${$('#data_barang option:selected').text()}</td>
                <td>${jumlah}</td>
                <td><button type="button" class="btn btn-danger btn-sm removeItem">Hapus</button></td>
              </tr>`;

        $('#itemList').append(itemRow);

        // Reset form fields
        $('#data_barang').val('').trigger('chosen:updated');
        $('#jumlah').val('');
        $('#data_stok').val('');

        // Update the dropdown options to disable the selected item
        updateItemList();
    });

    // Remove item from the list and update dropdown
    $(document).on('click', '.removeItem', function() {
        $(this).closest('tr').remove();
        // After removal, update the dropdown again
        updateItemList();
    });

    // Save transaction
    $('#saveTransaction').click(function() {
        var items = [];
        $('#itemList tr').each(function() {
            var row = $(this);
            var id_barang = row.find('td:eq(0)').text();
            var jumlah = row.find('td:eq(2)').text();
            items.push({
                id_barang: id_barang,
                jumlah: jumlah
            });
        });

        if (items.length === 0) {
            alert('Silakan tambahkan barang ke pesanan.');
            return false;
        }

        var formData = {
            id_penjualan: $('input[name="id_penjualan"]').val(),
            tanggal: $('input[name="tanggal"]').val(),
            customer: $('#data_customer').val(),
            quotation_no: $('input[name="quotation_no"]').val(),
            items: items,
            keterangan: $('#keterangan').val()
        };

        // Send data to save transaction (AJAX request)
        $.ajax({
    url: 'modules/penjualan-barang/proses_entri.php',
    type: 'POST',
    data: formData,
    success: function(response) {
        console.log("Response from server:", response); // Log the full response
        if (response === 'success') {
            alert('Pesanan barang berhasil disimpan!');
            location.reload();
        } else {
            alert('Gagal menyimpan pesanan barang! Response: ' + response );
        }
    },
    error: function(xhr, status, error) {
        console.error("AJAX Error:", error); // Log any error from the AJAX request
        console.error("XHR Object:", xhr);   // Log the full XHR object for more details
        console.error("Status:", status);     // Log the status of the request
        alert('Terjadi kesalahan saat menyimpan pesanan!');
    }
});

    });

    // Initial update when page loads
    updateItemList();
});
</script>

<?php } ?>