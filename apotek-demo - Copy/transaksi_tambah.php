<?php
include 'config/database.php';

// Bagian Logika PHP untuk menyimpan data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data utama dari form
    $id_customer = intval($_POST['id_customer']);
    $id_perusahaan = intval($_POST['id_perusahaan']);
    $no_faktur = "INV-" . date("YmdHis"); // Buat nomor faktur unik
    $tgl_faktur = $_POST['tgl_faktur'];
    $metode_bayar = htmlspecialchars($_POST['metode_bayar']);
    // Ambil nilai mentah dari input hidden
    $grand_total = floatval($_POST['grand_total_raw']);
    $bayar = floatval($_POST['bayar']);
    $kembali = floatval($_POST['kembali_raw']);

    // Ambil data item produk (dalam bentuk array)
    $id_produk_arr = $_POST['id_produk'];
    $harga_arr = $_POST['harga'];
    $qty_arr = $_POST['qty'];
    $subtotal_arr = $_POST['subtotal'];

    // Mulai transaksi
    mysqli_begin_transaction($connection);

    try {
        // 1. Simpan data ke tabel 'faktur'
        $query_faktur = "INSERT INTO faktur (no_faktur, tgl_faktur, id_customer, id_perusahaan, metode_bayar, grand_total, bayar, kembali) 
                         VALUES ('$no_faktur', '$tgl_faktur', $id_customer, $id_perusahaan, '$metode_bayar', $grand_total, $bayar, $kembali)";
        mysqli_query($connection, $query_faktur);
        
        // Dapatkan ID dari faktur yang baru saja disimpan
        $id_faktur_baru = mysqli_insert_id($connection);

        // 2. Looping untuk menyimpan setiap item ke 'detail_faktur' dan update stok
        for ($i = 0; $i < count($id_produk_arr); $i++) {
            $id_produk = intval($id_produk_arr[$i]);
            $harga = floatval($harga_arr[$i]);
            $qty = intval($qty_arr[$i]);
            $subtotal = floatval($subtotal_arr[$i]);

            // Simpan ke detail_faktur
            $query_detail = "INSERT INTO detail_faktur (id_faktur, id_produk, qty, harga, subtotal)
                             VALUES ($id_faktur_baru, $id_produk, $qty, $harga, $subtotal)";
            mysqli_query($connection, $query_detail);

            // Update stok produk
            $query_stok = "UPDATE produk SET stock = stock - $qty WHERE id_produk = $id_produk";
            mysqli_query($connection, $query_stok);
        }

        // Jika semua berhasil, commit transaksi
        mysqli_commit($connection);
        
        // Redirect ke halaman cetak faktur
        header("Location: transaksi_cetak.php?id=" . $id_faktur_baru);
        exit();

    } catch (mysqli_sql_exception $exception) {
        // Jika ada error, batalkan semua perubahan
        mysqli_rollback($connection);
        die("Transaksi gagal: " . $exception->getMessage());
    }
}

// Bagian untuk menampilkan halaman (form)
include 'auth_check.php';
include 'templates/header.php';
include 'templates/sidebar.php';

// Ambil data untuk dropdown
$customers = mysqli_query($connection, "SELECT * FROM customer");
$perusahaan = mysqli_query($connection, "SELECT * FROM perusahaan");
$products = mysqli_query($connection, "SELECT * FROM produk WHERE stock > 0");
?>

<h1>Buat Transaksi Baru</h1>

