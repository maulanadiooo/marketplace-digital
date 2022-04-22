<?
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';

require '../lib/csrf_token.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}

    if (!isset($_SESSION['login'])) {
		exit("No direct script access allowed!1");
	}
	if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
		exit("No direct script access allowed!2");
	}
	if (!isset($_GET['query_id']) OR !isset($_GET['action'])) {
		exit("No direct script access allowed!3");
	}
	if (in_array($_GET['action'], array('premium','featured')) == false) {
		exit("No direct script access allowed!4");
	}
	$id = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_GET['query_id']))));
	$action = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_GET['action']))));
	$check_services = $model->db_query($db, "*", "services", "id = '$id' AND status = 'active' ");
	$data_user = $model->db_query($db, "*", "user", "id = '".$login['id']."' ");
	$check_fitur_order = $model->db_query($db, "*", "fitur_order", "service_id = '$id' AND status = 'PENDING' AND order_fitur = '$action'");
	if($check_services['count'] == 0){
	     $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Aktifkan Layanan Sebelum Membeli Fitur');
		exit(header("Location: ".$config['web']['base_url']."my-product/"));
	} 
// 	elseif($check_fitur_order['count'] == 1){
// 	    if($check_fitur_order['rows']['pembayaran_id'] == '11' || $check_fitur_order['rows']['pembayaran_id'] == '6' || $check_fitur_order['rows']['pembayaran_id'] == '12' || $check_fitur_order['rows']['pembayaran_id'] == '13' || $check_fitur_order['rows']['pembayaran_id'] == '14' || $check_fitur_order['rows']['pembayaran_id'] == '15'){
// 	    exit(header("Location: ".$check_fitur_order['rows']['url_pembayaran']));    
// 	    } elseif($check_fitur_order['rows']['pembayaran_id'] == '1'){
// 	     exit(header("Location: ".$config['web']['base_url']."invoice-fitur/".$check_fitur_order['rows']['kode_invoice']));     
// 	    } elseif ($check_fitur_order['rows']['pembayaran_id'] == '0'){
// 	     exit(header("Location: ".$config['web']['base_url']."checkout-fitur/".$id."/featured"));    
// 	    }
// 	}
	else {
	    if($action == 'featured'){
	        $harga = $website['rows']['harga_fitur_featured'];
	    } else{
	        $harga = $website['rows']['harga_fitur_premium'];
	    }
	    $now = date("Y-m-d H:i:s");
        $expired_at = date('Y-m-d H:i:s',strtotime('+30 Minute',strtotime($now)));
	    $id_invoice = 'FTR'.rand(11111111,99999999);
	    $amount = $harga + rand(111,999);
	    if($check_fitur_order['count'] == 0){
	       $input = array(
    		        
            'user_id' => $login['id'],
            'order_fitur' => $action,
            'service_id' => $id,
            'kode_invoice' => $id_invoice,
            'amount' => $amount,
            'expired' => $expired_at,
            'status' => 'PENDING',
            );
            $insert = $model->db_insert($db, "fitur_order", $input); 
            if($insert == false){
               $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Permintaan Tidak Terselesaikan, Hubungi Admin, Error Code D-27');
    		exit(header("Location: ".$config['web']['base_url']."my-product/"));  
            }
	    }
	    
        if($check_fitur_order['count'] == 0){
           $invoice =  $input['kode_invoice']; 
        } else {
            $invoice = $check_fitur_order['rows']['kode_invoice'];
        }
            
 if($action == 'premium'){
     $durasi = $website['rows']['durasi_fitur_premium'];
 }  
 if($action == 'featured'){
     $durasi = $website['rows']['durasi_fitur_featured'];
 }

$title = "Select Payment";

require '../template/header.php';
?>
<section class="dashboard-area">
        <div class="dashboard_contents section--padding">
            <div class="container">
                <div class="row">
                     <div class="col-lg-12 col-md-12">
                            <div class="information_module payment_options">
                                <form method="post" action ="<?=$config['web']['base_url']?>invoice-fitur/<?=$invoice?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                                    <div class="toggle_title">
                                        <h4>Pilih Metode Pembayaran</h4><br>
                                        <p>Harga Fitur <?=ucfirst($action)?> : Rp <b><?=number_format($harga,0,',','.')?>,- Durasi <?=$durasi?> Hari</b></p>
                                    </div>
                                    <ul>
                                        <?
                                        $data_target_bank = mysqli_query($db, "SELECT * FROM bank_information WHERE status ='active' AND fitur = '1' ORDER by bank ASC");
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
                                        if($data_user['rows']['saldo_tersedia'] < $input['amount']){
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
                                    <div class="payment_info modules__content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn--md btn-primary">Bayar</button>
                                        </div>
                                    </div>
                                    </div>
                                    </form>
                                </div><!-- ends: .information_module-->
                            </div>
                </div><!-- ends: .row -->
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_menu_area -->
    </section><!-- ends: .dashboard-area -->
<?
     
	}
?>