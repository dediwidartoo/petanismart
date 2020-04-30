<div class="wrapper">
    <div class="navigation">
        <a href="<?= base_url(); ?>">Home</a>
        <i class="fa fa-caret-right"></i>
        <a>Troli</a>
    </div>
    <div class="core mt-4">
        <div class="product">
            <?php if($this->cart->total_items() > 0){ ?>
            <?php foreach($this->cart->contents() as $item): ?>
            <div class="item">
                <div class="item-main">
                    <img src="<?= base_url(); ?>assets/images/product/<?= $item['img']; ?>" alt="">
                    <a href="<?= base_url(); ?>p/<?= $item['slug']; ?>"><h2 class="title mb-0"><?= $item['name']; ?></h2></a>
                    <small class="text-muted">Jumlah: <?= $item['qty']; ?></small>
                    <h3 class="price mt-0">Rp <?= number_format($item['subtotal'],0,",","."); ?></h3>
                    <div class="clearfix"></div>
                </div>
                <a href="<?= base_url(); ?>cart/delete/<?= $item['rowid']; ?>" onclick="return confirm('Yakin ingin menghapus produk ini dari troli?')"><i class="fa fa-trash"></i></a>
            </div>
            <hr>
            <?php endforeach; ?>
            <a href="<?= base_url(); ?>cart/delete_cart" onclick="return confirm('Apakah Anda yakin akan mengosongkan Troli?')"><button class="btn btn-outline-dark">Kosongkan Troli</button></a>
            <?php }else{ ?>
                <div class="alert alert-warning">Upss. Troli masih kosong. Yuk belanja terlebih dahulu..</div>
                <br><br><br>
            <?php } ?>
        </div>
        <div class="total shadow">
            <h2 class="title">Ringkasan Belanja</h2>
            <hr>
            <div class="list">
                <p>Total Jumlah Barang</p>
                <p><?= $this->cart->total_items(); ?></p>
            </div>
            <div class="list">
                <p>Total Harga Barang</p>
                <p>Rp <?= number_format($this->cart->total(),0,",","."); ?></p>
            </div>
            <?php if($this->cart->total_items() > 0){ ?>
                <a href="<?= base_url(); ?>payment">
                    <button class="btn btn-dark btn btn-block mt-2">Lanjut ke Pembayaran</button>
                </a>
            <?php }else{ ?>
                <a href="<?= base_url(); ?>">
                    <button class="btn btn-dark btn btn-block mt-2">Belanja Dulu</button>
                </a>
            <?php } ?>
        </div>
    </div>
</div>