<?php    
require '../web.php';
require '../lib/class/class.phpmailer.php';
$cp = $model->db_query($db, "*", "payment_setting", "id = '4'");
    // Fill these in with the information from your CoinPayments.net account.
    $cp_merchant_id = decrypt($cp['rows']['value_3']);
    $cp_ipn_secret = decrypt($cp['rows']['value_4']);
    $cp_debug_email = decrypt($cp['rows']['value_5']);

    

    function errorAndDie($error_msg) {
        global $cp_debug_email;
        if (!empty($cp_debug_email)) {
            $report = 'Error: '.$error_msg."\n\n";
            $report .= "POST Data\n\n";
            foreach ($_POST as $k => $v) {
                $report .= "|$k| = |$v|\n";
            }
            mail($cp_debug_email, 'CoinPayments IPN Error', $report);
        }
        die('IPN Error: '.$error_msg);
    }

    if (!isset($_POST['ipn_mode']) || $_POST['ipn_mode'] != 'hmac') {
        errorAndDie('IPN Mode is not HMAC');
    }

    if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
        errorAndDie('No HMAC signature sent.');
    }

    $request = file_get_contents('php://input');
    if ($request === FALSE || empty($request)) {
        errorAndDie('Error reading POST data');
    }

    if (!isset($_POST['merchant']) || $_POST['merchant'] != trim($cp_merchant_id)) {
        errorAndDie('No or incorrect Merchant ID passed');
    }

    $hmac = hash_hmac("sha512", $request, trim($cp_ipn_secret));
    if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
    //if ($hmac != $_SERVER['HTTP_HMAC']) { <-- Use this if you are running a version of PHP below 5.6.0 without the hash_equals function
        errorAndDie('HMAC signature does not match');
    }

    // HMAC Signature verified at this point, load some variables.

    $ipn_type = $_POST['ipn_type'];
    $txn_id = $_POST['txn_id'];
    $item_name = $_POST['item_name'];
    $item_number = $_POST['item_number'];
    $amount1 = floatval($_POST['amount1']);
    $amount2 = floatval($_POST['amount2']);
    $currency1 = $_POST['currency1'];
    $currency2 = $_POST['currency2'];
    $status = intval($_POST['status']);
    $status_text = $_POST['status_text'];
    
    //These would normally be loaded from your database, the most common way is to pass the Order ID through the 'custom' POST field.
    $order_currency = 'USD';
    $btc = $model->db_query($db, "*", "bank_information", "id = '8'");

    //depending on the API of your system, you may want to check and see if the transaction ID $txn_id has already been handled before at this point

    // Check the original currency to make sure the buyer didn't change it.
    if ($currency1 != $order_currency) {
        errorAndDie('Original currency mismatch!');
    }
    $now = date("Y-m-d H:i:s");
    // Check amount against order total
 $data_targetss = $model->db_query($db, "*", "fitur_order", "tx_id = '$txn_id' ");
 if($data_targetss['count'] > 0){
    if ($status >= 100 || $status == 2) {
        $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
        $input_post_orders_active = array(
            'status' => 'PAID',
        );
        $update_orders = $model->db_update($db, "fitur_order", $input_post_orders_active, "tx_id = '$txn_id' ");
        
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
        
        
    } else if ($status < 0) {
        //payment error, this is usually final but payments will sometimes be reopened if there was no exchange rate conversion or with seller consent
        $data_targetss = $model->db_query($db, "*", "fitur_order", "tx_id = '$txn_id' ");
        $now = date("Y-m-d H:i:s");
        $input_post_orders_active = array(
        'status' => 'NOT PAID',
        );
        $update_orders = $model->db_update($db, "fitur_order", $input_post_orders_active, "tx_id = '$txn_id' ");
        
    } else if ($status == 0) {
        //payment error, this is usually final but payments will sometimes be reopened if there was no exchange rate conversion or with seller consent
        error_log("Deposit BTC ID $item_number Masih Pending");
    } else {
        //payment is pending, you can optionally add a note to the order page
        error_log("Deposit BTC ID $item_number Masih Pending1");
    }
    error_log("IPN OK");  
 } else {
     error_log("Order tidak ditemukan");
 }
    