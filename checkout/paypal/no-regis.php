<?php
require '../../web.php';
require '../../lib/result.php';
include_once 'config.php'; 
require '../../lib/csrf_token.php';

if (!isset($_SESSION['id_sementara'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
$login = $_SESSION['id_sementara'];
if ($model->db_query($db, "*", "user", "id = '$login'")['count'] == 0) {
	exit("No direct script access allowed!!");
}
if (!isset($_GET['query_invoice_paypal'])) {
	exit("No direct script access allowed!!");
}
$id_invoice = mysqli_real_escape_string($db, $_GET['query_invoice_paypal']);
$data = $model->db_query($db, "*", "cart", "kode_invoice = '".mysqli_real_escape_string($db, $_GET['query_invoice_paypal'])."' AND buyer_id = '".$login."' ");
$data_depo = $model->db_query($db, "*", "deposit", "kode_depo = '".mysqli_real_escape_string($db, $_GET['query_invoice_paypal'])."' AND user_id = '".$login."' ");
$pm =$model->db_query($db, "*", "bank_information", "id = '7' AND status = 'active' ");

if($data['count'] > 0){
    $nilai_dalamDollar = $data['rows']['total_price_admin']/$pm['rows']['rate_dollar'];


    $service = $model->db_query($db, "*", "services", "id = '".$data['rows']['service_id']."'");
    $nama_layanan = $service['rows']['nama_layanan'];
    
    
    $title = "Konfirmasi Pembayaran Paypal";
    
    require '../../template/header.php';
    ?>
    <section class="order-confirm-area bgcolor">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 offset-lg-1 col-md-12">
                        <div class="order-confirm-wrapper">
                            <div class="row">
                                <div class="col-lg-6 offset-lg-3">
                                     <form action="<?php echo PAYPAL_URL; ?>" method="post" class="mt-4" role="form">
                                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                                        <div class="form-group mt-3">
                                            <!-- Identify your business so that you can collect the payments. -->
                                                <input type="hidden" name="business" value="<?php echo PAYPAL_ID; ?>">  
                                                
                                                <!-- Specify a Buy Now button. -->
                                                <input type="hidden" name="cmd" value="_xclick">                                                                                        
                                                
                                                <!-- Specify details about the item that buyers will purchase. -->
                                                <input type="hidden" name="item_name" value="<? echo $nama_layanan; ?>">
                                                
                                                <input type="hidden" name="item_number" value="<? echo $id_invoice; ?>">
                                                
                                                <input type="hidden" name="amount" value="<?php echo round($nilai_dalamDollar, 2); ?>">
                                                <input type="hidden" name="notify_url" value="<?php echo PAYPAL_NOTIFY_URL; ?>">
                                                
                                                <input type="hidden" name="currency_code" value="<?php echo PAYPAL_CURRENCY; ?>">
                                                
                                                <!-- Specify URLs -->
                                                <input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL; ?>">
                                                <input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">
                                            
                                                <h2>Sebelum Melanjutkan Pembayaran, Silahkan Klik Tombol Konfirmasi Dibawah!</h2>
                                                        <p>Anda Akan diarahkan Kehalaman Pembayaran Paypal
                                                        </p>
                                                <button type="submit" class="btn btn-primary btn-block my-1">Konfirmasi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- ends: .order-confirm-wrapper -->
                    </div><!-- ends: .col-lg-12 -->
                </div><!-- ends: .row -->
            </div>
    </section><!-- ends: .order-confirm-area -->
    
    <?php
    require '../../template/footer.php';
    } elseif($data_depo['count'] > 0) {
        $nilai_dalamDollar = $data_depo['rows']['amount']/$pm['rows']['rate_dollar'];

        $user_depo = $model->db_query($db, "*", "user", "id = '".$data_depo['rows']['user_id']."'");
        $service = $model->db_query($db, "*", "services", "id = '".$data['rows']['service_id']."'");
        $nama_layanan = $service['rows']['nama_layanan'];
        
        
        $title = "Konfirmasi Pembayaran Paypal";
        
        require '../../template/header.php';
        ?>
        <section class="order-confirm-area bgcolor">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 offset-lg-1 col-md-12">
                            <div class="order-confirm-wrapper">
                                <div class="row">
                                    <div class="col-lg-6 offset-lg-3">
                                         <form action="<?php echo PAYPAL_URL; ?>" method="post" class="mt-4" role="form">
                                        <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                                            <div class="form-group mt-3">
                                                <!-- Identify your business so that you can collect the payments. -->
                                                    <input type="hidden" name="business" value="<?php echo PAYPAL_ID; ?>">  
                                                    
                                                    <!-- Specify a Buy Now button. -->
                                                    <input type="hidden" name="cmd" value="_xclick">                                                                                        
                                                    
                                                    <!-- Specify details about the item that buyers will purchase. -->
                                                    <input type="hidden" name="item_name" value="Deposit  <? echo $user_depo['rows']['username'] ?>">
                                                    
                                                    <input type="hidden" name="item_number" value="<? echo $id_invoice; ?>">
                                                    
                                                    <input type="hidden" name="amount" value="<?php echo round($nilai_dalamDollar, 2); ?>">
                                                    <input type="hidden" name="notify_url" value="<?php echo PAYPAL_NOTIFY_URL; ?>">
                                                    
                                                    <input type="hidden" name="currency_code" value="<?php echo PAYPAL_CURRENCY; ?>">
                                                    
                                                    <!-- Specify URLs -->
                                                    <input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL; ?>">
                                                    <input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">
                                                
                                                    <h2>Sebelum Melanjutkan Deposit, Silahkan Klik Tombol Konfirmasi Dibawah!</h2>
                                                            <p>Anda Akan diarahkan Kehalaman Pembayaran Paypal
                                                            </p>
                                                    <button type="submit" class="btn btn-primary btn-block my-1">Konfirmasi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div><!-- ends: .order-confirm-wrapper -->
                        </div><!-- ends: .col-lg-12 -->
                    </div><!-- ends: .row -->
                </div>
        </section><!-- ends: .order-confirm-area -->
        
        <?php
        require '../../template/footer.php';
    } else {
        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
	    exit(header("Location: ".$config['web']['base_url']));
    }
