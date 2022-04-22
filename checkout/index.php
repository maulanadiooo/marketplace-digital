<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
require '../lib/csrf_token.php';


if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit("No direct script access allowed!!");
}
if (!isset($_GET['query_id'])) {
	exit("No direct script access allowed!!1");
}
$websitea = $model->db_query($db, "*", "website", "id = '1'");   

    $id_cart = mysqli_real_escape_string($db, $_GET['query_id']);
    $data_targeta = $model->db_query($db, "*", "cart", "kode_invoice = '$id_cart' AND buyer_id = '".$login['id']."' AND status = 'waiting' ");
    
    
    // $quantity_submit = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['quantity']))));
    // if(!$quantity_submit){
    //     $quantity = '1';
    // } else {
    //     $quantity = $quantity_submit;
    // }
    $quantity = $data_targeta['rows']['quantity'];
    $price_quantity = $data_targeta['rows']['price'];
    $price_total = $price_quantity - $data_targeta['rows']['price'];
    $total_price = $price_total + $data_targeta['rows']['total_price'];
    $fee_admin_from_seller = ($websitea['rows']['admin_fee_seller']/100) * $total_price ;
    $price_for_seller = $total_price - $fee_admin_from_seller ;
    $total_price_admin = $total_price + $websitea['rows']['admin_fee'];
    $random = rand(111,999);
    $price_kode_unik = $total_price_admin + $random ;
    $now = date("Y-m-d H:i:s");
    $expired_at = date('Y-m-d H:i:s',strtotime('+24 Hour',strtotime($now)));
    
    $input_post_update = array(
    'quantity' => $quantity,
    'price' => $price_quantity,
    'total_price' => $total_price,
    'biaya_admin' => $websitea['rows']['admin_fee'],
    'total_price_admin' => $total_price_admin,
    'price_kode_unik' => $price_kode_unik,
    'status' => 'pending',
    'created_at' => date('Y-m-d H:i:s'),
    'expired_date' => $expired_at
    );
    $update = $model->db_update($db, "cart", $input_post_update, "kode_unik = '".$data_targeta['rows']['kode_unik']."' ");
    
    $input_post_orders = array(
    'price' => $price_quantity,
    'quantity' => $quantity,
    'total_price' => $total_price,
    'price_for_seller' => $price_for_seller,
    );
    $update_orders = $model->db_update($db, "orders", $input_post_orders, "kode_unik = '".$data_targeta['rows']['kode_unik']."' ");




$data_targets = $model->db_query($db, "*", "cart", "kode_invoice = '".mysqli_real_escape_string($db, $_GET['query_id'])."' AND buyer_id = '".$login['id']."' AND status = 'pending' ");
if($data_targets['count'] == 0){
    exit(header("Location: ".$config['web']['base_url']));
}
if($data_targets['rows']['pembayaran_id_bank'] == '8' && $data_targets['rows']['buyer_id'] == $login['id'] && $data_targets['rows']['status'] == 'pending'){
    exit(header("Location: ".$data_targets['rows']['url_coinpayment']));
}
// if($data_targets['rows']['pembayaran_id_bank'] == '1' && $data_targets['rows']['buyer_id'] == $login['id'] && $data_targets['rows']['status'] == 'pending'){
//     exit(header("Location: ".$config['web']['base_url']."checkout-invoice/".$data_targets['rows']['kode_invoice']));
// }
if($data_targets['rows']['pembayaran_id_bank'] == '9' && $data_targets['rows']['buyer_id'] == $login['id'] && $data_targets['rows']['status'] == 'pending'){
    exit(header("Location: ".$data_targets['rows']['url_coinpayment']));
}
if($data_targets['rows']['pembayaran_id_bank'] == '11' && $data_targets['rows']['buyer_id'] == $login['id'] && $data_targets['rows']['status'] == 'pending'){
    exit(header("Location: ".$data_targets['rows']['url_coinpayment']));
}
if($data_targets['rows']['pembayaran_id_bank'] == '6' && $data_targets['rows']['buyer_id'] == $login['id'] && $data_targets['rows']['status'] == 'pending'){
    exit(header("Location: ".$data_targets['rows']['url_coinpayment']));
}
if($data_targets['rows']['pembayaran_id_bank'] == '15' && $data_targets['rows']['buyer_id'] == $login['id'] && $data_targets['rows']['status'] == 'pending'){
    exit(header("Location: ".$data_targets['rows']['url_coinpayment']));
}
if($data_targets['rows']['pembayaran_id_bank'] == '14' && $data_targets['rows']['buyer_id'] == $login['id'] && $data_targets['rows']['status'] == 'pending'){
    exit(header("Location: ".$data_targets['rows']['url_coinpayment']));
}
if($data_targets['rows']['pembayaran_id_bank'] == '16' && $data_targets['rows']['buyer_id'] == $login['id'] && $data_targets['rows']['status'] == 'pending'){
    exit(header("Location: ".$config['web']['base_url']."checkout-invoice/".$id_cart));
}
if($data_targets['rows']['pembayaran_id_bank'] == '17' && $data_targets['rows']['buyer_id'] == $login['id'] && $data_targets['rows']['status'] == 'pending'){
    exit(header("Location: ".$config['web']['base_url']."checkout-invoice/".$id_cart));
}  
$data_service = $model->db_query($db, "*", "services", "id = '".$data_targets['rows']['service_id']."' ");
$website = $model->db_query($db, "*", "website", "id = '1'");

$data_user = $model->db_query($db, "*", "user", "id = '".$login['id']."' ");


