<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/csrf_token.php';
include_once '../lib/class/class.phpmailer.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
$ipaymu = $model->db_query($db, "*", "payment_setting", "id = '2'");
$cp = $model->db_query($db, "*", "payment_setting", "id = '4'");
if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."sigin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}

if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."withdraw/"));
}else {
    $data = array('add_balance', 'filter_opt');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."add-balance/"));
	} else {
	    
	   
	    $validation = array( 
			'add_balance' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['add_balance'])))),
			'filter_opt' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['filter_opt'])))),
		);
		
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
			exit(header("Location: ".$config['web']['base_url']."add-balance/"));
		} else {
		    $status_depo_pm = $model->db_query($db, "*", "deposit", "user_id = '".$login['id']."' AND status = 'pending' AND id_bank = '5'");
            if($status_depo_pm['count'] > 0){
    		    $input_post_user= array(
                    'status' => 'error',
                    );
                $update_user = $model->db_update($db, "deposit", $input_post_user, "id = '".$status_depo_pm['rows']['id']."' ");
                
    		}
    		$status_depo_paypal = $model->db_query($db, "*", "deposit", "user_id = '".$login['id']."' AND status = 'pending' AND id_bank = '7'");
            if($status_depo_paypal['count'] > 0){
    		    $input_post_user= array(
                    'status' => 'error',
                    );
                $update_user = $model->db_update($db, "deposit", $input_post_user, "id = '".$status_depo_paypal['rows']['id']."' ");
                
    		}
    		$status_depo = $model->db_query($db, "*", "deposit", "user_id = '".$login['id']."' AND status = 'pending'");
		    if ($validation['add_balance'] < $website['rows']['min_depo']) {
    			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Minimal Penambahan Saldo Adalah Rp '.number_format($website['rows']['min_depo'],0,',','.'));
    			exit(header("Location: ".$config['web']['base_url']."add-balance/"));
    		} elseif($status_depo['count'] > 0 ){
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Deposit Sebelumnya Masih Belum Kamu Bayar, Selesaikan Dulu Ya ^.^ ');
    			exit(header("Location: ".$config['web']['base_url']."invoice_depo/".$status_depo['rows']['kode_depo']));
    		} else {
    		    
                $id_invoice = 'DEPO'.rand(11111111,99999999);
    		    if($validation['filter_opt'] == "8"){ // BTC
                    
                    $now = date("Y-m-d H:i:s");
    		        $expire_depo = date('Y-m-d H:i:s',strtotime('+24 Hour',strtotime($now)));
                    $input_post = array(
                    'kode_depo' => $id_invoice,
    		        'user_id' => $login['id'],
    		        'id_bank' => $validation['filter_opt'],
    		        'amount' => $validation['add_balance'],
    		        'status' => 'pending',
    		        'created_at' => date('Y-m-d H:i:s'),
    		        'expired_at' => $expire_depo,
    		        );
    		        $insert = $model->db_insert($db, "deposit", $input_post);
                    
                    $buyer_btc =  $model->db_query($db, "*", "user", "id = '".$login['id']."'");
                    $website = $model->db_query($db, "*", "website", "id = '1'");
                    
                    $btc = $model->db_query($db, "*", "bank_information", "id = '".$validation['filter_opt']."'");
                    
                    $total_price_dalam_dollar = $validation['add_balance'] / $btc['rows']['rate_dollar'];
                    // exit(header("Location: ".$config['web']['base_url']."checkout/paypal/".$id_invoice));
                
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
                		'buyer_email' => $buyer_btc['rows']['email'],
                		'item_name' => 'Deposit '.$buyer_btc['rows']['username'],
                		'item_number' => $insert,
                		'address' => '', // leave blank send to follow your settings on the Coin Settings page
                		'ipn_url' => $config['web']['base_url'].'coinpayments/ipnhandler_deposit_btc.php',
                		'success_url' => $config['web']['base_url'].'payment-status/success.php',
                		'cancel_url' => $config['web']['base_url'].'add-balance',
                	);
                	// See https://www.coinpayments.net/apidoc-create-transaction for all of the available fields
                			
                	$result = $cps->CreateTransaction($req);
                	if ($result['error'] == 'ok') {
                		$le = php_sapi_name() == 'cli' ? "\n" : '<br />';
                		$input_id_bank = array(
                        'tx_id' => $result['result']['txn_id'],
                        'url_coinpayment' => $result['result']['status_url'],
                        );
                        $update_cart = $model->db_update($db, "deposit", $input_id_bank, "id = '$insert' ");
                        
                        
                        
                		exit(header("Location: ".$result['result']['checkout_url']));
                	} else {
                		echo "gagal";
                	}
                     
                    
                } elseif($validation['filter_opt'] == "15"){ // LTC
                    
                    $now = date("Y-m-d H:i:s");
    		        $expire_depo = date('Y-m-d H:i:s',strtotime('+24 Hour',strtotime($now)));
                    $input_post = array(
                    'kode_depo' => $id_invoice,
    		        'user_id' => $login['id'],
    		        'id_bank' => $validation['filter_opt'],
    		        'amount' => $validation['add_balance'],
    		        'status' => 'pending',
    		        'created_at' => date('Y-m-d H:i:s'),
    		        'expired_at' => $expire_depo,
    		        );
    		        $insert = $model->db_insert($db, "deposit", $input_post);
                    
                    $buyer_btc =  $model->db_query($db, "*", "user", "id = '".$login['id']."'");
                    $website = $model->db_query($db, "*", "website", "id = '1'");
                    
                    $btc = $model->db_query($db, "*", "bank_information", "id = '".$validation['filter_opt']."'");
                    
                    $total_price_dalam_dollar = $validation['add_balance'] / $btc['rows']['rate_dollar'];
                    // exit(header("Location: ".$config['web']['base_url']."checkout/paypal/".$id_invoice));
                
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
                		'buyer_email' => $buyer_btc['rows']['email'],
                		'item_name' => 'Deposit '.$buyer_btc['rows']['username'],
                		'item_number' => $insert,
                		'address' => '', // leave blank send to follow your settings on the Coin Settings page
                		'ipn_url' => $config['web']['base_url'].'coinpayments/ipnhandler_deposit_ltc.php',
                		'success_url' => $config['web']['base_url'].'payment-status/success.php',
                		'cancel_url' => $config['web']['base_url'].'add-balance',
                	);
                	// See https://www.coinpayments.net/apidoc-create-transaction for all of the available fields
                			
                	$result = $cps->CreateTransaction($req);
                	if ($result['error'] == 'ok') {
                		$le = php_sapi_name() == 'cli' ? "\n" : '<br />';
                		$input_id_bank = array(
                        'tx_id' => $result['result']['txn_id'],
                        'url_coinpayment' => $result['result']['status_url'],
                        );
                        $update_cart = $model->db_update($db, "deposit", $input_id_bank, "id = '$insert' ");
                        
                        
                		exit(header("Location: ".$result['result']['checkout_url']));
                	} else {
                		echo "gagal";
                	}
                     
                    
                } elseif($validation['filter_opt'] == "11"){ // IPAYMU QRIS
                    
                    $now = date("Y-m-d H:i:s");
    		        $expire_depo = date('Y-m-d H:i:s',strtotime('+24 Hour',strtotime($now)));
                    $input_post_depo = array(
                    'kode_depo' => $id_invoice,
    		        'user_id' => $login['id'],
    		        'id_bank' => $validation['filter_opt'],
    		        'amount' => $validation['add_balance'],
    		        'status' => 'pending',
    		        'created_at' => date('Y-m-d H:i:s'),
    		        'expired_at' => $expire_depo,
    		        );
    		        $insert_depo = $model->db_insert($db, "deposit", $input_post_depo);
    		        
                     $website = $model->db_query($db, "*", "website", "id = '1'");
                    $data_targetss = $model->db_query($db, "*", "cart", "kode_invoice = '".mysqli_real_escape_string($db, $_GET['query_invoice'])."' AND buyer_id = '".$login['id']."' ");
                   $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
                   $jangka_waktu = $data_services['rows']['jangka_waktu'];
                   $now = date("Y-m-d H:i:s");
                   $send_before = date('Y-m-d H:i:s',strtotime('+'.$jangka_waktu.' Day',strtotime($now)));
                   $total_price_admin = $data_targetss['rows']['total_price_admin'];
                   $user_buyer = $model->db_query($db, "*", "user", "id = '".$login['id']."' ");
                   $nominal = number_format($validation['add_balance'],0,',','.');
                   $product = 'Deposit GubukDigital Rp '.$nominal;
                    // SAMPLE HIT API iPaymu v2 PHP //
            
                    $va           = decrypt($ipaymu['rows']['value_1']); //get on iPaymu dashboard
                    $secret       = decrypt($ipaymu['rows']['value_2']); //get on iPaymu dashboard
                
                    $url          = 'https://my.ipaymu.com/api/v2/payment'; //url
                    $method       = 'POST'; //method
                
                    //Request Body//
                    $body['product']    = array($product);
                    $body['qty']        = array('1');
                    $body['price']      = array($validation['add_balance']);
                    $body['returnUrl']  = $config['web']['base_url'].'payment-status/deposit-success';
                    $body['cancelUrl']  = $config['web']['base_url'].'payment-status/deposit-cancel';
                    $body['notifyUrl']  = $config['web']['base_url'].'payment-status/deposit-ipaymu.php';
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
                            'tx_id' => $sessionId,
                            'url_coinpayment' => $url,
                            );
                            $update_cart = $model->db_update($db, "deposit", $input_id_bank, "id = '".$insert_depo."' ");
                            
                            header('Location:' . $url);
                        } else {
                            $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Terjadi Kesalahan, Silahkan Hubungi Admin Website! Code (03)');
                        exit(header("Location: ".$config['web']['base_url']."checkout/".mysqli_real_escape_string($db, $_GET['query_invoice'])));
                        }
                        //End Response
                    }
            
            
                
            } elseif($validation['filter_opt'] == "12"){ // IPAYMU VA
                    
                    $now = date("Y-m-d H:i:s");
    		        $expire_depo = date('Y-m-d H:i:s',strtotime('+24 Hour',strtotime($now)));
                    $input_post_depo = array(
                    'kode_depo' => $id_invoice,
    		        'user_id' => $login['id'],
    		        'id_bank' => $validation['filter_opt'],
    		        'amount' => $validation['add_balance'],
    		        'status' => 'pending',
    		        'created_at' => date('Y-m-d H:i:s'),
    		        'expired_at' => $expire_depo,
    		        );
    		        $insert_depo = $model->db_insert($db, "deposit", $input_post_depo);
    		        
                     $website = $model->db_query($db, "*", "website", "id = '1'");
                    $data_targetss = $model->db_query($db, "*", "cart", "kode_invoice = '".mysqli_real_escape_string($db, $_GET['query_invoice'])."' AND buyer_id = '".$login['id']."' ");
                   $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
                   $jangka_waktu = $data_services['rows']['jangka_waktu'];
                   $now = date("Y-m-d H:i:s");
                   $send_before = date('Y-m-d H:i:s',strtotime('+'.$jangka_waktu.' Day',strtotime($now)));
                   $total_price_admin = $data_targetss['rows']['total_price_admin'];
                   $user_buyer = $model->db_query($db, "*", "user", "id = '".$login['id']."' ");
                   $nominal = number_format($validation['add_balance'],0,',','.');
                   $product = 'Deposit GubukDigital Rp '.$nominal;
                    // SAMPLE HIT API iPaymu v2 PHP //
            
                    $va           = decrypt($ipaymu['rows']['value_1']); //get on iPaymu dashboard
                    $secret       = decrypt($ipaymu['rows']['value_2']); //get on iPaymu dashboard
                
                    $url          = 'https://my.ipaymu.com/api/v2/payment'; //url
                    $method       = 'POST'; //method
                
                    //Request Body//
                    $body['product']    = array($product);
                    $body['qty']        = array('1');
                    $body['price']      = array($validation['add_balance']);
                    $body['returnUrl']  = $config['web']['base_url'].'payment-status/deposit-success';
                    $body['cancelUrl']  = $config['web']['base_url'].'payment-status/deposit-cancel';
                    $body['notifyUrl']  = $config['web']['base_url'].'payment-status/deposit-ipaymu.php';
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
                            'tx_id' => $sessionId,
                            'url_coinpayment' => $url,
                            );
                            $update_cart = $model->db_update($db, "deposit", $input_id_bank, "id = '".$insert_depo."' ");
                            
                            header('Location:' . $url);
                        } else {
                            $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Terjadi Kesalahan, Silahkan Hubungi Admin Website! Code (03)');
                        exit(header("Location: ".$config['web']['base_url']."checkout/".mysqli_real_escape_string($db, $_GET['query_invoice'])));
                        }
                        //End Response
                    }
            
            
                
            } elseif($validation['filter_opt'] == "13"){ // IPAYMU Alfa
                    
                    $now = date("Y-m-d H:i:s");
    		        $expire_depo = date('Y-m-d H:i:s',strtotime('+24 Hour',strtotime($now)));
                    $input_post_depo = array(
                    'kode_depo' => $id_invoice,
    		        'user_id' => $login['id'],
    		        'id_bank' => $validation['filter_opt'],
    		        'amount' => $validation['add_balance'],
    		        'status' => 'pending',
    		        'created_at' => date('Y-m-d H:i:s'),
    		        'expired_at' => $expire_depo,
    		        );
    		        $insert_depo = $model->db_insert($db, "deposit", $input_post_depo);
    		        
                     $website = $model->db_query($db, "*", "website", "id = '1'");
                    $data_targetss = $model->db_query($db, "*", "cart", "kode_invoice = '".mysqli_real_escape_string($db, $_GET['query_invoice'])."' AND buyer_id = '".$login['id']."' ");
                   $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
                   $jangka_waktu = $data_services['rows']['jangka_waktu'];
                   $now = date("Y-m-d H:i:s");
                   $send_before = date('Y-m-d H:i:s',strtotime('+'.$jangka_waktu.' Day',strtotime($now)));
                   $total_price_admin = $data_targetss['rows']['total_price_admin'];
                   $user_buyer = $model->db_query($db, "*", "user", "id = '".$login['id']."' ");
                   $nominal = number_format($validation['add_balance'],0,',','.');
                   $product = 'Deposit GubukDigital Rp '.$nominal;
                    // SAMPLE HIT API iPaymu v2 PHP //
            
                    $va           = decrypt($ipaymu['rows']['value_1']); //get on iPaymu dashboard
                    $secret       = decrypt($ipaymu['rows']['value_2']); //get on iPaymu dashboard
                
                    $url          = 'https://my.ipaymu.com/api/v2/payment'; //url
                    $method       = 'POST'; //method
                
                    //Request Body//
                    $body['product']    = array($product);
                    $body['qty']        = array('1');
                    $body['price']      = array($validation['add_balance']);
                    $body['returnUrl']  = $config['web']['base_url'].'payment-status/deposit-success';
                    $body['cancelUrl']  = $config['web']['base_url'].'payment-status/deposit-cancel';
                    $body['notifyUrl']  = $config['web']['base_url'].'payment-status/deposit-ipaymu.php';
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
                            'tx_id' => $sessionId,
                            'url_coinpayment' => $url,
                            );
                            $update_cart = $model->db_update($db, "deposit", $input_id_bank, "id = '".$insert_depo."' ");
                            
                            header('Location:' . $url);
                        } else {
                            $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Terjadi Kesalahan, Silahkan Hubungi Admin Website! Code (03)');
                        exit(header("Location: ".$config['web']['base_url']."checkout/".mysqli_real_escape_string($db, $_GET['query_invoice'])));
                        }
                        //End Response
                    }
            
            
                
            } elseif($validation['filter_opt'] == "14"){ // Iapymu BCA
                   
                    $now = date("Y-m-d H:i:s");
    		        $expire_depo = date('Y-m-d H:i:s',strtotime('+24 Hour',strtotime($now)));
                    $input_post_depo = array(
                    'kode_depo' => $id_invoice,
    		        'user_id' => $login['id'],
    		        'id_bank' => $validation['filter_opt'],
    		        'amount' => $validation['add_balance'],
    		        'status' => 'pending',
    		        'created_at' => date('Y-m-d H:i:s'),
    		        'expired_at' => $expire_depo,
    		        );
    		        $insert_depo = $model->db_insert($db, "deposit", $input_post_depo);
    		        
                     $website = $model->db_query($db, "*", "website", "id = '1'");
                    $data_targetss = $model->db_query($db, "*", "cart", "kode_invoice = '".mysqli_real_escape_string($db, $_GET['query_invoice'])."' AND buyer_id = '".$login['id']."' ");
                   $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
                   $jangka_waktu = $data_services['rows']['jangka_waktu'];
                   $now = date("Y-m-d H:i:s");
                   $send_before = date('Y-m-d H:i:s',strtotime('+'.$jangka_waktu.' Day',strtotime($now)));
                   $total_price_admin = $data_targetss['rows']['total_price_admin'];
                   $user_buyer = $model->db_query($db, "*", "user", "id = '".$login['id']."' ");
                   $nominal = number_format($validation['add_balance'],0,',','.');
                   $product = 'Deposit GubukDigital Rp '.$nominal;
                    // SAMPLE HIT API iPaymu v2 PHP //
            
                    $va           = decrypt($ipaymu['rows']['value_1']); //get on iPaymu dashboard
                    $secret       = decrypt($ipaymu['rows']['value_2']); //get on iPaymu dashboard
                
                    $url          = 'https://my.ipaymu.com/api/v2/payment'; //url
                    $method       = 'POST'; //method
                
                    //Request Body//
                    $body['product']    = array($product);
                    $body['qty']        = array('1');
                    $body['price']      = array($validation['add_balance']);
                    $body['returnUrl']  = $config['web']['base_url'].'payment-status/deposit-success';
                    $body['cancelUrl']  = $config['web']['base_url'].'payment-status/deposit-cancel';
                    $body['notifyUrl']  = $config['web']['base_url'].'payment-status/deposit-ipaymu.php';
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
                            'tx_id' => $sessionId,
                            'url_coinpayment' => $url,
                            );
                            $update_cart = $model->db_update($db, "deposit", $input_id_bank, "id = '".$insert_depo."' ");
                            
                            header('Location:' . $url);
                        } else {
                            $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Terjadi Kesalahan, Silahkan Hubungi Admin Website! Code (03)');
                        exit(header("Location: ".$config['web']['base_url']."checkout/".mysqli_real_escape_string($db, $_GET['query_invoice'])));
                        }
                        //End Response
                    }
            
            
                
            } elseif($validation['filter_opt'] == "6"){
                    
                $total_price_admin = $validation['add_balance'];
                $website = $model->db_query($db, "*", "website", "id = '1'");
                $now = date("Y-m-d H:i:s");
		        $expire_depo = date('Y-m-d H:i:s',strtotime('+24 Hour',strtotime($now)));
                $input_post_depo = array(
                'kode_depo' => $id_invoice,
		        'user_id' => $login['id'],
		        'id_bank' => $validation['filter_opt'],
		        'amount' => $validation['add_balance'],
		        'status' => 'pending',
		        'created_at' => date('Y-m-d H:i:s'),
		        'expired_at' => $expire_depo,
		        );
		        $insert_depo = $model->db_insert($db, "deposit", $input_post_depo);
                exit(header("Location: ".$config['web']['base_url']."midtrans/proses/snap-redirect/proses.php?oid=".$id_invoice."&amount=".$total_price_admin));
                
            } elseif($validation['filter_opt'] == "1"){
                    
                    $random = rand(111,999);
        		    $amount = $validation['add_balance']+$random;
        		    $now = date("Y-m-d H:i:s");
        		    $expire_depo = date('Y-m-d H:i:s',strtotime('+3 Hour',strtotime($now)));
        		    
        		    $input_post = array(
                    'kode_depo' => $id_invoice,
    		        'user_id' => $login['id'],
    		        'id_bank' => $validation['filter_opt'],
    		        'amount' => $amount,
    		        'status' => 'pending',
    		        'created_at' => date('Y-m-d H:i:s'),
    		        'expired_at' => $expire_depo,
    		        );
    		        $insert = $model->db_insert($db, "deposit", $input_post);
    		        if ($insert == true) {
    		            
    		           $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Permintaan Deposit Berhasil Dibuat, Hubungi Admin Jika Dalam 1 Jam setelah melakukan transfer deposit tidak masuk');
    		           exit(header("Location: ".$config['web']['base_url']."invoice_depo/".$id_invoice));
    		             
    		        } else {
    		            $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Permintaan Deposit Tidak Berhasil, Hubungi Admin Website!');
        			    exit(header("Location: ".$config['web']['base_url']."add-balance/"));
    		        }
                } elseif($validation['filter_opt'] == "5"){
                    
                    $now = date("Y-m-d H:i:s");
        		    $expire_depo = date('Y-m-d H:i:s',strtotime('+3 Hour',strtotime($now)));
                    $input_post = array(
                    'kode_depo' => $id_invoice,
    		        'user_id' => $login['id'],
    		        'id_bank' => $validation['filter_opt'],
    		        'amount' => $validation['add_balance'],
    		        'status' => 'pending',
    		        'created_at' => date('Y-m-d H:i:s'),
    		        'expired_at' => $expire_depo,
    		        );
    		        $insert = $model->db_insert($db, "deposit", $input_post);
                    exit(header("Location: ".$config['web']['base_url']."checkout/perfectmoney/".$input_post['kode_depo']));
                } elseif($validation['filter_opt'] == "7"){
                    
                    $now = date("Y-m-d H:i:s");
        		    $expire_depo = date('Y-m-d H:i:s',strtotime('+3 Hour',strtotime($now)));
                    $input_post = array(
                    'kode_depo' => $id_invoice,
    		        'user_id' => $login['id'],
    		        'id_bank' => $validation['filter_opt'],
    		        'amount' => $validation['add_balance'],
    		        'status' => 'pending',
    		        'created_at' => date('Y-m-d H:i:s'),
    		        'expired_at' => $expire_depo,
    		        );
    		        $insert = $model->db_insert($db, "deposit", $input_post);
                    exit(header("Location: ".$config['web']['base_url']."checkout/paypal/".$input_post['kode_depo']));
                } else {
                        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Metode Pembayaran Under Maintenance!');
                        exit(header("Location: ".$config['web']['base_url']."add-balance/"));
                }
    		    
    		    
    		}
		    
		    
		}
	    
	}
    
    
}