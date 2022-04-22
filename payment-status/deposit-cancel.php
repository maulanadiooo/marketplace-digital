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
                                
                                <h2>Opsss.. Nampaknya Kami Belum Menerima Pembayaran Anda</h2>
                                <p>Jika Ini Sebuah Kesahalan, Silahkan Hubungi Admin
                                </p>
                                <a href="<?=$config['web']['base_url']?>" class="btn btn-lg btn-primary">Kembali</a>
                                
                                
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