$data_orders = $model->db_query($db, "*", "orders", "kode_unik = '".$data_targets['rows']['kode_unik']."' AND buyer_id = '".$login['id']."' AND status = 'unpaid' ");
$title = "Checkout";
?>
<?php
require '../template/header.php';
?>

<section class="dashboard-area p-top-100 p-bottom-70">
        <div class="dashboard_contents">
            <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="information_module order_summary">
                                <div class="toggle_title">
                                    <h4>Ringkasan Pesanan</h4>
                                </div>
                                <ul>
                                    <li class="item">
                                        <a href="<?=$config['web']['base_url']?>product/<?= $data_service['rows']['id']?>/<?= $data_service['rows']['url']?>" target="_blank"><?= $data_service['rows']['nama_layanan']?></a>
                                        <span>Rp <?= number_format($data_targets['rows']['price'],0,',','.') ?> ,-</span>
                                    </li>
                                    <?
                                    if($data_targets['rows']['extra_product'] != null){
                                    ?>   
                                    <li class="item">
                                        <p>Extra : <?=$data_targets['rows']['extra_product']?></p>
                                        <span>Rp <?= number_format($data_targets['rows']['price_extra_product'],0,',','.') ?> ,-</span>
                                    </li>
                                    <?
                                    } if($data_targets['rows']['extra_product1'] != null){
                                    ?>    
                                    <li class="item">
                                        <p>Extra : <?=$data_targets['rows']['extra_product1']?></p>
                                        <span>Rp <?= number_format($data_targets['rows']['price_extra_product1'],0,',','.') ?> ,-</span>
                                    </li>
                                    <?    
                                    } if($data_targets['rows']['extra_product2'] != null) {
                                    ?>    
                                    <li class="item">
                                        <p>Extra : <?=$data_targets['rows']['extra_product2']?></p>
                                        <span>Rp <?= number_format($data_targets['rows']['price_extra_product2'],0,',','.') ?> ,-</span>
                                    </li>
                                    <?    
                                    }
                                    ?>
                                    
                                    <li>
                                        <p>Admin Fee : </p>
                                        <span>Rp <?= number_format($website['rows']['admin_fee'],0,',','.') ?> ,-</span>
                                    </li>
                                    <li class="total_ammount">
                                        <p>Total</p>
                                        <span>Rp <?= number_format($data_targets['rows']['total_price_admin'],0,',','.') ?> ,-</span>
                                    </li>
                                </ul>
                            </div><!-- ends: .information_module-->
                        </div> <!-- ends: col-lg-6-->
                        <div class="col-lg-6 col-md-12">
                            <div class="information_module payment_options">
                                <form method="post" action ="<?=$config['web']['base_url']?>checkout-invoice/<?=$data_targets['rows']['kode_invoice']?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                                    <div class="toggle_title">
                                        <h4>Pilih Metode Pembayaran</h4>
                                    </div>
                                    <ul>
                                        <?
                                        $data_target_bank = mysqli_query($db, "SELECT * FROM bank_information WHERE status ='active' ORDER by bank ASC");
                                        while ($data_target_bank1 = mysqli_fetch_assoc($data_target_bank)){
                                        ?>
                                        
                                        <li>
                                             
                                            <div class="custom-radio">
                                                <input type="radio" id="opt<?=$data_target_bank1['id']?>" class="" name="filter_opt" value="<?=$data_target_bank1['id']?>">
                                                <label for="opt<?=$data_target_bank1['id']?>">
                                                    <span class="circle"></span><?= $data_target_bank1['bank']?></label>
                                            </div>
                                            <img src="<?=$config['web']['base_url']?>img/bank/<?=$data_target_bank1['icon']?>" alt="<?= $data_target_bank1['bank']?>" width = "53px" height = "35px">
                                            
                                            
                                            
                                        </li>
                                        <?}?>
                                        <?
                                        if($data_user['rows']['saldo_tersedia'] < $data_targets['rows']['total_price_admin']){
                                            $disabel = "disabled";
                                            $info = "(Saldo Tidak Cukup)";
                                        }
                                        ?>
                                        
                                        <li>
                                        <div class="custom-radio">
                                            <input type="radio" id="opts" class="" name="filter_opt" value ="saldo_tersedia" <?=$disabel?>>
                                            <label for="opts">
                                                <span class="circle"></span>Saldo</label> <?=$info?>
                                        </div>
                                            <p>
                                            <span class="bold">Rp <?= number_format($data_user['rows']['saldo_tersedia'],0,',','.') ?> ,-</span>
                                            </p>
                                        </li>
                                    </ul>
                                    <?
                                    if($data_service['rows']['allow_buyer_information'] == 'yes'){
                                    ?> 
                                    <div class="col-md-12">
                                        <center><label>Penjual Membutuhkan Informasi : </label></center>
                                        <center><h5><?= $data_service['rows']['buyer_information']?></h5></center><br>
                                        <textarea name ="buyer_information" placeholder="Berikan Informasi Sesuai Dengan Instruksi" required oninvalid="this.setCustomValidity('Berikan Informasi Sesuai Petunjuk Penjual')" oninput="setCustomValidity('')"><?=$data_orders['rows']['instruction']?></textarea>
                                    </div>
                                    <?    
                                    }
                                    ?>
                                    <div class="payment_info modules__content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn--md btn-primary">Konfirmasi Pesanan</button>
                                        </div>
                                    </div>
                                    </div>
                                    </form>
                                </div><!-- ends: .information_module-->
                            </div>
                    </div><!-- ends: .row -->
            </div>
        </div><!-- ends: .dashboard_contents -->
    </section><!-- ends: .dashboard-area -->
    
<?php
require '../template/footer.php';

?>