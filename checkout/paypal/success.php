<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include configuration file 
include_once 'config.php'; 
require '../../web.php';
// include "../../lib/class/class.phpmailer.php";
include '../../lib/phpmailer/src/Exception.php';
include '../../lib/phpmailer/src/PHPMailer.php';
include '../../lib/phpmailer/src/SMTP.php';

// If transaction data is available in the URL 
if(!empty($_GET['item_number']) && !empty($_GET['tx']) && !empty($_GET['amt']) && !empty($_GET['cc']) && !empty($_GET['st'])){ 

// Get transaction information from URL 
$item_number = $_GET['item_number'];
$name = $_GET['item_name']; 
$txn_id = $_GET['tx']; 
$payment_gross = $_GET['amt']; 
$currency_code = $_GET['cc']; 
$payment_status = $_GET['st']; 
if ($payment_status == 'Completed'){
     $status = 'Success' ;
}
// Get product info from the database 

$productResult = $db->query("SELECT * FROM cart WHERE kode_invoice = '$txn_id'"); 
$productRow = $productResult->fetch_assoc();

// Check if transaction data exists with the same TXN ID. 
$prevPaymentResult = $db->query("SELECT * FROM cart WHERE tx_id_paypal = '$txn_id'"); 

if($prevPaymentResult->num_rows > 0){ 

    $paymentRow = $prevPaymentResult->fetch_assoc(); 

    $payment_id = $paymentRow['id']; 

    $payment_gross = $paymentRow['amount']; 
    $payment_status = $paymentRow['status'];
    

}else{ 

// Insert transaction data into the database 
   if ($payment_status == 'Completed'){
       $num_char = 4;
        $kode_depo = substr($item_number, 0, $num_char);
        $potong = 3;
        $kode_ftr = substr($order_id, 0, $potong);
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
                'message' => 'Deposit Paypal Dengan ID #'.$item_number,
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
                if($user_depo['rows']['terima_tele_pesan'] == '1'){
                                           $text = 'Hallo '.$user_depo['rows']['username'].'
Depositmu Telah Kami Terima Dengan ID: #'.$data_targetss['rows']['kode_depo'].' ^.^
Sebesar : IDR '.$amount.'

Regards
Gubuk Digital';
                                        $teks = urlencode($text);
                                        
                                        $chat_id = decrypt($user_depo['rows']['telegram_id']);
                                        kirim_tele($teks, $chat_id); 
                                        }
             $_SESSION['login'] = $data_targetss['rows']['buyer_id'];
                $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Deposti Anda Telah Kamu Terima ^.^');
                exit(header("Location: ".$config['web']['base_url']."my-product/"));
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
                
                $_SESSION['login'] = $data_targetss['rows']['buyer_id'];
                $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Fitur Anda Telah Aktif ^.^');
                exit(header("Location: ".$config['web']['base_url']."my-product/"));
                
        }else {
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
            'status' => 'success',
            'tx_id_paypal' => $txn_id
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
                // $pisah_keenam = explode("{{admin_fee}}", $pisah_kelima[1]);$invoice_mails = $pisah_pertama[0].$data_targetss['rows']['kode_invoice'].$pisah_kedua[0].$data_targetss['rows']['total_price_admin'].$pisah_ketiga[0].$data_services['rows']['nama_layanan'].$pisah_keempat[0].$data_services['rows']['price'].$pisah_kelima[0].$data_targetss['rows']['quantity'].$pisah_keenam[0].$website['rows']['admin_fee'].$pisah_keenam[1];
               
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
                $nohp = decrypt($user_penjual['rows']['no_hp']);
                $message_sm = $config['web']['base_url']."show-sales/".$order_detail['rows']['id'];
                $username = $user_penjual['rows']['username'];
                $message_sms = 'Halo '.$username.' ada pesanan baru nih, silahkan di cek pada '.$message_sm;
                
                $subject = "Pesanan Baru";
                kirim_email($ke, $nama, $orderan_link, $subject);
//                 if($user_penjual['rows']['terima_wa_orderan'] == '1'){
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
				
                $_SESSION['login'] = $data_targetss['rows']['buyer_id'];
                $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Pembelian Anda Sudah Berhasil Di Proses ke Penjual ^.^');
                exit(header("Location: ".$config['web']['base_url']."my-orders/"));
            }else {
                echo "Something wrong!";
            }
        }
       
       
       
       
       
       
   }
    
    
            

    
    } 
} 
?>