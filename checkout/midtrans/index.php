<?php
require '../../web.php';
require '../../lib/is_login.php';
require '../../lib/result.php';
require '../../lib/csrf_token.php';

if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$login['id']."'")['count'] == 0) {
	exit("No direct script access allowed!!");
}
if (!isset($_GET['query_invoice_pm'])) {
	exit("No direct script access allowed!!");
}
$id_invoice = mysqli_real_escape_string($db, $_GET['query_invoice_pm']);
$format = $id_invoice;
$pisah = explode("pay", $format);
$invoice_id = $pisah[0];
$token = $pisah[1];
$data = $model->db_query($db, "*", "cart", "kode_invoice = '".$invoice_id."' AND buyer_id = '".$login['id']."' ");
$pm =$model->db_query($db, "*", "bank_information", "id = '5' AND status = 'active' ");
if($pm['count'] == 0){
    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Metode Pembayaran Tidak Bisa Dilakukan ');
    exit(header("Location: ".$config['web']['base_url']."checkout/".$id_invoice));
}
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
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-E_xOL_L7MCHjTRE3"></script>
<section class="order-confirm-area bgcolor">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 col-md-12">
                    <div class="order-confirm-wrapper">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3">
                                <form onsubmit="return false">
                                  <input type="text" id="snap-token" value="<?=$token?>">
                                  <button id="pay-button">Pay!</button>
                                </form>
                            </div>
                        </div>
                    </div><!-- ends: .order-confirm-wrapper -->
                </div><!-- ends: .col-lg-12 -->
            </div><!-- ends: .row -->
        </div>
</section><!-- ends: .order-confirm-area -->
<script type="text/javascript">
      var payButton = document.getElementById('pay-button');
      // For example trigger on button clicked, or any time you need
      payButton.addEventListener('click', function() {
        var snapToken = document.getElementById('snap-token').value;
        snap.pay(snapToken);
      });

    </script>
<?php
require '../../template/footer.php';

?>