<?
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
$website = $model->db_query($db, "*", "website", "id = '1'");

if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
// 	Delete untuk mengaktifkan
// 	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Tindakan Tidak Dapat Dilakukan Pada Akun Demo, Beli Script DI WA: +6285668708552.');
// 	exit(header("Location: ".$config['web']['base_url']."my-product/"));
// 	Delete untuk mengaktifkan
    if (!isset($_SESSION['login'])) {
		exit("No direct script access allowed!1");
	}
	if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) { 
		exit("No direct script access allowed!2");
	}
	if (!isset($_GET['query_id']) OR !isset($_GET['buy'])) {
		exit("No direct script access allowed!3");
	}
	if (in_array($_GET['buy'], array('2')) == false) {
		exit("No direct script access allowed!4");
	}
	$id = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_GET['query_id']))));
	$check_premium = $model->db_query($db, "*", "services", "featured = '1'");
	
	$exp_terbaru = mysqli_query($db, "SELECT * FROM services WHERE featured = '1' ORDER BY expired_featured ASC LIMIT 1 ");
	$exp_terbaru = mysqli_fetch_assoc($exp_terbaru);
	$exp_tgl = format_date(substr($exp_terbaru['expired_featured'], 0, -9)).", ".substr($exp_terbaru['expired_featured'], -8);
	$premium_tersedia_wiithout_pending_payment = 3 - $check_premium['count'];
	$check_pending_payment = mysqli_query($db, "SELECT * FROM fitur_order WHERE order_fitur = 'featured' AND status = 'PENDING' ORDER BY expired ASC");
	$check_fitur_order = $model->db_query($db, "*", "fitur_order", "service_id = '$id' AND order_fitur = 'featured' AND status = 'PENDING' ");
	if($check_premium['count'] >= 3){
	    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Fitur Featured Penuh Ready Kembali pada '.$exp_tgl.' WIB');
		exit(header("Location: ".$config['web']['base_url']."my-product/"));
	} 
	elseif(mysqli_num_rows($check_pending_payment) >= $premium_tersedia_wiithout_pending_payment){
	     $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Seseorang Lagi Ngantri Untuk Bayar Fitur Ini, Tunggu +-30 Menit dan Cek Kembali ^.^'); 
		exit(header("Location: ".$config['web']['base_url']."my-product/"));
	}
	elseif($check_fitur_order['count'] == 1){
	    if($check_fitur_order['rows']['pembayaran_id'] == '11' || $check_fitur_order['rows']['pembayaran_id'] == '6' || $check_fitur_order['rows']['pembayaran_id'] == '12' || $check_fitur_order['rows']['pembayaran_id'] == '13' || $check_fitur_order['rows']['pembayaran_id'] == '14' || $check_fitur_order['rows']['pembayaran_id'] == '15'){
	    exit(header("Location: ".$check_fitur_order['rows']['url_pembayaran']));    
	    } elseif($check_fitur_order['rows']['pembayaran_id'] == '1'){
	     exit(header("Location: ".$config['web']['base_url']."invoice-fitur/".$check_fitur_order['rows']['kode_invoice']));     
	    } else {
	     exit(header("Location: ".$config['web']['base_url']."checkout-fitur/".$id."/featured"));    
	    }
	} else {
	    exit(header("Location: ".$config['web']['base_url']."checkout-fitur/".$id."/featured"));  
	}
	
	
	require '../lib/result.php';
    