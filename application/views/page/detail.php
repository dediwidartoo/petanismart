<div class="wrapper">
    <div class="navigation">
        <a href="<?= base_url(); ?>">Home</a>
        <i class="fa fa-caret-right"></i>
        <a href="<?= base_url(); ?>c/<?= $product['slug']; ?>"><?= $product['name']; ?></a>
        <i class="fa fa-caret-right"></i>
        <a><?= $product['title']; ?></a>
    </div>
    <?php $setting = $this->db->get('settings')->row_array(); ?>
    <div class="top">
        <div class="main-top">
            <div class="img">
                <img src="<?= base_url(); ?>assets/images/product/<?= $product['img']; ?>" alt="produk">
            </div>
            <div class="ket">
                <h1 class="title"><?= $product['title']; ?></h1>
                <p class="subtitle">Terjual <?= $product['transaction']; ?> Produk &bull; <?= $product['viewer']; ?>x Dilihat</p>
                <hr>
                <table>
                    <?php if($setting['promo'] == 1){ ?>
                    <?php if($product['promo_price'] == 0){ ?>
                        <tr>
                            <td class="t">Harga</td>
                            <td class="price">Rp <?= str_replace(",",".",number_format($product['price'])); ?></td>
                        </tr>
                    <?php }else{ ?>
                        <tr>
                            <td class="t">Harga</td>
                            <td class="oldPrice">Rp <?= str_replace(",",".",number_format($product['price'])); ?></td>
                        </tr>
                        <tr class="newPrice">
                            <td></td>
                            <td class="price">Rp <?= str_replace(",",".",number_format($product['promo_price'])); ?></td>
                        </tr>
                    <?php } ?>
                    <?php }else{ ?>
                        <tr>
                            <td class="t">Harga</td>
                            <td class="price">Rp <?= str_replace(",",".",number_format($product['price'])); ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="t">Kondisi</td>
                        <?php if($product['condit'] == 1){ ?>
                            <td>Baru</td>
                        <?php }else{ ?>
                            <td>Bekas</td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td class="t">Berat</td>
                        <td><?= $product['weight']; ?> gram</td>
                    </tr>
                    <tr>
                        <td class="t">Stok</td>
                        <td><?= $product['stock']; ?> produk</td>
                    </tr>
                    <tr>
                        <?php if($setting['promo'] == 1){ ?>
                        <?php if($product['promo_price'] == 0){ ?>
                            <?php $priceP = $product['price']; ?>
                        <?php }else{ ?>
                            <?php $priceP = $product['promo_price']; ?>
                        <?php } ?>
                        <?php }else{ ?>
                            <?php $priceP = $product['price']; ?>
                        <?php } ?>
                        <td class="t">Jumlah</td>
                        <td>
                            <button onclick="minusProduct(<?= $priceP; ?>)">-</button><!--
                        --><input disabled type="text" value="1" id="qtyProduct" class="valueJml"><!--
                        --><button onclick="plusProduct(<?= $priceP; ?>, <?= $product['stock']; ?>)">+</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="t">Total</td>
                        <td>Rp <span id="detailTotalPrice"><?= str_replace(",",".",number_format($priceP)); ?></span></td>
                    </tr>
                </table>
                <hr>
                <button class="btn btn-warning pl-5 pr-5" onclick="buy()">Beli</button>
                <button class="btn btn-primary" onclick="addCart()">Tambah ke Keranjang</button>
            </div>
        </div>
    </div>
    <hr>
    <div class="description">
        <?= nl2br($product['description']); ?>
    </div>
    <hr>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script>
    function plusProduct(price, stock){
        let inputJml;
        inputJml = parseInt($("input.valueJml").val());
        inputJml = inputJml + 1;
        if(inputJml <= stock){
            $("input.valueJml").val(inputJml);
            const newPrice = inputJml * price;
            const rpFormat = number_format(newPrice);
            $("#detailTotalPrice").text(rpFormat.split(",").join("."));
        }
    }

    function minusProduct(price){
        let inputJml;
        inputJml = parseInt($("input.valueJml").val());
        inputJml = inputJml - 1;
        if(inputJml >= 1){
            $("input.valueJml").val(inputJml);
            const newPrice = inputJml * price;
            const rpFormat = number_format(newPrice);
            $("#detailTotalPrice").text(rpFormat.split(",").join("."));
        }
    }

    function number_format (number, decimals, decPoint, thousandsSep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
        var n = !isFinite(+number) ? 0 : +number
        var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
        var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
        var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
        var s = ''

        var toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec)
        return '' + (Math.round(n * k) / k)
            .toFixed(prec)
        }

        // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
        if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
        }
        if ((s[1] || '').length < prec) {
        s[1] = s[1] || ''
        s[1] += new Array(prec - s[1].length + 1).join('0')
        }

        return s.join(dec)
    }

    function buy(){
        $.ajax({
            url: "<?= base_url(); ?>cart/add_to_cart",
            type: "post",
            data: {
                id: <?= $product['productId']; ?>,
                qty: $("#qtyProduct").val()
            },
            success: function(data){
                location.href = "<?= base_url(); ?>cart"
            }
        })
    }

    function addCart(){
        $.ajax({
            url: "<?= base_url(); ?>cart/add_to_cart",
            type: "post",
            data: {
                id: <?= $product['productId']; ?>,
                qty: $("#qtyProduct").val()
            },
            success: function(data){
                $(".navbar-cart-inform").html(`<i class="fa fa-shopping-cart"></i> Keranjang(<?= count($this->cart->contents()) + 1; ?>)`);
                swal({
                    title: "Berhasil Ditambah ke Keranjang",
                    text: `<?= $product['title']; ?>`,
                    icon: "success",
                    buttons: true,
                    buttons: ["Lanjut Belanja", "Lihat Keranjang"],
                    })
                    .then((cart) => {
                    if (cart) {
                        location.href = "<?= base_url(); ?>cart"
                    }
                });
            }
        })
    }

</script>
