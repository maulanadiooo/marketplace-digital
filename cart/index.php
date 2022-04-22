<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
require '../lib/csrf_token.php';

if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit("No direct script access allowed!2");
}


$title = "Keranjang";
?>
<?php
require '../template/header.php';
?>

<section class="cart_area section--padding bgcolor">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product_archive added_to__cart">
                        <div class="table-responsive single_product">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <h4>Product Details</h4>
                                        </th>
                                        <th scope="col">
                                            <h4>Harga Produk</h4>
                                        </th>
                                        <th scope="col">
                                            <h4>Jumlah</h4>
                                            <span>(Produk Utama)</span>
                                        </th>
                                        <th scope="col">
                                            <h4>Aksi</h4>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data_target = mysqli_query($db, "SELECT * FROM cart WHERE buyer_id = '".$login['id']."' AND status in ('pending', 'waiting') ORDER by created_at DESC");
                                    while ($data_target_s = mysqli_fetch_assoc($data_target)){
                                    $data_service = $model->db_query($db, "*", "services", "id = '".$data_target_s['service_id']."'");
                                    if($data_service['rows']['allow_multisale'] == 'yes'){
                                        $end_number = $data_service['rows']['max_pembelian'];
                                    } else {
                                        $end_number = 1;
                                    }
                                    ?>
                                    <form method="post" action="<?= $config['web']['base_url']; ?>checkout/<?=$data_target_s['kode_invoice']?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                                    <tr>
                                        <td colspan="1">
                                            <div class="product__description">
                                                <div class="p_image"><img src="<?= $config['web']['base_url']; ?>file-photo/<?=$data_service['rows']['id'] ?>/<?=$data_service['rows']['photo'] ?>" width="100px" height="80px"></div>
                                                <div class="short_desc">
                                                    <a href="<?= $config['web']['base_url'] ?>product/<?=$data_service['rows']['id'] ?>/<?=$data_service['rows']['url'] ?>">
                                                        <h6><?=$data_service['rows']['nama_layanan']?></h6>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="item_price">
                                                <span>Rp <?= number_format($data_target_s['total_price'],0,',','.') ?></span>
                                            </div>
                                        </td>
                                        <?
                                        if($data_target_s['status'] == 'waiting'){
                                         ?>
                                        <td>
                                            <span><?=$data_target_s['quantity']?></span>
                                            
                                        </td>   
                                        <?
                                        } else {
                                        ?>    
                                        <td>
                                            <span><?=$data_target_s['quantity']?></span>
                                        </td>
                                        <?    
                                        }
                                        ?>
                                        <td>
                                            <button name ="buy_now" type="submit" class="btn btn--md checkout_link btn-primary">Bayar</button>
                                        </td>
                                    </tr>
                                    </form>
                                    <?
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div><!-- ends: .single_product -->
                    </div><!-- end .added_to__cart -->
                </div><!-- end .col-md-12 -->
            </div><!-- end .row -->
        </div><!-- end .container -->
    </section><!-- ends: .cart_area -->
    
   <?php
require '../template/footer.php';

?>