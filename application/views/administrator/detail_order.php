<?php echo $this->session->flashdata('upload'); ?>
<?php
$id = $invoice['province'];
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => "https://api.rajaongkir.com/starter/province?id=$id",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_HTTPHEADER => array(
    "key: ". $this->config->item('api_rajaongkir')
),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $response =  json_decode($response, true);
    $province = $response['rajaongkir']['results']['province'];
}

$id = $invoice['regency'];
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => "https://api.rajaongkir.com/starter/city?id=$id",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_HTTPHEADER => array(
    "key: ". $this->config->item('api_rajaongkir')
),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $response =  json_decode($response, true);
    $regency = $response['rajaongkir']['results']['type'] . ' ' . $response['rajaongkir']['results']['city_name'];
}

?>

<!-- Begin Page Content -->
<div class="container-fluid">
	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800 mb-2">Invoice ID = <?= $invoice['invoice_code']; ?></h1>
    <?php if($invoice['send'] == 1){ ?>
        <h3 class="text-success">Transaksi Selesai</h3>
    <?php } ?>

	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
            <a href="<?= base_url(); ?>administrator/orders" class="btn btn-sm btn-primary"><i class="fa fa-chevron-left"></i> Kembali</a>
		</div>
		<div class="card-body">
            <h3 class="lead">Data Alamat</h3>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td>Label Alamat</td>
                            <td><?= $invoice['label']; ?></td>
                        </tr>
                        <tr>
                            <td>Nama Penerima</td>
                            <td><?= $invoice['name']; ?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><?= $invoice['email']; ?></td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td><?= $invoice['telp']; ?></td>
                        </tr>
                        <tr>
                            <td>Provinsi</td>
                            <td><?= $province; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td>Kabupaten/Kota</td>
                            <td><?= $regency; ?></td>
                        </tr>
                        <tr>
                            <td>Kecamatan</td>
                            <td><?= $invoice['district']; ?></td>
                        </tr>
                        <tr>
                            <td>Desa/Kelurahan</td>
                            <td><?= $invoice['village']; ?></td>
                        </tr>
                        <tr>
                            <td>Kode Post</td>
                            <td><?= $invoice['zipcode']; ?></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td style="width: 65%"><?= $invoice['address']; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr><hr>
            <h3 class="lead">Metode Pengiriman</h3>
            <hr>
            <div class="row">
                <div class="col-md-10">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td>Ekspedisi</td>
                            <?php if($invoice['courier'] == "jne"){ ?>
                                <td>Jalur Nugraha Ekakurir (JNE)</td>
                            <?php }else if($invoice['courier'] == "pos"){ ?>
                                <td>POS Indonesia (POS)</td>
                            <?php }else if($invoice['courier'] == "tiki"){ ?>
                                <td>Citra Van Titipan Kilat (TIKI)</td>
                            <?php }else if($invoice['courier'] == "antar"){ ?>
                                <td>Diantar oleh penjual</td>
                            <?php }else{ ?>
                                <td>Jasa kurir tidak dikenal, silakan hubungi pembeli.</td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>Layanan</td>
                             <?php if($invoice['courier'] == "antar"){ ?>
                                <td>-</td>
                            <?php }else{ ?>
                                <td><?= $invoice['courier_service']; ?></td>
                            <?php } ?>
                        </tr>
                    </table>
                </div>
            </div>
		</div>
	</div>
    <div class="card shadow mb-5">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>#</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>Aksi</th>
                <?php $no=1; foreach($orders->result_array() as $data): ?>
                </tr>
                    <td><?= $no; ?></td>
                    <td><?= $data['product_name']; ?></td>
                    <td class="text-center"><?= $data['qty']; ?></td>
                    <td>Rp <?= number_format($data['price'],0,",","."); ?></td>
                    <?php $total = $data['price'] * $data['qty']; ?>
                    <td>Rp <?= number_format($total,0,",","."); ?></td>
                    <td>
                        <a href="<?= base_url(); ?>p/<?= $data['slug']; ?>" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>
                    </td>
                <tr>
                <?php $no++; endforeach; ?>
                </tr>
            </table>
            <div class="col-md-6">
                <table class="table table-borderless table-sm">
                    <tr>
                        <th>Total Harga</th>
                        <th>Rp <?= number_format($invoice['total_price'],0,",","."); ?></th>
                    </tr>
                    <tr>
                    <?php if($invoice['courier'] == "antar"){ ?>
                        <th>Biaya Ongkir (Diantar oleh penjual)</th>
                    <?php }else{ ?>
                        <th>Biaya Ongkir (<?= strtoupper($invoice['courier']); ?> <?= $invoice['courier_service']; ?>)</th>
                    <?php } ?>
                        <th>Rp <?= number_format($invoice['ongkir'],0,",","."); ?></th>
                    </tr>
                    <tr>
                        <th>Total Keseluruhan</th>
                        <th>Rp <?= number_format($invoice['total_all'],0,",","."); ?></th>
                    </tr>
                </table>
            </div>
            <hr>
            <?php if($invoice['send'] == 0 && $invoice['process'] == 0){ ?>
                <a href="<?= base_url(); ?>administrator/order/<?= $invoice['invoice_code']; ?>/delete" onclick="return confirm('Yakin ingin menolak pesanan? Aksi ini akan sekaligus menghapus pesanan. Harap hubungi pembeli jika pesanan ditolak.');" class="btn btn-sm btn-danger">Tolak dan Hapus Pesanan</a>
            <?php } ?>
            <?php if($invoice['process'] == 0 && $invoice['send'] == 0){ ?>
                <a href="<?= base_url(); ?>administrator/order/<?= $invoice['invoice_code']; ?>/process" onclick="return confirm('Yakin ingin mengubah status pesanan? Pembeli akan mendapatkan email notifikasi jika pesanan sedang diproses.');" class="btn btn-info btn-sm">Terima dan Proses Pesanan</a>
            <?php } ?>
            <?php if($invoice['process'] == 1 && $invoice['send'] == 0){ ?>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#sendingOrder">Sedang Dikirim</button>
                <a href="<?= base_url(); ?>administrator/order/<?= $invoice['invoice_code']; ?>/sending" ></a>
            <?php } ?>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<!-- Modal -->
<div class="modal fade" id="sendingOrder" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
  <form action="<?= base_url(); ?>administrator/order/<?= $invoice['invoice_code']; ?>/sending" method="post">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Kirim Resi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Pastikan Anda sudah mengirim barang kepada kurir dan mendapatkan nomor resi.</p>
        <div class="form-group">
            <label for="resi">Masukan Nomor Resi</label>
            <input type="text" required name="resi" id="resi" class="form-control" autocomplete="off">
        </div>
        <small class="text-muted">Setelah menekan tombol dibawah Anda tidak bisa mengubah nomor resi yang sudah Anda masukan.</small>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info">Yakin dan Kirim</button>
      </div>
    </div>
    </form>
  </div>
</div>
