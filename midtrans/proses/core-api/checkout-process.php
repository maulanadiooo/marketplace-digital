<?php

namespace Midtrans;
require_once dirname(__FILE__) . '/../../../web.php';
require_once dirname(__FILE__) . '/../../Midtrans.php';
$midtrans = $model->db_query($db, "*", "payment_setting", "id = '1'");
//Set Your server key
Config::$serverKey = decrypt($midtrans['rows']['value_1']);
//Set Your server key

// Uncomment for production environment
Config::$isProduction = true;

// Uncomment to enable sanitization
Config::$isSanitized = true;

// Uncomment to enable 3D-Secure
Config::$is3ds = true;

$oid = mysqli_real_escape_string($db, $_GET['oid']);
$amount = mysqli_real_escape_string($db, $_GET['amount']);
$action = mysqli_real_escape_string($db, $_GET['action']);
$cart = $model->db_query($db, "*", "cart", "kode_invoice = '$oid'");
$user = $model->db_query($db, "*", "user", "id = '".$cart['rows']['buyer_id']."'");
$services = $model->db_query($db, "*", "services", "id = '".$cart['rows']['service_id']."'");
if(!$oid && !$amount & !$action ){
    header('Location: ' . $config['web']['base_url']);
}

// Required
// Uncomment for production environment
// Config::$isProduction = true;

// Uncomment to enable sanitization
// Config::$isSanitized = true;

// Uncomment to enable idempotency-key, more details: (http://api-docs.midtrans.com/#idempotent-requests)
// Config::$paymentIdempotencyKey = "Unique-ID";

$transaction_details = array(
    'order_id' => $oid,
    'gross_amount' => $amount, // no decimal allowed for creditcard
);


$num_char = 4;
$kode_depo = substr($oid, 0, $num_char);
$potong = 3;
$kode_ftr = substr($oid, 0, $potong);
// Fill SNAP API parameter
if($action != 'gopay'){
    $transaction_data = array(
    'payment_type' => 'bank_transfer',
    'bank_transfer'  => array(
        'bank'          => $action, // optional acquiring bank, must be the same bank with get-token bank
    ),
    'transaction_details' => $transaction_details,
  );
} else {
    $transaction_data = array(
    'payment_type' => 'qris',
    'transaction_details' => $transaction_details,
  );
}


try {
    $response = CoreApi::charge($transaction_data);
    if($kode_depo == "DEPO"){
        $input_id_bank = array(
        'url_coinpayment' => $response->va_numbers[0]->va_number,
        );
        $update_deposit = $model->db_update($db, "deposit", $input_id_bank, "kode_depo = '$oid' ");
    } elseif($kode_ftr == "FTR"){
        $input_id_bank = array(
        'url_pembayaran' => $response->va_numbers[0]->va_number,
        );
        $update_deposit = $model->db_update($db, "fitur_order", $input_id_bank, "kode_invoice = '$oid' ");
    } else {
        if($action == 'gopay'){
            $now =  date('Y-m-d H:i:s');
             $input_id_bank = array(
            'expired_date' => date('Y-m-d H:i:s',strtotime('+15 Min',strtotime($now))),
            'url_coinpayment' => $response->actions[0]->url,
            'pembayaran_id_bank' => '17',
            'tx_id_paypal' => $response->payment_type
            );
            $update_cart = $model->db_update($db, "cart", $input_id_bank, "kode_invoice = '$oid' "); 
            header('Location: ' . $config['web']['base_url'].'checkout-invoice/'.$oid);
            
        
        } elseif($action == 'bni') {
            $now =  date('Y-m-d H:i:s');
            $input_id_bank = array(
            'expired_date' => date('Y-m-d H:i:s',strtotime('+1 Day',strtotime($now))),
            'url_coinpayment' => $response->va_numbers[0]->va_number,
            'pembayaran_id_bank' => '16',
            'tx_id_paypal' => $response->va_numbers[0]->bank
            );
            $update_cart = $model->db_update($db, "cart", $input_id_bank, "kode_invoice = '$oid' "); 
        header('Location: ' . $config['web']['base_url'].'checkout-invoice/'.$oid);
        }
       
    }
} catch (Exception $e) {
    echo $e->getMessage();
    die();
}
