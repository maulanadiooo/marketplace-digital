<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
require '../lib/csrf_token.php';

if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$login['id']."'")['count'] == 0) {
	exit("No direct script access allowed!!");
}
if (!isset($_GET['query_id_depo'])) {
	exit("No direct script access allowed!!");
}
$id_depo = mysqli_real_escape_string($db, $_GET['query_id_depo']);
$data = $model->db_query($db, "*", "deposit", "kode_depo = '".mysqli_real_escape_string($db, $_GET['query_id_depo'])."' AND user_id = '".$login['id']."' AND status='pending'  ");
if($data['count'] == 0){
    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data Tidak Ditemukan!');
    exit(header("Location: ".$config['web']['base_url']));
}
$bank =$model->db_query($db, "*", "bank_information", "id = '".$data['rows']['id_bank']."'");
if($bank['count'] == 0){
    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Metode Pembayaran Tidak Bisa Dilakukan ');
    exit(header("Location: ".$config['web']['base_url']."add-balance/"));
}

$title = "Penambahan Saldo";

require '../template/header.php';
?>
<section class="order-confirm-area bgcolor">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 col-md-12">
                    <div class="order-confirm-wrapper">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                
                             <h1>Permintaan Deposit!</h1>
                                <p>
                                    Silahkan Melakukan Transfer Sesuai Dengan Nominal yang Ditentukan <br>
                                    Transfer Sebelum <font color="red"><?= format_date(substr($data['rows']['expired_at'], 0, -9)).", ".substr($data['rows']['expired_at'], 11, -3); ?> UTC+7<br></font>
                                    <h2>Rp <?= number_format($data['rows']['amount'],0,',','.') ?></h2><br>
                                    
                                    <?
                                    if($bank['rows']['id'] == '11'){
                                    ?>
                                    <h2><?=$bank['rows']['bank']?> <br><br>
                                    <a href="<?=$data['rows']['url_coinpayment']?>"><button class="btn btn--lg btn-primary">Bayar</button></a>
                                    </h2>
                                    <?
                                    } elseif($bank['rows']['id'] == '12'){
                                    ?>
                                    <h2><?=$bank['rows']['bank']?> <br><br>
                                    <a href="<?=$data['rows']['url_coinpayment']?>"><button class="btn btn--lg btn-primary">Bayar</button></a>
                                    </h2>
                                    <?
                                    } elseif($bank['rows']['id'] == '8'){
                                    ?>
                                    <h2><?=$bank['rows']['bank']?> <br><br>
                                    <a href="<?=$data['rows']['url_coinpayment']?>"><button class="btn btn--lg btn-primary">Bayar</button></a>
                                    </h2>
                                    <?
                                    }elseif($bank['rows']['id'] == '6'){
                                    ?>
                                    <h2><?=$bank['rows']['bank']?> <br><br>
                                    <a href="<?=$data['rows']['url_coinpayment']?>"><button class="btn btn--lg btn-primary">Bayar</button></a>
                                    </h2>
                                    <?
                                    }elseif($bank['rows']['id'] == '1') {
                                    ?>
                                    <h2><?=$bank['rows']['bank']?> <br>
                                    <?= $bank['rows']['no_rek']?> <br> 
                                    <?= $bank['rows']['nama_pemilik_bank']?></h2>
                                    <?
                                    } elseif($bank['rows']['id'] == '13'){
                                    ?>
                                    <h2><?=$bank['rows']['bank']?> <br><br>
                                    <a href="<?=$data['rows']['url_coinpayment']?>"><button class="btn btn--lg btn-primary">Bayar</button></a>
                                    </h2>
                                    <?
                                    } elseif($bank['rows']['id'] == '14'){
                                    ?>
                                    <h2><?=$bank['rows']['bank']?> <br><br>
                                    <a href="<?=$data['rows']['url_coinpayment']?>"><button class="btn btn--lg btn-primary">Bayar</button></a>
                                    </h2>
                                    <?
                                    } elseif($bank['rows']['id'] == '15'){
                                    ?>
                                    <h2><?=$bank['rows']['bank']?> <br><br>
                                    <a href="<?=$data['rows']['url_coinpayment']?>"><button class="btn btn--lg btn-primary">Bayar</button></a>
                                    </h2>
                                    <?
                                    } else {
                                    ?>
                                    <h2>Keterangan Invoice Tidak Terdeteksi</h2>
                                    <?
                                    }
                                    ?>
                                    
                                </p>
                                <?
                                if($bank['rows']['id'] == '1') {
                                ?>
                                <span>Note: Kode Unik Akan Masuk Sebagai Saldo Anda</span> 
                                <?
                                }
                                ?>
                                          
                            </div>
                        </div>
                    </div><!-- ends: .order-confirm-wrapper -->
                </div><!-- ends: .col-lg-12 -->
            </div><!-- ends: .row -->
        </div>
</section><!-- ends: .order-confirm-area -->

<?php
require '../template/footer.php';

?>