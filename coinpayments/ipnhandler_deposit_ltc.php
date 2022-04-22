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
    $txn_id_database = $model->db_query($db, "*", "deposit", "tx_id = '$txn_id'");
    $btc = $model->db_query($db, "*", "bank_information", "id = '15'");

    //depending on the API of your system, you may want to check and see if the transaction ID $txn_id has already been handled before at this point

    // Check the original currency to make sure the buyer didn't change it.
    if ($currency1 != $order_currency) {
        errorAndDie('Original currency mismatch!');
    }

    // Check amount against order total
 $data_targetss = $model->db_query($db, "*", "deposit", "tx_id = '$txn_id' ");
 if($data_targetss['count'] > 0){
    if ($status >= 100 || $status == 2) {
        $website = $model->db_query($db, "*", "website", "id = '1'");
        $user = $model->db_query($db, "*", "user", "id = '".$data_targetss['rows']['user_id']."'");
        $kode_depo = $data_targetss['rows']['kode_depo'];
         $model->db_update($db, "deposit", array('status' => 'success'), "tx_id = '$txn_id'");
         $model->db_update($db, "user", array('saldo_tersedia' => $user['rows']['saldo_tersedia'] + $data_targetss['rows']['amount']), "id = '".$data_targetss['rows']['user_id']."'");
        //             $ke = decrypt($user['rows']['email']);
        //             $nama = $user['rows']['nama'];
        //             $jumlah = number_format($data_targetss['rows']['amount'],0,',','.');
        //             $isi_pesan = "Halo, $nama <br>
                    
        //             Deposit Anda Dengan ID #$kode_depo Sebesar $jumlah Sudah Berhasil Tambahkan <br><br>
                    
        //             Regards <br>
        //             Gubuk Digital - Marketplace Produk Virtual Terpercaya";
                    
        //             $subject = "Deposit Berhasil";
				    // kirim_email($ke, $nama, $isi_pesan, $subject); 
        //             error_log("Deposit Cointpayment Telah berhasil diverifikasi id deposit $item_number");
        $update_history_pembayaran = array(
        'user_id' => $data_targetss['rows']['user_id'],
        'amount' => $data_targetss['rows']['amount'],
        'message' => 'Deposit Dengan ID #'.$data_targetss['rows']['kode_depo'],
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
        
    } else if ($status < 0) {
        //payment error, this is usually final but payments will sometimes be reopened if there was no exchange rate conversion or with seller consent
        $data_targetss = $model->db_query($db, "*", "deposit", "tx_id = '$txn_id' ");
        $now = date("Y-m-d H:i:s");
        $input_post_orders_active = array(
        'status' => 'error',
        );
        $update_orders = $model->db_update($db, "deposit", $input_post_orders_active, "tx_id = '$txn_id' ");
        
        error_log("Deposit ID $item_number gagal didapatkan");
    } else if ($status == 0) {
        //payment error, this is usually final but payments will sometimes be reopened if there was no exchange rate conversion or with seller consent
        
        
        error_log("Deposit ID $item_number MASIH PENDING");
    } else {
        //payment is pending, you can optionally add a note to the order page
        error_log("Deposit ID $item_number MASIH PENDING1");
    }
    error_log("IPN OK");
 } else {
     error_log("Deposit tidak ditemukan");
 }
    