<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';

if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$login['id']."'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']));
}
if(!isset($_GET['order_id']) && !isset($_GET['transaction_status'])){
    exit(header("Location: ".$config['web']['base_url']));
}
$transaction_status = mysqli_real_escape_string($db, $_GET['transaction_status']);
$order_id = mysqli_real_escape_string($db, $_GET['order_id']);

$title = "Status Pembayaran";

require '../template/header.php';
?>
<section class="order-confirm-area bgcolor">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 col-md-12">
                    <div class="order-confirm-wrapper">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <?
                                if($transaction_status == 'settlement'){
                                ?>
                                <h2>Terimakasih, Pembayaran Anda Sudah Kami Terima!</h2>
                                <?
                                } else {
                                ?>    
                                <h2>Ooopss..</h2>
                                <p>Sayangnya kami belum menerima pembayaran yang anda lakukan
                                </p>
                                <span>Jika ini adalah kesalahan, hubungi admin</span>
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