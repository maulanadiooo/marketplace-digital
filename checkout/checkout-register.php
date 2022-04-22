<?php
require '../web.php';
require '../lib/result.php';
require '../lib/csrf_token.php';
if (isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']));
}

if (!isset($_GET['query_id'])) {
	exit("No direct script access allowed!!");
}
$websitea = $model->db_query($db, "*", "website", "id = '1'");   

    $id_cart = mysqli_real_escape_string($db, $_GET['query_id']);
    $data_targeta = $model->db_query($db, "*", "cart", "kode_invoice = '$id_cart' AND status = 'waiting' ");
    
    
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




$data_targets = $model->db_query($db, "*", "cart", "kode_invoice = '".mysqli_real_escape_string($db, $_GET['query_id'])."' AND status = 'pending' ");
if($data_targets['count'] == 0){
    exit(header("Location: ".$config['web']['base_url']));
}
  
$data_service = $model->db_query($db, "*", "services", "id = '".$data_targets['rows']['service_id']."' ");
$website = $model->db_query($db, "*", "website", "id = '1'");

$data_orders = $model->db_query($db, "*", "orders", "kode_unik = '".$data_targets['rows']['kode_unik']."' AND status = 'unpaid' ");
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
                            <form method="post" action ="<?=$config['web']['base_url']?>checkout-action/<?=$id_cart?>">
                            <div class="information_module">
                                <div class="toggle_title">
                                    <h4>Billing Information</h4>
                                </div>
                                <div class="information__set">
                                    <div class="information_wrapper form--fields" >
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="full_name">Nama Lengkap <sup>*</sup></label>
                                                    <input id="full_name" name="full_name" type="text" class="text_field"  value="<?= $_SESSION['full_name'] ?>">
                                                </div><!-- ends: .col-md-6 -->
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email <sup>*</sup></label>
                                                    <input id="email" name="email" type="email" class="text_field"  value="<?= $_SESSION['email'] ?>">
                                                </div>
                                            </div><!-- ends: .col-md-6 -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="no_hp">Nomor Whatsapp <sup>*</sup></label>
                                                    <input id="no_hp" name="no_hp" type="number" class="text_field" placeholder="No HP/ Whatsapp" value="<?= $_SESSION['no_hp'] ?>">
                                                </div>
                                            </div><!-- ends: .col-md-6 -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="username">Username <sup>*</sup></label>
                                                    <input name="username" id="username" type="text" class="text_field" minlength="5"  required oninvalid="this.setCustomValidity('Username Minimal 5 Karakter')" oninput="setCustomValidity('')" value="<?= $_SESSION['username'] ?>">
                                                </div>
                                            </div><!-- ends: .col-md-6 -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password">Password <sup>*</sup></label>
                                                    <input name="password" id="password" type="password" class="text_field" minlength="5"  required oninvalid="this.setCustomValidity('Password Minimal 5 Karakter')" oninput="setCustomValidity('')">
                                                </div>
                                            </div><!-- ends: .col-md-6 -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="co_password">Konfirmasi Password <sup>*</sup></label>
                                                    <input name="co_password" id="co_password" type="password" class="text_field" >
                                                </div>
                                            </div><!-- ends: .col-md-6 -->
                                        </div>
                                    </div>
                                </div><!-- ends: .payment_tabs -->
                            </div><!-- ends: .payment_module -->
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
                                    </ul>
                                    <?
                                    if($data_service['rows']['allow_buyer_information'] == 'yes'){
                                    ?> 
                                    <div class="col-md-12">
                                        <center><label>Penjual Membutuhkan Informasi : </label></center>
                                        <center><h3><?= $data_service['rows']['buyer_information']?></h3></center><br>
                                        <textarea name ="buyer_information" placeholder="Berikan Informasi Sesuai Dengan Instruksi" required oninvalid="this.setCustomValidity('Berikan Informasi Sesuai Petunjuk Penjual')" oninput="setCustomValidity('')"><?= $_SESSION['buyer_information'] ?></textarea>
                                    </div>
                                    <?    
                                    }
                                    ?>
                                    <div class="col-md-12">
                                        <img src="<?=$config['web']['base_url']?>checkout/captcha.php" alt="gambar" />
                                        <div class="form-group">
                                            <label for="full_name">Isi Captcha <sup>*</sup></label>
                                            <input name="kodecaptcha" type="number" placeholder="input captcha" maxlength="5"/>
                                        </div><!-- ends: .col-md-6 -->
                                    </div>
                                            
                                        
                                    <div class="payment_info modules__content">
                                        
                                        
                                        <div class="col-md-12">
                                            <div class="custom_checkbox">
                                                <input type="checkbox" id="ch2" name="accept_terms">
                                                <label for="ch2">
                                                    <span class="shadow_checkbox"></span>
                                                    <span class="label_text">Setuju Dengan <a href="<?=$config['web']['base_url']?>term-condition">Syarat & Ketentuan</a> <sup>*</sup></span>
                                                </label>
                                            </div>
                                        </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn--md btn-primary">Konfirmasi Pesanan</button>
                                        </div>
                                    </div>
                                    </div>
                                </div><!-- ends: .information_module-->
                                </form>
                            </div>
                            
                    </div><!-- ends: .row -->
            </div>
        </div><!-- ends: .dashboard_contents -->
    </section><!-- ends: .dashboard-area -->
  
<?php
require '../template/footer.php';

?>