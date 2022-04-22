<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../web.php';
// include "../lib/class/class.phpmailer.php";
include '../lib/phpmailer/src/Exception.php';
include '../lib/phpmailer/src/PHPMailer.php';
include '../lib/phpmailer/src/SMTP.php';
$bca = $model->db_query($db, "*", "payment_setting", "id = '3'");
$q = $db->query("SELECT * FROM cart WHERE pembayaran_id_bank = '1' AND status = 'pending' ");

$s = $db->query("SELECT * FROM deposit WHERE status = 'pending' AND id_bank = '1'");
        
$mutasi_depo = mysqli_num_rows($s);
$ftrs = $db->query("SELECT * FROM fitur_product WHERE amount = '$total' AND status = 'PENDING' AND pembayaran_id = '1'");
$fitur_orderss = mysqli_num_rows($ftrs);
if($q->num_rows > 0 || $mutasi_depo > 0 || $fitur_orderss > 0) {
        $url = "https://member.buffmedia.net/mutasi/getmutasi.php";
        $postdata = array( 
            'user' => decrypt($bca['rows']['value_1']),
    	    'password' => decrypt($bca['rows']['value_2'])
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $chresult = curl_exec($ch);
        $result = json_decode($chresult, true);
        // print_r($result);
        
        foreach($result['data'] as $data) {
            $total = $data['total'];
            
            $q = $db->query("SELECT * FROM cart WHERE price_kode_unik = '$total' AND status = 'pending' AND pembayaran_id_bank = '1'");
            $s = $db->query("SELECT * FROM deposit WHERE amount = '$total' AND status = 'pending' AND id_bank = '1'");
            $ftr = $db->query("SELECT * FROM fitur_product WHERE amount = '$total' AND status = 'PENDING' AND pembayaran_id = '1'");
            $mutasi = mysqli_num_rows($q);
            $depo_mutasi =  mysqli_num_rows($s);
            $fitur_order = mysqli_num_rows($ftr);
            
            
             if($mutasi > 0){
                    $data_user = mysqli_fetch_assoc($q);
                    $kode_invoice = $data_user['kode_invoice'];
                    $now_tgl = date('Y-m-d H:i:s');
                    $total_price = $data_user['price_kode_unik'];
                               
                      $website = $model->db_query($db, "*", "website", "id = '1'");
                     $data_targetss = $model->db_query($db, "*", "cart", "kode_invoice = '$kode_invoice' ");
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
                    'pembayaran_id_bank' => '1',
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
                    if($update_orders == true && $update_cart == true){
                        $update_history_pembayaran = array(
                        'user_id' => $data_targetss['rows']['buyer_id'],
                        'amount' => $data_targetss['rows']['price_kode_unik'],
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
                        // $invoice_mails = $pisah_pertama[0].$data_targetss['rows']['kode_invoice'].$pisah_kedua[0].number_format($data_targetss['rows']['total_price_admin'],0,',','.').$pisah_ketiga[0].$data_services['rows']['nama_layanan'].$pisah_keempat[0].number_format($data_services['rows']['price'],0,',','.').$pisah_kelima[0].$data_targetss['rows']['quantity'].$pisah_keenam[0].$website['rows']['admin_fee'].$pisah_keenam[1];
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
                        $nohp = decrypt($user_penjual['rows']['no_hp']);
                        $message_sm = $config['web']['base_url']."show-sales/".$order_detail['rows']['id'];
                        $username = $user_penjual['rows']['username'];
                        $message_sms = 'Halo '.$username.' ada pesanan baru nih, silahkan di cek pada '.$message_sm;
                        
                        kirim_email($ke, $nama, $orderan_link, $subject); 
                         if($user_penjual['rows']['terima_tele_orderan'] == '1'){
                                           $text = 'Hallo '.$nama.' ada rejeki baru nih...
Kamu mendapatkan penjualan dengan ID #'.$order_detail['rows']['id'].'

Silahkan Login Pada Gubukdigital.net untuk memproses penjualanmu
            
Pesan Ini Dibuat Secara Otomatis

Regards
Gubuk Digital';
                                        $teks = urlencode($text);
                                        
                                        $chat_id = decrypt($user_penjual['rows']['telegram_id']);
                                        kirim_tele($teks, $chat_id); 
                                        } 
                        error_log("Pembayaran BCA  $total_price Telah berhasil diverifikasi invoice $kode_invoice");
                        
                        
                    }else {
                        error_log("Something wrong!");
                    }
            } elseif($depo_mutasi > 0){
                
                $data_users = mysqli_fetch_assoc($s);
                $id = $data_users['id'];
                $kode_depo = $data_users['kode_depo'];
                $user = $model->db_query($db, "*", "user", "id = '".$data_users['user_id']."'");
                $amount = $data_users['amount'];
                $saldo_user = $user['rows']['saldo_tersedia'];
                $input_post_orders_active = array(
                'status' => 'success',
                );
                 $update_deposit = $model->db_update($db, "deposit", $input_post_orders_active, "id = '$id' ");
                
                $input_post_user= array(
                    'saldo_tersedia' => $saldo_user + $amount,
                    );
                $update_user = $model->db_update($db, "user", $input_post_user, "id = '".$data_users['user_id']."' ");
                if($update_deposit == true && $update_user == true){
                    $now = date("Y-m-d H:i:s");
                    $update_history_pembayaran = array(
                    'user_id' => $data_users['user_id'],
                    'amount' => $amount,
                    'message' => 'Deposit Dengan ID #'.$kode_depo,
                    'created_at' => $now
                    );
                    $model->db_insert($db, "history_pembayaran", $update_history_pembayaran);
                
                $email_depo = $model->db_query($db, "*", "email", "id = '6'");
                $user_depo = $model->db_query($db, "*", "user", "id = '".$data_users['user_id']."' ");
                $amount = number_format($data_users['amount'],0,',','.');
                $pertama = str_replace("{{username}}",$user_depo['rows']['username'],$email_depo['rows']['email']);
                $kedua = str_replace("{{jumlah_pembayaran}}",'Rp '.$amount.' ,-',$pertama);
                $invoice_depo = str_replace("{{invoice_id}}",$data_users['kode_depo'],$kedua);
                $subject_depo = 'Deposit Berhasil #'.$data_users['kode_depo'];
                $ke_user_depo = decrypt($user_depo['rows']['email']);
                $nama_user_depo = $user_depo['rows']['nama'];
                kirim_email($ke_user_depo, $nama_user_depo, $invoice_depo, $subject_depo);    
                if($user_depo['rows']['terima_tele_pesan'] == '1'){
                                           $text = 'Hallo '.$user_depo['rows']['username'].'
Depositmu Telah Kami Terima Dengan ID: #'.$kode_depo.' ^.^
Sebesar : IDR '.$amount.'

Regards
Gubuk Digital';
                                        $teks = urlencode($text);
                                        
                                        $chat_id = decrypt($user_depo['rows']['telegram_id']);
                                        kirim_tele($teks, $chat_id); 
                                        }    
                }else {
                    error_log ("Something wrong!");
                }
            } elseif($fitur_order > 0){
                $data_targetss = mysqli_fetch_assoc($ftr);
                $website = $model->db_query($db, "*", "website", "id = '1'");
                $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['service_id']."' ");
                $id_user = $data_targetss['user_id'];
                $order_id = $data_targetss['kode_invoice'];
                $user = $model->db_query($db, "*", "user", "id = '".$data_targetss['user_id']."'");
                $amount = $data_targetss['amount'];
                $now = date("Y-m-d H:i:s");
                $input_post_orders_active = array(
                    'status' => 'PAID',
                );
                $update_orders = $model->db_update($db, "fitur_order", $input_post_orders_active, "kode_invoice = '$order_id' ");
                
                if($data_targetss['order_fitur'] == 'premium'){
                    $expired_premi = date('Y-m-d H:i:s',strtotime('+'.$website['rows']['durasi_fitur_premium'].' Day',strtotime($now)));
                    $update_fitur = array(
                    'premium' => '1',
                    'expired_premium' => $expired_premi,
                    );
                    $update_services = $model->db_update($db, "services", $update_fitur, "id = '".$data_targetss['service_id']."' ");
                   
                }
                if($data_targetss['order_fitur'] == 'featured'){
                    $expired_featured = date('Y-m-d H:i:s',strtotime('+'.$website['rows']['durasi_fitur_featured'].' Day',strtotime($now)));
                    $update_fitur = array(
                    'featured' => '1',
                    'expired_featured' => $expired_featured,
                    );
                    $update_services = $model->db_update($db, "services", $update_fitur, "id = '".$data_targetss['service_id']."' "); 
                    
                }
                $input_post_penghasilan_admin = array(
                'dari_fitur' => $data_targetss['amount'],
                'order_id' => $data_targetss['id'],
                'created_at' => $now
                );
                $insert = $model->db_insert($db, "penghasilan_admin", $input_post_penghasilan_admin);
                $update_history_pembayaran = array(
                'user_id' => $data_targetss['user_id'],
                'amount' => $data_targetss['amount'],
                'message' => 'Pembelian Fitur #'.$data_targetss['order_fitur']." Untuk Layananan - ".$data_services['nama_layanan'],
                'created_at' => $now
                );
                
                
                
            }else {
                error_log("Coba Lagi!");
            }
            
    }
} else {
    echo "Tidak ada deposit atau Order Dengan Metode BCA";
}
?>