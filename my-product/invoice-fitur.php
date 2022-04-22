<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
require '../lib/csrf_token.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
$ipaymu = $model->db_query($db, "*", "payment_setting", "id = '2'");
$cp = $model->db_query($db, "*", "payment_setting", "id = '4'");
if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit("No direct script access allowed!!");
}
if (!isset($_GET['query_invoice'])) {
	exit("No direct script access allowed!!");
}
$id_invoice = mysqli_real_escape_string($db, $_GET['query_invoice']);
$data_targetss = $model->db_query($db, "*", "fitur_order", "kode_invoice = '".mysqli_real_escape_string($db, $_GET['query_invoice'])."' AND user_id = '".$login['id']."' ");
$service_information = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
$price = $data_targetss['rows']['amount'];

$id_bank = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['filter_opt']))));
$now = date("Y-m-d H:i:s");



if(isset($_POST['filter_opt'])){

if($id_bank == "saldo_tersedia"){
    $website = $model->db_query($db, "*", "website", "id = '1'");
    $data_targetss = $model->db_query($db, "*", "fitur_order", "kode_invoice = '".mysqli_real_escape_string($db, $_GET['query_invoice'])."' AND user_id = '".$login['id']."' ");
   $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
   $now = date("Y-m-d H:i:s");
   $price = $data_targetss['rows']['amount'];
   $user_buyer = $model->db_query($db, "*", "user", "id = '".$login['id']."' ");
    if($user_buyer['rows']['saldo_tersedia'] < $price){
        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Saldo Anda Tidak Cukup Untuk Pembayaran Ini! ');
        exit(header("Location: ".$config['web']['base_url']."checkout-fitur/".$id_invoice));
    } else {
        
        
        $input_post_penghasilan_admin = array(
            'user_id' => $login['id'],
            'amount' => $price,
            'message' => 'Pembelian Fitur #'.$data_targetss['rows']['order_fitur']." Untuk Layananan - ".$data_services['rows']['nama_layanan'],
            'created_at' => $now
            );
            $insert_pembayaran = $model->db_insert($db, "history_pembayaran", $input_post_penghasilan_admin);
        if($insert_pembayaran == false){
            $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pembelian Fitur '.$data_targetss['rows']['order_fitur'].' Gagal! :( Error Code D-24');
            exit(header("Location: ".$config['web']['base_url']."my-product/"));
         } 
        $update_saldo_user = $db->query("UPDATE user set saldo_tersedia = saldo_tersedia-$price WHERE id = '".$login['id']."' ");
            if($update_saldo_user == true){
                if($data_targetss['rows']['order_fitur'] == 'premium'){
                $expired_premi = date('Y-m-d H:i:s',strtotime('+'.$website['rows']['durasi_fitur_premium'].' Day',strtotime($now)));
                $update_fitur = array(
                'premium' => '1',
                'expired_premium' => $expired_premi, 
                );
                $update_services = $model->db_update($db, "services", $update_fitur, "id = '".$data_targetss['rows']['service_id']."' ");
                if($update_services == false){
                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pembelian Fitur '.$data_targetss['rows']['order_fitur'].' Gagal! :( Error Code D-231');
                exit(header("Location: ".$config['web']['base_url']."my-product/"));
                }
            }
            if($data_targetss['rows']['order_fitur'] == 'featured'){
                $expired_featured = date('Y-m-d H:i:s',strtotime('+'.$website['rows']['durasi_fitur_featured'].' Day',strtotime($now)));
                $update_fitur = array(
                'featured' => '1',
                'expired_featured' => $expired_featured,
                );
                $update_services = $model->db_update($db, "services", $update_fitur, "id = '".$data_targetss['rows']['service_id']."' "); 
                if($update_services == false){
                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pembelian Fitur '.$data_targetss['rows']['order_fitur'].' Gagal! :( Error Code D-232');
                exit(header("Location: ".$config['web']['base_url']."my-product/"));
                }
            }
            $input_post_orders_active = array(
            'status' => 'PAID',
            'pembayaran_id' => 'saldo_tersedia'
            );
            $update_orders = $model->db_update($db, "fitur_order", $input_post_orders_active, "id = '".$data_targetss['rows']['id']."' ");
            if($update_orders == false){
                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pembelian Fitur '.$data_targetss['rows']['order_fitur'].' Gagal! :( Error Code D-21');
                exit(header("Location: ".$config['web']['base_url']."my-product/"));
            }
            $input_post_penghasilan_admin = array(
            'dari_fitur' => $price,
            'order_id' => $data_targetss['rows']['id'],
            'created_at' => $now
            );
            $insert_pembayarans = $model->db_insert($db, "penghasilan_admin", $input_post_penghasilan_admin);
            
           
            if($insert_pembayarans == false){
                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pembelian Fitur '.$data_targetss['rows']['order_fitur'].' Gagal! :( Error Code D-212');
                exit(header("Location: ".$config['web']['base_url']."my-product/"));
             }
            $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Pembelian Fitur '.$data_targetss['rows']['order_fitur'].' Berhasil ^.^');
            exit(header("Location: ".$config['web']['base_url']."my-product/"));
        }   
            
        
    }
    
} elseif($id_bank == "5"){ // perfectmoney
    
    $website = $model->db_query($db, "*", "website", "id = '1'");
    $input_post_orders_active = array(
    'pembayaran_id' => $id_bank
    );
    $update_orders = $model->db_update($db, "fitur_order", $input_post_orders_active, "id = '".$data_targetss['rows']['id']."' ");
    exit(header("Location: ".$config['web']['base_url']."checkout/perfectmoney/".$id_invoice));
            
    
} elseif($id_bank == "6"){ //midtrans

$website = $model->db_query($db, "*", "website", "id = '1'");
$input_post_orders_active = array(
'pembayaran_id' => $id_bank
);
$update_orders = $model->db_update($db, "fitur_order", $input_post_orders_active, "id = '".$data_targetss['rows']['id']."' ");

$price = $data_targetss['rows']['amount'];
exit(header("Location: ".$config['web']['base_url']."midtrans/proses/snap-redirect/proses.php?oid=".$id_invoice."&amount=".$price));
        

} elseif($id_bank == "7"){ // paypal
    
    $website = $model->db_query($db, "*", "website", "id = '1'");
     $input_post_orders_active = array(
    'pembayaran_id' => $id_bank
    );
    $update_orders = $model->db_update($db, "fitur_order", $input_post_orders_active, "id = '".$data_targetss['rows']['id']."' ");
    exit(header("Location: ".$config['web']['base_url']."checkout/paypal/".$id_invoice));
            
    
} elseif($id_bank == "8"){ // BTC
    
    $website = $model->db_query($db, "*", "website", "id = '1'");
    
    $btc = $model->db_query($db, "*", "bank_information", "id = '$id_bank'");
    $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
    $price = $data_targetss['rows']['amount'];
    $total_price_dalam_dollar = $price / $btc['rows']['rate_dollar'];
    $buyer_btc =  $model->db_query($db, "*", "user", "id = '".$data_targetss['rows']['user_id']."'");

/*
	CoinPayments.net API Example
	Copyright 2014-2018 CoinPayments.net. All rights reserved.	
	License: GPLv2 - http://www.gnu.org/licenses/gpl-2.0.txt
*/
	require('../coinpayments/coinpayments.inc.php');
	$cps = new CoinPaymentsAPI();
	$cps->Setup(decrypt($cp['rows']['value_2']), decrypt($cp['rows']['value_1']));

	$req = array(
		'amount' => round($total_price_dalam_dollar, 2),
		'currency1' => 'USD',
		'currency2' => 'BTC',
		'buyer_email' => decrypt($buyer_btc['rows']['email']),
		'item_name' => 'Pembelian Fitur '.$data_targetss['rows']['order_fitur'],
		'item_number' => mysqli_real_escape_string($db, $_GET['query_invoice']),
		'address' => '', // leave blank send to follow your settings on the Coin Settings page
		'ipn_url' => $config['web']['base_url'].'coinpayments/ipnhandler_fiutr_btc.php',
		'success_url' => $config['web']['base_url'].'payment-status/success.php',
		'cancel_url' => $config['web']['base_url'],
	);
	// See https://www.coinpayments.net/apidoc-create-transaction for all of the available fields
			
	$result = $cps->CreateTransaction($req);
	if ($result['error'] == 'ok') {
		$le = php_sapi_name() == 'cli' ? "\n" : '<br />';
		$input_id_bank = array(
        'pembayaran_id' => $id_bank,
        'tx_id' => $result['result']['txn_id'],
        'url_pembayaran' => $result['result']['status_url'],
        );
        $update_cart = $model->db_update($db, "fitur_order", $input_id_bank, "id = '".$data_targetss['rows']['id']."' ");
		exit(header("Location: ".$result['result']['checkout_url']));
	} else {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Terjadi Kesalahan, Silahkan Hubungi Admin Website! Code (01)');
             exit(header("Location: ".$config['web']['base_url']."checkout-fitur/".$id_invoice));
	}
     
    
} elseif($id_bank == "11"){ // IPAYMU QRIS

         $website = $model->db_query($db, "*", "website", "id = '1'");
       $now = date("Y-m-d H:i:s");
       $user_buyer = $model->db_query($db, "*", "user", "id = '".$login['id']."' ");
       $product = 'Pembelian Fitur '.$data_targetss['rows']['order_fitur'];
        // SAMPLE HIT API iPaymu v2 PHP //

        $va           = decrypt($ipaymu['rows']['value_1']); //get on iPaymu dashboard
        $secret       = decrypt($ipaymu['rows']['value_2']); //get on iPaymu dashboard
    
        $url          = 'https://my.ipaymu.com/api/v2/payment'; //url
        $method       = 'POST'; //method
    
        //Request Body//
        $body['product']    = array($product);
        $body['qty']        = array('1');
        $body['price']      = array($price);
        $body['returnUrl']  = $config['web']['base_url'].'payment-status/fitur-ipaymu-success.php';
        $body['cancelUrl']  = $config['web']['base_url'].'payment-status/fitur-ipaymu-cancel.php';
        $body['notifyUrl']  = $config['web']['base_url'].'payment-status/fitur-ipaymu.php';
        $body['buyerName']  = $user_buyer['rows']['nama'];
        $body['buyerEmail']  = decrypt($user_buyer['rows']['email']);
        $body['buyerPhone']  = decrypt($user_buyer['rows']['no_hp']);
        $body['paymentMethod']  = 'qris';
        //End Request Body//
    
        //Generate Signature
        // *Don't change this
        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $secret;
        $signature    = hash_hmac('sha256', $stringToSign, $secret);
        $timestamp    = Date('YmdHis');
        //End Generate Signature
    
    
        $ch = curl_init($url);
    
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );
    
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $err = curl_error($ch);
        $ret = curl_exec($ch);
        curl_close($ch);
        if($err) {
            echo $err;
        } else {
    
            //Response
            $ret = json_decode($ret);
            if($ret->Status == 200) {
                $sessionId  = $ret->Data->SessionID;
                $url        =  $ret->Data->Url;
                $input_id_bank = array(
                'pembayaran_id' => $id_bank,
                'tx_id' => $sessionId,
                'url_pembayaran' => $url,
                );
                $update_cart = $model->db_update($db, "fitur_order", $input_id_bank, "id = '".$data_targetss['rows']['id']."' ");
                
                header('Location:' . $url);
            } else {
                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Terjadi Kesalahan, Silahkan Hubungi Admin Website! Code (03)');
             exit(header("Location: ".$config['web']['base_url']."checkout-fitur/".$id_invoice));
            }
            //End Response
        }


    
} elseif($id_bank == "12"){ // IPAYMU VA

         $website = $model->db_query($db, "*", "website", "id = '1'");
       $now = date("Y-m-d H:i:s");
       $user_buyer = $model->db_query($db, "*", "user", "id = '".$login['id']."' ");
       $product = 'Pembelian Fitur '.$data_targetss['rows']['order_fitur'];
        // SAMPLE HIT API iPaymu v2 PHP //

        $va           = decrypt($ipaymu['rows']['value_1']); //get on iPaymu dashboard
        $secret       = decrypt($ipaymu['rows']['value_2']); //get on iPaymu dashboard
    
        $url          = 'https://my.ipaymu.com/api/v2/payment'; //url
        $method       = 'POST'; //method
    
        //Request Body//
        $body['product']    = array($product);
        $body['qty']        = array('1');
        $body['price']      = array($price);
        $body['returnUrl']  = $config['web']['base_url'].'payment-status/fitur-ipaymu-success.php';
        $body['cancelUrl']  = $config['web']['base_url'].'payment-status/fitur-ipaymu-cancel.php';
        $body['notifyUrl']  = $config['web']['base_url'].'payment-status/fitur-ipaymu.php';
        $body['buyerName']  = $user_buyer['rows']['nama'];
        $body['buyerEmail']  = decrypt($user_buyer['rows']['email']);
        $body['buyerPhone']  = decrypt($user_buyer['rows']['no_hp']);
        $body['paymentMethod']  = 'va';
        //End Request Body//
    
        //Generate Signature
        // *Don't change this
        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $secret;
        $signature    = hash_hmac('sha256', $stringToSign, $secret);
        $timestamp    = Date('YmdHis');
        //End Generate Signature
    
    
        $ch = curl_init($url);
    
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );
    
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $err = curl_error($ch);
        $ret = curl_exec($ch);
        curl_close($ch);
        if($err) {
            echo $err;
        } else {
    
            //Response
            $ret = json_decode($ret);
            if($ret->Status == 200) {
                $sessionId  = $ret->Data->SessionID;
                $url        =  $ret->Data->Url;
                $input_id_bank = array(
                'pembayaran_id' => $id_bank,
                'tx_id' => $sessionId,
                'url_pembayaran' => $url,
                );
                $update_cart = $model->db_update($db, "fitur_order", $input_id_bank, "id = '".$data_targetss['rows']['id']."' ");
                
                header('Location:' . $url);
            } else {
                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Terjadi Kesalahan, Silahkan Hubungi Admin Website! Code (03)');
             exit(header("Location: ".$config['web']['base_url']."checkout-fitur/".$id_invoice));
            }
            //End Response
        }


    
} elseif($id_bank == "15"){ // LTC
    
    $website = $model->db_query($db, "*", "website", "id = '1'");
    
    $btc = $model->db_query($db, "*", "bank_information", "id = '$id_bank'");
    $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
    $price = $data_targetss['rows']['amount'];
    $total_price_dalam_dollar = $price / $btc['rows']['rate_dollar'];
    $buyer_btc =  $model->db_query($db, "*", "user", "id = '".$data_targetss['rows']['user_id']."'");

/*
	CoinPayments.net API Example
	Copyright 2014-2018 CoinPayments.net. All rights reserved.	
	License: GPLv2 - http://www.gnu.org/licenses/gpl-2.0.txt
*/
	require('../coinpayments/coinpayments.inc.php');
	$cps = new CoinPaymentsAPI();
	$cps->Setup(decrypt($cp['rows']['value_2']), decrypt($cp['rows']['value_1']));

	$req = array(
		'amount' => round($total_price_dalam_dollar, 2),
		'currency1' => 'USD',
		'currency2' => 'LTC',
		'buyer_email' => decrypt($buyer_btc['rows']['email']),
		'item_name' => 'Pembelian Fitur '.$data_targetss['rows']['order_fitur'],
		'item_number' => mysqli_real_escape_string($db, $_GET['query_invoice']),
		'address' => '', // leave blank send to follow your settings on the Coin Settings page
		'ipn_url' => $config['web']['base_url'].'coinpayments/ipnhandler_fiutr_btc.php',
		'success_url' => $config['web']['base_url'].'payment-status/success.php',
		'cancel_url' => $config['web']['base_url'],
	);
	// See https://www.coinpayments.net/apidoc-create-transaction for all of the available fields
			
	$result = $cps->CreateTransaction($req);
	if ($result['error'] == 'ok') {
		$le = php_sapi_name() == 'cli' ? "\n" : '<br />';
		$input_id_bank = array(
        'pembayaran_id' => $id_bank,
        'tx_id' => $result['result']['txn_id'],
        'url_pembayaran' => $result['result']['status_url'],
        );
        $update_cart = $model->db_update($db, "fitur_order", $input_id_bank, "id = '".$data_targetss['rows']['id']."' ");
		exit(header("Location: ".$result['result']['checkout_url']));
	} else {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Terjadi Kesalahan, Silahkan Hubungi Admin Website! Code (01)');
             exit(header("Location: ".$config['web']['base_url']."checkout-fitur/".$id_invoice));
	}
     
    
} elseif($id_bank == "13"){ // IPAYMU ALFA/INDOMART

         $website = $model->db_query($db, "*", "website", "id = '1'");
       $now = date("Y-m-d H:i:s");
       $user_buyer = $model->db_query($db, "*", "user", "id = '".$login['id']."' ");
       $product = 'Pembelian Fitur '.$data_targetss['rows']['order_fitur'];
        // SAMPLE HIT API iPaymu v2 PHP //

        $va           = decrypt($ipaymu['rows']['value_1']); //get on iPaymu dashboard
        $secret       = decrypt($ipaymu['rows']['value_2']); //get on iPaymu dashboard
    
        $url          = 'https://my.ipaymu.com/api/v2/payment'; //url
        $method       = 'POST'; //method
    
        //Request Body//
        $body['product']    = array($product);
        $body['qty']        = array('1');
        $body['price']      = array($price);
        $body['returnUrl']  = $config['web']['base_url'].'payment-status/fitur-ipaymu-success.php';
        $body['cancelUrl']  = $config['web']['base_url'].'payment-status/fitur-ipaymu-cancel.php';
        $body['notifyUrl']  = $config['web']['base_url'].'payment-status/fitur-ipaymu.php';
        $body['buyerName']  = $user_buyer['rows']['nama'];
        $body['buyerEmail']  = decrypt($user_buyer['rows']['email']);
        $body['buyerPhone']  = decrypt($user_buyer['rows']['no_hp']);
        $body['paymentMethod']  = 'cstore';
        //End Request Body//
    
        //Generate Signature
        // *Don't change this
        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $secret;
        $signature    = hash_hmac('sha256', $stringToSign, $secret);
        $timestamp    = Date('YmdHis');
        //End Generate Signature
    
    
        $ch = curl_init($url);
    
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );
    
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $err = curl_error($ch);
        $ret = curl_exec($ch);
        curl_close($ch);
        if($err) {
            echo $err;
        } else {
    
            //Response
            
            $ret = json_decode($ret);
            if($ret->Status == 200) {
                $sessionId  = $ret->Data->SessionID;
                $url        =  $ret->Data->Url;
                $input_id_bank = array(
                'pembayaran_id' => $id_bank,
                'tx_id' => $sessionId,
                'url_pembayaran' => $url,
                );
                $update_cart = $model->db_update($db, "fitur_order", $input_id_bank, "id = '".$data_targetss['rows']['id']."' ");
                
                header('Location:' . $url);
            } else {
                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Terjadi Kesalahan, Silahkan Hubungi Admin Website! Code (03)');
            exit(header("Location: ".$config['web']['base_url']."checkout-fitur/".$id_invoice));
            }
            //End Response
        }


    
} elseif($id_bank == "14"){ // IPAYMU BCA

        $website = $model->db_query($db, "*", "website", "id = '1'");
       $now = date("Y-m-d H:i:s");
       $user_buyer = $model->db_query($db, "*", "user", "id = '".$login['id']."' ");
       $product = 'Pembelian Fitur '.$data_targetss['rows']['order_fitur'];
        // SAMPLE HIT API iPaymu v2 PHP //

        $va           = decrypt($ipaymu['rows']['value_1']); //get on iPaymu dashboard
        $secret       = decrypt($ipaymu['rows']['value_2']); //get on iPaymu dashboard
    
        $url          = 'https://my.ipaymu.com/api/v2/payment'; //url
        $method       = 'POST'; //method
    
        //Request Body//
        $body['product']    = array($product);
        $body['qty']        = array('1');
        $body['price']      = array($price);
        $body['returnUrl']  = $config['web']['base_url'].'payment-status/fitur-ipaymu-success.php';
        $body['cancelUrl']  = $config['web']['base_url'].'payment-status/fitur-ipaymu-cancel.php';
        $body['notifyUrl']  = $config['web']['base_url'].'payment-status/fitur-ipaymu.php';
        $body['buyerName']  = $user_buyer['rows']['nama'];
        $body['buyerEmail']  = decrypt($user_buyer['rows']['email']);
        $body['buyerPhone']  = decrypt($user_buyer['rows']['no_hp']);
        $body['paymentMethod']  = 'banktransfer';
        //End Request Body//
    
        //Generate Signature
        // *Don't change this
        $jsonBody     = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $jsonBody));
        $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $secret;
        $signature    = hash_hmac('sha256', $stringToSign, $secret);
        $timestamp    = Date('YmdHis');
        //End Generate Signature
    
    
        $ch = curl_init($url);
    
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
            'va: ' . $va,
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );
    
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        curl_setopt($ch, CURLOPT_POST, count($body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $err = curl_error($ch);
        $ret = curl_exec($ch);
        curl_close($ch);
        if($err) {
            echo $err;
        } else {
    
            //Response
            $ret = json_decode($ret);
            if($ret->Status == 200) {
                $sessionId  = $ret->Data->SessionID;
                $url        =  $ret->Data->Url;
                $input_id_bank = array(
                'pembayaran_id' => $id_bank,
                'tx_id' => $sessionId,
                'url_pembayaran' => $url,
                );
                $update_cart = $model->db_update($db, "fitur_order", $input_id_bank, "id = '".$data_targetss['rows']['id']."' ");
                
                header('Location:' . $url);
            } else {
                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Terjadi Kesalahan, Silahkan Hubungi Admin Website! Code (03)');
            exit(header("Location: ".$config['web']['base_url']."checkout-fitur/".$id_invoice));
            }
            //End Response
        }


    
} else {
       $data_targetsa = $model->db_query($db, "*", "fitur_order", "kode_invoice = '".mysqli_real_escape_string($db, $_GET['query_invoice'])."' AND user_id = '".$login['id']."' ");
        $input_post_update = array(
        'pembayaran_id' => $id_bank,
        );
        $update = $model->db_update($db, "fitur_order", $input_post_update, "id = '".$data_targetsa['rows']['id']."' "); 
    }
    
}


$invoice_fitur = $model->db_query($db, "*", "fitur_order", "kode_invoice = '".mysqli_real_escape_string($db, $_GET['query_invoice'])."' AND user_id = '".$login['id']."' ");

if($invoice_fitur['rows']['pembayaran_id'] == null){
    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Anda Belum memilih Metode Pembayaran! ');
     exit(header("Location: ".$config['web']['base_url']."checkout-fitur/".$data_targetss['rows']['service_id']."/".$data_targetss['rows']['order_fitur']));
}




$website = $model->db_query($db, "*", "website", "id = '1'");
$data_targets = $model->db_query($db, "*", "fitur_order", "kode_invoice = '".mysqli_real_escape_string($db, $_GET['query_invoice'])."' AND user_id = '".$login['id']."' ");
if($data_targets['count'] == 0){
    exit(header("Location: ".$config['web']['base_url']));
}
if($data_targets['rows']['status'] == 'PENDING'){
    $status_invoice = 'Menunggu Pembayaran';
    $warna = "orange";
} elseif($data_targets['rows']['status'] == 'PAID'){
    $status_invoice = 'Pembayaran Diterima';
    $warna = "green";
} else {
    $status_invoice = 'Gagal Melakukan Pembayaran';
    $warna = "red";
}


$data_service = $model->db_query($db, "*", "services", "id = '".$data_targets['rows']['service_id']."' ");

$data_user = $model->db_query($db, "*", "user", "id = '".$login['id']."' ");


if($data_targets['rows']['pembayaran_id'] != 'saldo_tersedia'){
    $bank_infor = $model->db_query($db, "*", "bank_information", "id = '".$data_targets['rows']['pembayaran_id']."' ");
    $pembayaran = $bank_infor['rows']['bank'];
    $nama_admin = $bank_infor['rows']['nama_pemilik_bank'];
    $norek_admin = $bank_infor['rows']['no_rek'];
    
    $message_invoice = "Detail Pembayaran <br>".$pembayaran." ".$norek_admin." A.n ".$nama_admin."<br>Bayar Sebelum : ".format_date(substr($data_targets['rows']['expired'], 0, -9)).", ".substr($data_targets['rows']['expired'], 11, -3)." UTC+7";
} else {
    $pembayaran = $data_targets['rows']['pembayaran_id'];
    $message_invoice = "Detail Pembayaran : <br> Menggunakan Saldo Akun";
}
$expire_fitur = $data_targets['rows']['expired'];
$tgl_create = date('Y-m-d H:i:s',strtotime('-30 Minute',strtotime($expire_fitur)));
$title = "Invoice #".$id_invoice;

require '../template/header.php';
require '../template/header-dashboard.php';
?>

<section class="dashboard-area">
        <div class="dashboard_contents section--padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard_title_area">
                            <div class="">
                                <div class="dashboard__title">
                                    <h3>Invoice</h3>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <a href="#" class="btn btn-sm btn-secondary print_btn">
                                    <span class="icon-printer"></span>Print</a>
                                <a href="#" class="btn btn-sm btn-primary">Download</a>
                            </div>
                        </div>
                    </div><!-- ends: .col-md-12 -->
                    <div class="col-md-12">
                        <div class="invoice">
                            <div class="invoice__head">
                                <div class="invoice_logo">
                                    <img src="../img/logo.png" alt="">
                                </div>
                                <div class="info">
                                    <h4>Invoice</h4>
                                    <p>#<?=$data_targets['rows']['kode_invoice']?></p>
                                </div>
                            </div><!-- ends: .invoice__head -->
                            <div class="invoice__meta">
                                <div class="address">
                                    <h5 class="bold"><?=$website['rows']['title']?></h5>
                                    <p><?=$message_invoice?></p>
                                </div>
                                <div class="date_info">
                                    <p>
                                        <span>Tanggal Invoice</span><?= format_date(substr($tgl_create, 0, -9)); ?></p>
                                    <p>
                                        <span>Jatuh Tempo</span><?= format_date(substr($data_targets['rows']['expired'], 0, -9)).", ".substr($data_targets['rows']['expired'], -8); ?> UTC+7</p>
                                    <p>
                                        <span>Status</span><font color="<?=$warna?>"><?=$status_invoice?></font></p>
                                </div>
                            </div><!-- ends: .invoice__meta -->
                            <div class="table-responsive invoice__detail">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Pembeli</th>
                                            <th>Produk</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?= format_date(substr($tgl_create, 0, -9)); ?></td>
                                            <td class="author"><?=$data_user['rows']['nama']?></td>
                                            <td class="detail">
                                                Pembelian Fitur <?=$data_targets['rows']['order_fitur']?> Untuk <a href="<?=$config['web']['base_url']?>product/<?=$data_service['rows']['id']?>/<?=$data_service['rows']['url']?>"><?=$data_service['rows']['nama_layanan']?></a>
                                            </td>
                                            <td>Rp <?= number_format($data_targets['rows']['amount'],0,',','.') ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="pricing_info">
                                    
                                    <p class="bold">Total Pembayaran : Rp <?= number_format($data_targets['rows']['amount'],0,',','.') ?></p>
                                    
                                    <?
                                    if($data_targets['rows']['status'] == 'PENDING'){
                                    ?>
                                    <span>Note : Harap Transfer Sesuai Kode Unik</span>
                                    <?
                                    }
                                    ?>
                                </div>
                            </div><!-- ends: .invoice_detail -->
                        </div><!-- ends: .invoice -->
                    </div><!-- ends: .col-md-12 -->
                </div><!-- ends: .row --> 
            </div><!-- ends: .container -->
        </div><!-- ends: .dashboard_menu_area -->
    </section><!-- ends: .dashboard-area -->
    
<?php
require '../template/footer.php';

?>