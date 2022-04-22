<?php 

// Include configuration file 
include_once 'config.php'; 
require("../../web.php");
include "../../lib/class/class.phpmailer.php";

/* 
* Read POST data 
* reading posted data directly from $_POST causes serialization 
* issues with array data in POST. 
* Reading raw POST data from input stream instead. 
*/ 
$raw_post_data = file_get_contents('php://input'); 
$raw_post_array = explode('&', $raw_post_data); 
$myPost = array(); 
foreach ($raw_post_array as $keyval) { 
$keyval = explode ('=', $keyval); 
if (count($keyval) == 2) 
$myPost[$keyval[0]] = urldecode($keyval[1]); 
} 
// Read the post from PayPal system and add 'cmd' 
$req = 'cmd=_notify-validate'; 
if(function_exists('get_magic_quotes_gpc')) { 
$get_magic_quotes_exists = true; 
} 
foreach ($myPost as $key => $value) { 
if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
$value = urlencode(stripslashes($value)); 
} else { 
 $value = urlencode($value); 
$req .= "&$key=$value"; 
} 
/* 
* Post IPN data back to PayPal to validate the IPN data is genuine 
* Without this step, anyone can fake IPN data 
*/ 
$paypalURL = PAYPAL_URL; 
$ch = curl_init($paypalURL); 
if ($ch == FALSE) { 
return FALSE; 
} 
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); 
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $req); 
curl_setopt($ch, CURLOPT_SSLVERSION, 6); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1); 

// Set TCP timeout to 30 seconds 

curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: company-name')); 
$res = curl_exec($ch); 

/* 
* Inspect IPN validation result and act accordingly 
* Split response headers and payload, a better way for strcmp 
*/ 