<form action="transaksi_tambah.php" method="POST" id="form-transaksi">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div>
            <label for="id_perusahaan">Nama Perusahaan/Apotek:</label>
            <select name="id_perusahaan" id="id_perusahaan" required style="width:100%; padding: 8px;">
                <?php while ($p = mysqli_fetch_assoc($perusahaan)) { echo "<option value='{$p['id_perusahaan']}'>{$p['nama_perusahaan']}</option>"; } ?>
            </select>
        </div>
        <div>
            <label for="id_customer">Customer:</label>
            <select name="id_customer" id="id_customer" required style="width:100%; padding: 8px;">
                <?php while ($c = mysqli_fetch_assoc($customers)) { echo "<option value='{$c['id_customer']}'>{$c['nama_customer']}</option>"; } ?>
            </select>
        </div>
        <div>
            <label for="tgl_faktur">Tanggal:</label>
            <input type="date" name="tgl_faktur" id="tgl_faktur" required value="<?php echo date('Y-m-d'); ?>" style="width:100%; padding: 8px;">
        </div>
    </div>
    
    <hr>
    
    <h3>Pilih Produk</h3>
    <div style="display: grid; grid-template-columns: 3fr 1fr 1fr; gap: 10px; align-items: flex-end;">
        <div>
            <label for="produk-list">Produk</label>
            <select id="produk-list" style="width:100%; padding: 8px;">
                <option value="">-- Pilih Produk --</option>
                <?php mysqli_data_seek($products, 0); // Reset pointer
                      while ($prod = mysqli_fetch_assoc($products)) {
                    echo "<option value='{$prod['id_produk']}' data-harga='{$prod['harga']}' data-stok='{$prod['stock']}'>{$prod['nama_produk']} (Stok: {$prod['stock']})</option>";
                } ?>
            </select>
        </div>
        <div>
            <label for="qty">Jumlah</label>
            <input type="number" id="qty" min="1" value="1" style="width:100%; padding: 8px;">
        </div>
        <div>
            <button type="button" id="btn-tambah-produk" class="btn btn-primary">Tambah</button>
        </div>
    </div>

    <hr>

    <h3>Keranjang</h3>
    <table id="keranjang">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="keranjang-items">
            </tbody>
    </table>

    <hr>

    <div style="width: 300px; float: right;">
        <input type="hidden" name="grand_total_raw" id="grand_total_raw" value="0">
        <input type="hidden" name="kembali_raw" id="kembali_raw" value="0">

        <div style="display:grid; grid-template-columns: 1fr 2fr; margin-bottom: 10px;">
            <label for="grand_total_display">Grand Total</label>
            <input type="text" id="grand_total_display" readonly style="padding: 8px; text-align:right; background-color: #e9ecef;">
        </div>
        <div style="display:grid; grid-template-columns: 1fr 2fr; margin-bottom: 10px;">
            <label for="metode_bayar">Metode Bayar</label>
            <select name="metode_bayar" id="metode_bayar" required style="padding: 8px;">
                <option value="Tunai">Tunai</option>
                <option value="Transfer">Transfer</option>
            </select>
        </div>
        <div style="display:grid; grid-template-columns: 1fr 2fr; margin-bottom: 10px;">
            <label for="bayar">Bayar</label>
            <input type="number" name="bayar" id="bayar" required style="padding: 8px; text-align:right;" min="0">
        </div>
        <div style="display:grid; grid-template-columns: 1fr 2fr;">
            <label for="kembali_display">Kembali</label>
            <input type="text" id="kembali_display" readonly style="padding: 8px; text-align:right; background-color: #e9ecef;">
        </div>
    </div>
    <div style="clear:both;"></div>
    <button type="submit" class="btn btn-primary" style="margin-top:20px; padding: 15px 30px; font-size: 18px;">Simpan Transaksi</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnTambah = document.getElementById('btn-tambah-produk');
    const produkList = document.getElementById('produk-list');
    const qtyInput = document.getElementById('qty');
    const keranjangItems = document.getElementById('keranjang-items');
    
    // Elemen input yang diubah
    const grandTotalDisplay = document.getElementById('grand_total_display');
    const grandTotalRaw = document.getElementById('grand_total_raw');
    const bayarInput = document.getElementById('bayar');
    const kembaliDisplay = document.getElementById('kembali_display');
    const kembaliRaw = document.getElementById('kembali_raw');

    // Fungsi untuk menambah produk ke keranjang
    btnTambah.addEventListener('click', function() {
        const selectedOption = produkList.options[produkList.selectedIndex];
        if (!selectedOption.value) {
            alert('Pilih produk terlebih dahulu!');
            return;
        }

        const id_produk = selectedOption.value;
        const nama_produk = selectedOption.text.split(' (Stok:')[0];
        const harga = parseFloat(selectedOption.dataset.harga);
        const stok = parseInt(selectedOption.dataset.stok);
        const qty = parseInt(qtyInput.value);

        if (qty > stok) {
            alert('Jumlah melebihi stok yang tersedia!');
            return;
        }

        const subtotal = harga * qty;

        const row = `
            <tr>
                <td>
                    ${nama_produk}
                    <input type="hidden" name="id_produk[]" value="${id_produk}">
                </td>
                <td>${formatRupiah(harga)}</td>
                <td>
                    <input type="hidden" name="qty[]" value="${qty}">
                    <input type="hidden" name="harga[]" value="${harga}">
                    ${qty}
                </td>
                <td>
                    <input type="hidden" name="subtotal[]" class="subtotal-item" value="${subtotal}">
                    ${formatRupiah(subtotal)}
                </td>
                <td><button type="button" class="btn btn-danger btn-hapus-item">Hapus</button></td>
            </tr>
        `;
        keranjangItems.insertAdjacentHTML('beforeend', row);
        updateGrandTotal();
    });

    // Fungsi untuk menghapus item dari keranjang
    keranjangItems.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('btn-hapus-item')) {
            e.target.closest('tr').remove();
            updateGrandTotal();
        }
    });

    // #################### PERUBAHAN DI SINI ####################
    // Fungsi untuk mengupdate grand total
    function updateGrandTotal() {
        let total = 0;
        const subtotals = document.querySelectorAll('.subtotal-item');
        subtotals.forEach(input => {
            total += parseFloat(input.value);
        });
        
        // Update input display (yang dilihat user)
        grandTotalDisplay.value = formatRupiah(total);
        // Update input hidden (yang dikirim ke server)
        grandTotalRaw.value = total;

        updateKembalian();
    }

    // Fungsi untuk menghitung kembalian
    bayarInput.addEventListener('input', updateKembalian);

    function updateKembalian() {
        const grandTotal = parseFloat(grandTotalRaw.value) || 0;
        const bayar = parseFloat(bayarInput.value) || 0;
        const kembali = bayar - grandTotal;
        
        // Update input display
        kembaliDisplay.value = formatRupiah(kembali);
        // Update input hidden
        kembaliRaw.value = kembali;
    }
    
    // Fungsi format Rupiah
    function formatRupiah(angka) {
        if (isNaN(angka)) return "Rp 0";
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }
    // #################### AKHIR PERUBAHAN ####################
});
</script>


<?php
include 'templates/footer.php';
?>