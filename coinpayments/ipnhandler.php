<?php    
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../web.php';
// require '../lib/class/class.phpmailer.php';
include '../lib/phpmailer/src/Exception.php';
include '../lib/phpmailer/src/PHPMailer.php';
include '../lib/phpmailer/src/SMTP.php';
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
    $txn_id_database = $model->db_query($db, "*", "cart", "tx_id_paypal = '$txn_id'");
    $total_price_admin = $txn_id_database['rows']['total_price_admin'];
    $btc = $model->db_query($db, "*", "bank_information", "id = '8'");
    $order_total = round($total_price_admin / $btc['rows']['rate_dollar'], 2);

   


$data_targetss = $model->db_query($db, "*", "cart", "tx_id_paypal = '$txn_id' AND status = 'pending'");
 if($data_targetss['count'] > 0){
 
    if ($status >= 100 || $status == 2) {
        $website = $model->db_query($db, "*", "website", "id = '1'");
         $data_targetss = $model->db_query($db, "*", "cart", "kode_invoice = '$item_number' ");
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
                    $pisah_pertama = explode("{{id_invoice}}", $formats);
                    $pisah_kedua = explode("{{amount}}", $pisah_pertama[1]);
                    $pisah_ketiga = explode("{{layanan}}", $pisah_kedua[1]);
                    $pisah_keempat = explode("{{harga_layanan}}", $pisah_ketiga[1]);
                    $pisah_kelima = explode("{{quantity}}", $pisah_keempat[1]);
                    $pisah_keenam = explode("{{admin_fee}}", $pisah_kelima[1]);
                    $user_pembeli = $model->db_query($db, "*", "user", "id = '".$data_targetss['rows']['buyer_id']."' ");	
                    $ke_pembeli = decrypt($user_pembeli['rows']['email']);
                    $nama_pembeli  = $user_pembeli['rows']['nama'];
                    $subject_pembeli = "Pembayaran Berhasil Untuk Invoice #".$data_targetss['rows']['kode_invoice'];
                    $invoice_mail = $pisah_pertama[0].$data_targetss['rows']['kode_invoice'].$pisah_kedua[0].$data_targetss['rows']['total_price_admin'].$pisah_ketiga[0].$data_services['rows']['nama_layanan'].$pisah_keempat[0].$data_services['rows']['price'].$pisah_kelima[0].$data_targetss['rows']['quantity'].$pisah_keenam[0].$website['rows']['admin_fee'].$pisah_keenam[1];
                    
                    kirim_email($ke_pembeli, $nama_pembeli, $invoice_mail, $subject_pembeli);
                    
                    
                    $email_orderan = $model->db_query($db, "*", "email", "id = '3'");
                    $user_penjual = $model->db_query($db, "*", "user", "id = '".$data_services['rows']['author']."' ");			    
                    $ke = decrypt($user_penjual['rows']['email']);
                    $nama = $user_penjual['rows']['nama'];
                    $format = $email_orderan['rows']['email'];
                    $pisah = explode("{{link_penjualan}}", $format);
                    $orderan_link = $pisah[0].$config['web']['base_url']."show-sales/".$order_detail['rows']['id'].$pisah[1];
                    $nohp = decrypt($user_penjual['rows']['no_hp']);
                    $message_sm = $config['web']['base_url']."show-sales/".$order_detail['rows']['id'];
                    $username = $user_penjual['rows']['username'];
                    $message_sms = 'Halo '.$username.' ada pesanan baru nih, silahkan di cek pada '.$message_sm;
                    
                    $subject = "Pesanan Baru #".$order_detail['rows']['id'];
                    kirim_email($ke, $nama, $orderan_link, $subject);
//                     if($user_penjual['rows']['terima_wa_orderan'] == '1'){
// $pesan_wa = 'Hallo '.$nama.' ada rejeki baru nih...
// Kamu mendapatkan penjualan dengan ID #'.$order_detail['rows']['id'].'

// Silahkan Login Pada Gubukdigital.net untuk memproses penjualanmu
            
// Pesan Ini Dibuat Secara Otomatis,
// Jika ingin mematikan pemberitahuan via Whatsapp, Silahkan Ketik:
// *matikan notifikasi orderan*

// Atau pada pengaturan akunnmu

// Regards
// GubukDigital.Net';
// $no_hp = decrypt($user_penjual['rows']['no_hp']); 
// kirim_wa_pesan($no_hp, $pesan_wa);
//                                     }
				    if($user_penjual['rows']['terima_tele_orderan'] == '1'){
                                           $text = 'Hallo '.$user_penjual['rows']['username'].' ada rejeki baru nih...
Kamu mendapatkan penjualan dengan ID #'.$order_detail['rows']['id'].'

Silahkan Login Pada Gubukdigital.net untuk memproses penjualanmu
            
Pesan Ini Dibuat Secara Otomatis

Regards
Gubuk Digital';
                                        $teks = urlencode($text);
                                        
                                        $chat_id = decrypt($user_penjual['rows']['telegram_id']);
                                        kirim_tele($teks, $chat_id); 
                                        }
				    
                    error_log("Pembayaran Cointpayment Telah berhasil diverifikasi invoice $item_number");
                } else {
                    error_log("Terjadi Kesalahan/Error Pada Update");
                }
        
    } elseif ($status < 0) {
        //payment error, this is usually final but payments will sometimes be reopened if there was no exchange rate conversion or with seller consent
        $data_targetss = $model->db_query($db, "*", "cart", "tx_id_paypal = '$txn_id' ");
        $now = date("Y-m-d H:i:s");
        $input_post_orders_active = array(
        'status' => 'unpaid',
        'created_at' => $now,
        );
        $update_orders = $model->db_update($db, "orders", $input_post_orders_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' ");
        $input_post_update_active = array(
            'pembayaran_id_bank' => '8',
            'status' => 'cancel'
            );
        $update_cart = $model->db_update($db, "cart", $input_post_update_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' ");
        error_log("Pembayaran $item_number gagal didapatkan");
    } elseif ($status == 0) {
        error_log("payment masih pending");
    } else {
        //payment is pending, you can optionally add a note to the order page
        error_log("payment masih pending");
    }
    error_log("IPN OK");
 } else {
     error_log("invoice BTC tidak ditemukan");
 }