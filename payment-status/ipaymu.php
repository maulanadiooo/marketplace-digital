<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../web.php';
require '../lib/result.php';
// include "../lib/class/class.phpmailer.php";
include '../lib/phpmailer/src/Exception.php';
include '../lib/phpmailer/src/PHPMailer.php';
include '../lib/phpmailer/src/SMTP.php';

if(!isset($_POST['trx_id'])){
    exit(header("Location: ".$config['web']['base_url']));
}
if (!isset($_POST['sid'])){
    exit(header("Location: ".$config['web']['base_url']));
}

if(!isset($_POST['status'])){
    exit(header("Location: ".$config['web']['base_url']));
}
$trx_id = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['trx_id']))));
$sid = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['sid']))));
$status = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['status']))));
    
$website = $model->db_query($db, "*", "website", "id = '1'");
 $data_targetss = $model->db_query($db, "*", "cart", "tx_id_paypal = '$sid' ");
 $now = date("Y-m-d H:i:s");
 $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
 $jangka_waktu = $data_services['rows']['jangka_waktu'];
 $send_before = date('Y-m-d H:i:s',strtotime('+'.$jangka_waktu.' Day',strtotime($now)));
 if($data_targetss['count'] == 1){
        if($status == 'berhasil'){
            $input_post_orders_active = array(
            'status' => 'active',
            'created_at' => $now,
            'send_before' => $send_before
            );
        $update_orders = $model->db_update($db, "orders", $input_post_orders_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' ");
        
        $input_post_update_active = array(
        'status' => 'success',
        'tx_id_ipaymu' => $trx_id
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
            // end email alma
            
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
            
            $subject = "Pesanan Baru #".$order_detail['rows']['id'];
            kirim_email($ke, $nama, $orderan_link, $subject);
//             if($user_penjual['rows']['terima_wa_orderan'] == '1'){
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
			
            error_log ("Pembayaran IPAYMU Telah diterima, Kode Invoice: ".$data_targetss['rows']['kode_invoice']);
            
        }else {
            error_log ("Something wrong! invoice ".$data_targetss['rows']['kode_invoice']);
        }
        } elseif($status == 'pending') { // transaksi gagal
            $input_post_update_active = array(
            'status' => 'pending',
            'tx_id_ipaymu' => $trx_id
            );
            $update_cart = $model->db_update($db, "cart", $input_post_update_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' ");
            error_log("Pembayaran IPAYMU Masih Pending Invoice ".$data_targetss['rows']['kode_invoice']);
        } elseif($status == 'gagal'){
            $input_post_update_active = array(
            'status' => 'cancel',
            'tx_id_ipaymu' => $trx_id
            );
            $update_cart = $model->db_update($db, "cart", $input_post_update_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' ");
            error_log("Pembayaran IPAYMU Gagal Invoice ".$data_targetss['rows']['kode_invoice']);
        } else {
            $input_post_update_active = array(
            'status' => 'cancel',
            'tx_id_ipaymu' => $trx_id
            );
            $update_cart = $model->db_update($db, "cart", $input_post_update_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' ");
            error_log("Pembayaran IPAYMU Gagal Invoice ".$data_targetss['rows']['kode_invoice']);
        }
         
 } else {
     error_log ("Session ID Tidak ditemukan");
 }     