$tokens = explode("\r\n\r\n", trim($res)); 
$res = trim(end($tokens)); 
if (strcmp($res, "VERIFIED") == 0 || strcasecmp($res, "VERIFIED") == 0) { 

// Retrieve transaction info from PayPal 
$item_number    = $_POST['item_number']; 
$name = $_GET['item_name']; 
$txn_id         = $_POST['txn_id']; 
$payment_gross     = $_POST['mc_gross']; 
$currency_code     = $_POST['mc_currency']; 


// Check if transaction data exists with the same TXN ID 

$prevPayment = $db->query("SELECT id FROM cart WHERE tx_id_paypal = '".$txn_id."'"); 
if($prevPayment->num_rows > 0){ 
exit(); 
}else{ 
    $num_char = 4;
    $kode_depo = substr($item_number, 0, $num_char);
    $potong = 3;
    $kode_ftr = substr($item_number, 0, $potong);
if($kode_depo == "DEPO"){
    $website = $model->db_query($db, "*", "website", "id = '1'");
     $data_targetss = $model->db_query($db, "*", "deposit", "kode_depo = '$item_number' ");
     $now = date("Y-m-d H:i:s");
     
    $input_post_orders_active = array(
        'status' => 'success'
        );
    $update_orders = $model->db_update($db, "deposit", $input_post_orders_active, "kode_depo = '".$item_number."' ");
    
    $user = $model->db_query($db, "*", "user", "id = '".$data_targetss['rows']['user_id']."'");
    $amount = $data_targetss['rows']['amount'];
    $saldo_user = $user['rows']['saldo_tersedia'];
    $input_post_user= array(
        'saldo_tersedia' => $saldo_user + $amount,
        );
    $update_user = $model->db_update($db, "user", $input_post_user, "id = '".$data_targetss['rows']['user_id']."' ");
    
     $update_history_pembayaran = array(
        'user_id' => $data_targetss['rows']['user_id'],
        'amount' => $data_targetss['rows']['amount'],
        'message' => 'Deposit Dengan ID #'.$item_number,
        'created_at' => $now
        );
        $model->db_insert($db, "history_pembayaran", $update_history_pembayaran);
        
    $email_depo = $model->db_query($db, "*", "email", "id = '6'");
    $user_depo = $model->db_query($db, "*", "user", "id = '".$data_targetss['rows']['user_id']."' ");
    $amount = number_format($data_targetss['rows']['amount'],0,',','.');
    $pertama = str_replace("{{username}}",$user_depo['rows']['username'],$email_depo['rows']['email']);
    $kedua = str_replace("{{jumlah_pembayaran}}",'Rp '.$amount.' ,-',$pertama);
    $invoice_depo = str_replace("{{invoice_id}}",$data_targetss['rows']['kode_depo'],$kedua);
    $subject_depo = 'Deposit Berhasil #'.$data_targetss['rows']['kode_depo'];
    $ke_user_depo = decrypt($user_depo['rows']['email']);
    $nama_user_depo = $user_depo['rows']['nama'];
    kirim_email($ke_user_depo, $nama_user_depo, $invoice_depo, $subject_depo);
    
} elseif($kode_ftr == "FTR"){
    $website = $model->db_query($db, "*", "website", "id = '1'");
    $data_targetss = $model->db_query($db, "*", "fitur_order", "kode_invoice = '$item_number' AND status = 'PENDING' ");
    $now = date("Y-m-d H:i:s");
    $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
    $input_post_orders_active = array(
            'status' => 'PAID',
        );
        $update_orders = $model->db_update($db, "fitur_order", $input_post_orders_active, "kode_invoice = '$item_number' ");
        
        if($data_targetss['rows']['order_fitur'] == 'premium'){
            $expired_premi = date('Y-m-d H:i:s',strtotime('+'.$website['rows']['durasi_fitur_premium'].' Day',strtotime($now)));
            $update_fitur = array(
            'premium' => '1',
            'expired_premium' => $expired_premi,
            );
            $update_services = $model->db_update($db, "services", $update_fitur, "id = '".$data_targetss['rows']['service_id']."' ");
           
        }
        if($data_targetss['rows']['order_fitur'] == 'featured'){
            $expired_featured = date('Y-m-d H:i:s',strtotime('+'.$website['rows']['durasi_fitur_featured'].' Day',strtotime($now)));
            $update_fitur = array(
            'featured' => '1',
            'expired_featured' => $expired_featured,
            );
            $update_services = $model->db_update($db, "services", $update_fitur, "id = '".$data_targetss['rows']['service_id']."' "); 
            
        }
        $input_post_penghasilan_admin = array(
        'dari_fitur' => $data_targetss['rows']['amount'],
        'order_id' => $data_targetss['rows']['id'],
        'created_at' => $now
        );
        $insert = $model->db_insert($db, "penghasilan_admin", $input_post_penghasilan_admin);
        $update_history_pembayaran = array(
        'user_id' => $data_targetss['rows']['user_id'],
        'amount' => $data_targetss['rows']['amount'],
        'message' => 'Pembelian Fitur #'.$data_targetss['rows']['order_fitur']." Untuk Layananan - ".$data_services['rows']['nama_layanan'],
        'created_at' => $now
        );
        
} else {
   $website = $model->db_query($db, "*", "website", "id = '1'");
    $data_targetss = $model->db_query($db, "*", "cart", "kode_invoice = '$item_number' AND pembayaran_id_bank = '7' ");
    $now = date("Y-m-d H:i:s");
     $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
     $jangka_waktu = $data_services['rows']['jangka_waktu'];
     $send_before = date('Y-m-d H:i:s',strtotime('+'.$jangka_waktu.' Day',strtotime($now)));
     
     $input_post_orders_active = array(
            'status' => 'active',
            'created_at' => $now,
            'send_before' => $send_before
            );
        $update_orders = $model->db_update($db, "orders", $input_post_orders_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' ");
        
        $input_post_update_active = array(
        'pembayaran_id_bank' => '7',
        'status' => 'success'
        );
        $update_cart = $model->db_update($db, "cart", $input_post_update_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' "); 
        
         $order_detail = $model->db_query($db, "*", "orders", "kode_unik = '".$data_targetss['rows']['kode_unik']."'");
         $input_post_penghasilan_admin = array(
        'admin_fee' => $website['rows']['admin_fee'],
        'order_id' => $order_detail['rows']['id'],
        'created_at' => $now
        );
        $insert = $model->db_insert($db, "penghasilan_admin", $input_post_penghasilan_admin);
        if($update_orders == true && $update_cart == true && $insert == true){
            $update_history_pembayaran = array(
            'user_id' => $data_targetss['rows']['buyer_id'],
            'amount' => $data_targetss['rows']['total_price_admin'],
            'message' => 'Pembelian Produk #'.$order_detail['rows']['id']." - ".$data_services['rows']['nama_layanan'],
            'created_at' => $now
            );
            $model->db_insert($db, "history_pembayaran", $update_history_pembayaran);
            $update_notifikasi = array(
            'buyer_id' => $data_targetss['rows']['buyer_id'],
            'seller_id' => $data_services['rows']['author'],
            'service_id' => $data_targetss['rows']['service_id'],
            'type' => 'pembelian',
            'go' => "show-sales/".$order_detail['rows']['id'],
            'created_at' => $now
            );
            $model->db_insert($db, "notifikasi", $update_notifikasi);
            
            $email_invoice = $model->db_query($db, "*", "email", "id = '7'");
            $formats = $email_invoice['rows']['email'];
            // email lama
            // $pisah_pertama = explode("{{id_invoice}}", $formats);
            // $pisah_kedua = explode("{{amount}}", $pisah_pertama[1]);
            // $pisah_ketiga = explode("{{layanan}}", $pisah_kedua[1]);
            // $pisah_keempat = explode("{{harga_layanan}}", $pisah_ketiga[1]);
            // $pisah_kelima = explode("{{quantity}}", $pisah_keempat[1]);
            // $pisah_keenam = explode("{{admin_fee}}", $pisah_kelima[1]);
            // $invoice_mails = $pisah_pertama[0].$data_targetss['rows']['kode_invoice'].$pisah_kedua[0].$data_targetss['rows']['total_price_admin'].$pisah_ketiga[0].$data_services['rows']['nama_layanan'].$pisah_keempat[0].$data_services['rows']['price'].$pisah_kelima[0].$data_targetss['rows']['quantity'].$pisah_keenam[0].$website['rows']['admin_fee'].$pisah_keenam[1];
           
            // $invoice_mail = str_replace("{{logo_website}}",$config['web']['base_url']."file-photo/website/".$website['rows']['logo_web'],$invoice_mails);
            // end email lama
            $user_pembeli = $model->db_query($db, "*", "user", "id = '".$data_targetss['rows']['buyer_id']."' ");
            $ke_pembeli = decrypt($user_pembeli['rows']['email']);
            $nama_pembeli  = $user_pembeli['rows']['nama'];
            $subject_pembeli = "Pembayaran Berhasil Untuk Invoice #".$data_targetss['rows']['kode_invoice'];
            
            $satu = str_replace("{{logo_website}}",$config['web']['base_url']."file-photo/website/".$website['rows']['logo_web'],$formats);
                $dua = str_replace("{{id_invoice}}",$data_targetss['rows']['kode_invoice'],$satu);
                $tiga = str_replace("{{gif}}",$config['web']['base_url']."img/image-2.gif",$dua);
                $empat = str_replace("{{layanan}}",$data_services['rows']['nama_layanan'],$tiga);
                $lima = str_replace("{{harga_layanan}}",number_format($data_services['rows']['price'],0,',','.'),$empat);
                $enam = str_replace("{{admin_fee}}",number_format($website['rows']['admin_fee'],0,',','.'),$lima);
                $invoice_fix = str_replace("{{amount}}",number_format($data_targetss['rows']['total_price_admin'],0,',','.'),$enam);
             kirim_email($ke_pembeli, $nama_pembeli, $invoice_fix, $subject_pembeli);
            
            $email_orderan = $model->db_query($db, "*", "email", "id = '3'");
            $user_penjual = $model->db_query($db, "*", "user", "id = '".$data_services['rows']['author']."' ");			    
            $ke = decrypt($user_penjual['rows']['email']);
            $nama = $user_penjual['rows']['nama'];
            $format = $email_orderan['rows']['email'];
            $pisah = explode("{{link_penjualan}}", $format);
            $orderan_link = $pisah[0].$config['web']['base_url']."show-sales/".$order_detail['rows']['id'].$pisah[1];
            
            $subject = "Pesanan Baru";
			kirim_email($ke, $nama, $orderan_link, $subject); 
        }else {
            echo "Something wrong!";
        } 
}    
    
    
	
} 
} 
}
?>