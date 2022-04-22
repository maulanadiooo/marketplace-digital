<?php
require '../../web.php';
require '../../lib/result.php';
require '../../lib/csrf_token.php';

if (!isset($_SESSION['id_sementara'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
$login = $_SESSION['id_sementara'];
if ($model->db_query($db, "*", "user", "id = '$login'")['count'] == 0) {
	exit("No direct script access allowed!!");
}
if (!isset($_GET['query_invoice_pm'])) {
	exit("No direct script access allowed!!");
}
$id_invoice = mysqli_real_escape_string($db, $_GET['query_invoice_pm']);
$data = $model->db_query($db, "*", "cart", "kode_invoice = '".mysqli_real_escape_string($db, $_GET['query_invoice_pm'])."' AND buyer_id = '$login' ");
$data_depo = $model->db_query($db, "*", "deposit", "kode_depo = '".mysqli_real_escape_string($db, $_GET['query_invoice_pm'])."' AND user_id = '$login' ");
$pm =$model->db_query($db, "*", "bank_information", "id = '5' AND status = 'active' ");
if($data['count'] > 0){
    $nilai_dalamDollar = $data['rows']['total_price_admin']/$pm['rows']['rate_dollar'];
    $nama_pm = $pm['rows']['nama_pemilik_bank'];
    $no_pm = $pm['rows']['no_rek'];
    
    $service = $model->db_query($db, "*", "services", "id = '".$data['rows']['service_id']."'");
    $nama_layanan = $service['rows']['nama_layanan'];
    
    $base_url = $config['web']['base_url'];
    $terima_pm1 = $base_url."checkout/perfectmoney/terima1.php";
    $terima_pm = $base_url."checkout/perfectmoney/terima.php";
    $tidak_terima_pm = $base_url;
    
    $title = "Konfirmasi Pembayaran Perfect Money";
    
    require '../../template/header.php';
    ?>
    <section class="order-confirm-area bgcolor">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 offset-lg-1 col-md-12">
                        <div class="order-confirm-wrapper">
                            <div class="row">
                                <div class="col-lg-6 offset-lg-3">
                                    <form action="https://perfectmoney.is/api/step1.asp" method="post" class="mt-4" role="form">
                                        <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                                        
                                            <div class="form-group mt-3">
                                                <!-- Identify your business so that you can collect the payments. -->
                                                    <input type="hidden" name="PAYEE_ACCOUNT" value="<? echo $no_pm; ?>">
                                                    <input type="hidden" name="PAYEE_NAME" value="<? echo $nama_pm; ?>">
                                                    <input type="hidden" name="PAYMENT_AMOUNT" value="<? echo round($nilai_dalamDollar, 2); ?>">
                                                    <input type="hidden" name="PAYMENT_UNITS" value="USD">
                                                    <input type="hidden" name="SUGGESTED_MEMO" value="Pembayaran Produk <? echo $nama_layanan ?>">
                                                    <input type="hidden" name="PAYMENT_ID" value="<? echo $id_invoice; ?>">
                                                    <input type="hidden" name="STATUS_URL" 
                                                        value="<? echo $terima_pm1 ?>">
                                                    <input type="hidden" name="PAYMENT_URL" 
                                                        value="<? echo $terima_pm ; ?>">
                                                    <input type="hidden" name="NOPAYMENT_URL" 
                                                        value="<? echo $tidak_terima_pm ;?>">
                                                    <input type="hidden" name="BAGGAGE_FIELDS" 
                                                        value="">
                                                    <input type="hidden" name="CUST_NUM" value="">
                                                     <h2>Sebelum Melanjutkan Pembayaran, Silahkan Klik Tombol Konfirmasi Dibawah!</h2>
                                                        <p>Anda Akan diarahkan Kehalaman Pembayaran Perfectmoney
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
        $nilai_dalamDollars = $data_depo['rows']['amount']/$pm['rows']['rate_dollar'];
        $nama_pm = $pm['rows']['nama_pemilik_bank'];
        $no_pm = $pm['rows']['no_rek'];
        
        $user_depo = $model->db_query($db, "*", "user", "id = '".$data_depo['rows']['user_id']."'");
        $nama_layanan = $service['rows']['nama_layanan'];
        
        $base_url = $config['web']['base_url'];
        $terima_pm1 = $base_url."checkout/perfectmoney/terima1.php";
        $terima_pm = $base_url."checkout/perfectmoney/terima.php";
        $tidak_terima_pm = $base_url;
        
        $title = "Konfirmasi Pembayaran Perfect Money";
        
        require '../../template/header.php';
        ?>
        <section class="order-confirm-area bgcolor">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 offset-lg-1 col-md-12">
                            <div class="order-confirm-wrapper">
                                <div class="row">
                                    <div class="col-lg-6 offset-lg-3">
                                        <form action="https://perfectmoney.is/api/step1.asp" method="post" class="mt-4" role="form">
                                            <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                                            
                                                <div class="form-group mt-3">
                                                    <!-- Identify your business so that you can collect the payments. -->
                                                        <input type="hidden" name="PAYEE_ACCOUNT" value="<? echo $no_pm; ?>">
                                                        <input type="hidden" name="PAYEE_NAME" value="<? echo $nama_pm; ?>">
                                                        <input type="hidden" name="PAYMENT_AMOUNT" value="<? echo round($nilai_dalamDollars, 2); ?>">
                                                        <input type="hidden" name="PAYMENT_UNITS" value="USD">
                                                        <input type="hidden" name="SUGGESTED_MEMO" value="Deposit  <? echo $user_depo['rows']['username'] ?>">
                                                        <input type="hidden" name="PAYMENT_ID" value="<? echo $id_invoice; ?>">
                                                        <input type="hidden" name="STATUS_URL" 
                                                            value="<? echo $terima_pm1 ?>">
                                                        <input type="hidden" name="PAYMENT_URL" 
                                                            value="<? echo $terima_pm ; ?>">
                                                        <input type="hidden" name="NOPAYMENT_URL" 
                                                            value="<? echo $tidak_terima_pm ;?>">
                                                        <input type="hidden" name="BAGGAGE_FIELDS" 
                                                            value="">
                                                        <input type="hidden" name="CUST_NUM" value="">
                                                         <h2>Sebelum Melanjutkan Deposit, Silahkan Klik Tombol Konfirmasi Dibawah!</h2>
                                                            <p>Anda Akan diarahkan Kehalaman Pembayaran Perfectmoney
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
    


