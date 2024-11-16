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
          <li class="nav-item"><a href="?module=Pesanan_barang" class="text-white">Pesanan Barang</a></li>
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
              <!-- Customer Dropdown -->
              <div class="form-group">
    <label>Pilih Customer <span class="text-danger">*</span></label>
    <select name="id_customer" id="id_customer" class="form-control">
        <option selected disabled value="">-- Pilih Customer --</option>
        <?php
        // Query untuk mengambil data customer
        $query_customer = mysqli_query($mysqli, "SELECT id_customer, nama FROM tbl_customer ORDER BY id_customer ASC")
            or die('Ada kesalahan pada query tampil data customer: ' . mysqli_error($mysqli));
        while ($data_customer = mysqli_fetch_assoc($query_customer)) {
            echo "<option value='$data_customer[id_customer]'>$data_customer[nama]</option>";
        }
        ?>
    </select>
    <div class="invalid-feedback">Customer harus dipilih.</div>
</div>


              <div class="form-group">
                <?php
                $query = mysqli_query($mysqli, "SELECT RIGHT(id_penjualan,6) as nomor FROM tbl_penjualan_barang ORDER BY id_penjualan DESC LIMIT 1")
                  or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));

                $rows = mysqli_num_rows($query);

                // Cast 'nomor' as integer before adding 1
                $nomor_urut = ($rows <> 0) ? (int) mysqli_fetch_assoc($query)['nomor'] + 1 : 1;

                $id_penjualan = "ORD-" . str_pad($nomor_urut, 6, "0", STR_PAD_LEFT);
                ?>
                <label>ID Pesanan <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="text" name="id_penjualan" class="form-control" value="<?php echo $id_penjualan; ?>" readonly>
                  <div class="input-group-append">
                    <button type="button" id="saveTransaction" class="btn btn-primary">Simpan</button>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Tanggal <span class="text-danger">*</span></label>
                <input type="text" name="tanggal" id="tanggal" class="form-control date-picker" value="<?php echo date("Y-m-d"); ?>" required>
                <div class="invalid-feedback">Tanggal tidak boleh kosong.</div>
              </div>

              <div class="form-group">
                <label>No Quotation <span class="text-danger">*</span></label>
                <input type="text" id="quotation_no" name="quotation_no" class="form-control">
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
                <label>Stok <span class="text-danger">*</span></label>
                <input type="text" id="data_stok" name="stok" class="form-control" readonly>
              </div>

              <div class="form-group">
                <label>Jumlah Pesan<span class="text-danger">*</span></label>
                <input type="text" id="jumlah" name="jumlah" class="form-control" onKeyPress="return goodchars(event,'0123456789',this)" required>
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
            <div class="card-title">Daftar Pesanan Barang</div>
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
  <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
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
            <textarea id="keterangan" name="keterangan" rows="4" class="form-control" placeholder="Masukkan keterangan di sini..." required style="resize: none;"></textarea>
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
      // Change event for data_barang
      $('#data_barang').change(function() {
        var id_barang = $('#data_barang').val();
        $.ajax({
          type: "GET",
          url: "modules/penjualan-barang/get_barang.php",
          data: { id_barang: id_barang },
          dataType: "JSON",
          success: function(result) {
            $('#data_stok').val(result.stok);
            $('#jumlah').focus();
          }
        });
      });

      // Keyup event for jumlah field
      $('#jumlah').keyup(function() {
        var stok = parseInt($('#data_stok').val());
        var jumlah = parseInt($('#jumlah').val());
        var total_stok = isNaN(stok + jumlah) ? '' : stok + jumlah;
        $('#total').val(total_stok);
      });

      // Add item to the list
      $('#addItem').click(function() {
        var id_barang = $('#data_barang').val();
        var nama_barang = $("#data_barang option:selected").text();
        var jumlah = $('#jumlah').val();

        // Prevent adding duplicate items
        if ($('#itemList tr[data-id="'+ id_barang +'"]').length > 0) {
          $('#pesan').html('<div class="alert alert-danger">Barang sudah ditambahkan.</div>');
          return;
        }

        // Validation: Check if id_barang and jumlah are valid
        if (!id_barang || !jumlah || jumlah <= 0) {
          $('#pesan').html('<div class="alert alert-warning">Silakan isi data barang dan jumlah dengan benar.</div>');
          return;
        }

        // Check if quotation_no is filled
        if (!$('#quotation_no').val()) {
          alert('No Quotation tidak boleh kosong.');
          return;
        }

        // Add item to the table
        $('#itemList').append(
          `<tr data-id="${id_barang}">
            <td>${id_barang}</td>
            <td>${nama_barang}</td>
            <td>${jumlah}</td>
            <td><button class="btn btn-danger btn-sm removeItem">Hapus</button></td>
          </tr>`
        );

        // Reset the form fields
        $('#data_barang').trigger("chosen:updated");

        // Remove item from list
        $('.removeItem').click(function() {
          $(this).closest('tr').remove();
        });
      });

      // Show modal on "Simpan" button click
      $('#saveTransaction').click(function() {
        // Ensure itemList is not empty
        if ($('#itemList').children().length === 0) {
          alert('Daftar Pesanan barang kosong. Harap tambahkan barang terlebih dahulu.');
          return;
        }

        console.log('Quotation No:', $('#quotation_no').val());  // Log the value of the input field
    if (!$('#quotation_no').val()) {
        alert('No Quotation tidak boleh kosong.');
        return; // Stop further execution if quotation_no is empty
    }

        $('#confirmationModal').modal('show');
      });

      // Datepicker configuration
      $('#tanggal').datepicker({
        format: 'yyyy-mm-dd',    // Ensure the format is yyyy-mm-dd
        autoclose: true,         // Automatically close the date picker after selecting a date
        todayHighlight: true,    // Highlight today's date
        clearBtn: true           // Show the "Clear" button
      });

      // Handle confirmation inside modal
      $('#confirmSave').click(function() {
        var keterangan = $('#keterangan').val();
        var quotation_no = $('#quotation_no').val(); // Get the value of quotation_no
        var id_customer = $('#id_customer').val(); // Get the selected customer ID

        // Validate keterangan
        if (!keterangan) {
          $('#keterangan').addClass('is-invalid');
          return;
        } else {
          $('#keterangan').removeClass('is-invalid');
        }

        // Validate quotation_no
        if (!quotation_no) {
          alert('No Quotation tidak boleh kosong.');
          return;
        }

        // Validate customer selection
        if (!id_customer) {
          alert('Customer harus dipilih.');
          return;
        }

        // Build itemList from the table rows
        var itemList = [];
        $('#itemList tr').each(function() {
          var id_barang = $(this).data('id');
          var jumlah = $(this).find('td').eq(2).text(); // Get the quantity column

          if (id_barang && jumlah) {
            itemList.push({ id_barang: id_barang, jumlah: jumlah });
          }
        });

        // Serialize form data and add itemList, quotation_no, and id_customer
        var formData = $('#entryForm').serialize() + 
                       '&keterangan=' + encodeURIComponent(keterangan) + 
                       '&quotation_no=' + encodeURIComponent(quotation_no) +  // Include quotation_no
                       '&id_customer=' + encodeURIComponent(id_customer) + // Include customer id
                       '&itemList=' + encodeURIComponent(JSON.stringify(itemList));

        $.ajax({
          url: "modules/penjualan-barang/proses_entri.php",
          type: 'POST',
          data: formData,
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              alert('Pesanan disimpan! ID: ' + response.id_penjualan);
              window.location.href = '../gudang/main.php?module=data_penjualan';
            } else {
              alert('Error: ' + response.message);
            }
          },
          error: function(xhr, status, error) {
            console.log("XHR:", xhr);
            console.log("Status:", status);
            console.log("Error:", error);
            console.log("Response Text:", xhr.responseText);
            alert('Error saving transaction: ' + xhr.responseText);
          }
        });
      });
    });
</script>


<?php } ?>
