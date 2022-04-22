<?php

namespace Midtrans;
require_once dirname(__FILE__) . '/../../../web.php';
require_once dirname(__FILE__) . '/../../Midtrans.php';
$midtrans = $model->db_query($db, "*", "payment_setting", "id = '1'");
//Set Your server key
Config::$serverKey = decrypt($midtrans['rows']['value_1']);

// Uncomment for production environment
Config::$isProduction = true;

// Uncomment to enable sanitization
Config::$isSanitized = true;

// Uncomment to enable 3D-Secure
Config::$is3ds = true;

$oid = mysqli_real_escape_string($db, $_GET['oid']);
$amount = mysqli_real_escape_string($db, $_GET['amount']);
// Required
$transaction_details = array(
    'order_id' => $oid,
    'gross_amount' => $amount, // no decimal allowed for creditcard
);
$num_char = 4;
$kode_depo = substr($oid, 0, $num_char);
$potong = 3;
$kode_ftr = substr($oid, 0, $potong);
// Fill SNAP API parameter
$params = array(
    'transaction_details' => $transaction_details,
);


try {
    // Get Snap Payment Page URL
    $paymentUrl = Snap::createTransaction($params)->redirect_url;
    // $token = Snap::createTransaction($params)->token; // token
    if($kode_depo == "DEPO"){
        $input_id_bank = array(
        'url_coinpayment' => $paymentUrl,
        );
        $update_deposit = $model->db_update($db, "deposit", $input_id_bank, "kode_depo = '$oid' ");
    } elseif($kode_ftr == "FTR"){
        $input_id_bank = array(
        'url_pembayaran' => $paymentUrl,
        );
        $update_deposit = $model->db_update($db, "fitur_order", $input_id_bank, "kode_invoice = '$oid' ");
    } else {
       $input_id_bank = array(
        'url_coinpayment' => $paymentUrl,
        'pembayaran_id_bank' => '6'
        );
        $update_cart = $model->db_update($db, "cart", $input_id_bank, "kode_invoice = '$oid' "); 
    }
     
    // Redirect to Snap Payment Page
    $invoic_token = "$oid".pay."$token";
    // exit(header("Location: ".$config['web']['base_url']."checkout/midtrans/".$invoic_token));
    header('Location: ' . $paymentUrl);
}
catch (Exception $e) {
    echo $e->getMessage();
}
