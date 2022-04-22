<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "config.php";
require_once "EnvayaSMS.php";
require_once "../../web.php";
include '../../lib/phpmailer/src/Exception.php';
include '../../lib/phpmailer/src/PHPMailer.php';
include '../../lib/phpmailer/src/SMTP.php';

$request = EnvayaSMS::get_request();
header("Content-Type: {$request->get_response_type()}");
if (!$request->is_validated($PASSWORD))
{
    header("HTTP/1.1 403 Forbidden");
    error_log("Invalid password");     
    echo $request->render_error_response("Invalid password");
    return;
}
$action = $request->get_action();
switch ($action->type)
{
    case EnvayaSMS::ACTION_INCOMING:    
        
        
		
        $type = strtoupper($action->message_type);
        $isi_pesan = $action->message;
        $file = fopen("test.txt","w");
echo fwrite($file,$isi_pesan);
fclose($file);
     if($action->from == 'BNI' AND preg_match("/Rek 913727155 ada dana masuk sebesar/i", $isi_pesan)) {
         $pesan_isi = $action->message;
         $check_cart = mysqli_query($db, "SELECT * FROM cart WHERE status = 'pending' AND pembayaran_id_bank = '2'");
         $check_depo = mysqli_query($db, "SELECT * FROM deposit WHERE status = 'pending' AND id_bank = '2'");
         if (mysqli_num_rows($check_cart) > 0) {
             
             while($data_cart = mysqli_fetch_assoc($check_cart)) {
                        $id_cart = $data_cart['id'];
                        $kode_invoice = $data_cart['kode_invoice'];
                        $total_price = number_format($data_cart['price_kode_unik'],0,',','.');
                        
                        
                        $cekpesan = preg_match("/Rek 913727155 ada dana masuk sebesar IDR$total_price,00./i", $isi_pesan);
                        if($cekpesan == true) {
                           
                           $now_tgl = date('Y-m-d H:i:s');
                           
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
                                'pembayaran_id_bank' => '2',
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
                                    
                                     $email_orderan = $model->db_query($db, "*", "email", "id = '3'");
                                    $user_penjual = $model->db_query($db, "*", "user", "id = '".$data_services['rows']['author']."' ");			    
                                    $ke = decrypt($user_penjual['rows']['email']);
                                    $nama = $user_penjual['rows']['nama'];
                                    $format = $email_orderan['rows']['email'];
                                    $pisah = explode("{{link_penjualan}}", $format);
                                    $orderan_link = $pisah[0].$config['web']['base_url']."show-sales/".$order_detail['rows']['id'].$pisah[1];
                                    
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
                                    $invoice_mail = $pisah_pertama[0].$data_targetss['rows']['kode_invoice'].$pisah_kedua[0].$data_targetss['rows']['price_kode_unik'].$pisah_ketiga[0].$data_services['rows']['nama_layanan'].$pisah_keempat[0].$data_services['rows']['price'].$pisah_kelima[0].$data_targetss['rows']['quantity'].$pisah_keenam[0].$website['rows']['admin_fee'].$pisah_keenam[1];
                                    
                                    
                                    $mail = new PHPMailer(true);
    
                                    //Server settings
                                    $mail->SMTPDebug = 0;                      // Enable verbose debug output
                                    $mail->isSMTP();                                            // Send using SMTP
                                    $mail->Host       = 'mail.privateemail.com';                    // Set the SMTP server to send through
                                    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                                    $mail->Username   = 'no-reply@gubukdigital.net';                     // SMTP username
                                    $mail->Password   = 'Dio4pesek!';                               // SMTP password
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                                    $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                                
                                    //Recipients
                                    $mail->setFrom('no-reply@gubukdigital.net', 'Gubukdigital.net');
                                    $mail->addAddress($ke_pembeli, $nama_pembeli);     // Add a recipient
                                    $mail->addAddress($ke_pembeli);               // Name is optional
                                    $mail->addReplyTo('support@gubukdigital.net', 'Gubukdigital.net');
                                    // $mail->addCC('diomaulana25@gmail.com');
                                
                                    
                                
                                    // Content
                                    $mail->isHTML(true);                                  // Set email format to HTML
                                    $mail->Subject = $subject_pembeli;
                                    $mail->Body    = $invoice_mail;
                                    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                                    $mail->send();
                                    
                                    $mail = new PHPMailer(true);
    
                                    //Server settings
                                    $mail->SMTPDebug = 0;                      // Enable verbose debug output
                                    $mail->isSMTP();                                            // Send using SMTP
                                    $mail->Host       = 'mail.privateemail.com';                    // Set the SMTP server to send through
                                    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                                    $mail->Username   = 'no-reply@gubukdigital.net';                     // SMTP username
                                    $mail->Password   = 'Dio4pesek!';                               // SMTP password
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                                    $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                                
                                    //Recipients
                                    $mail->setFrom('no-reply@gubukdigital.net', 'Gubukdigital.net');
                                    $mail->addAddress($ke, $nama);     // Add a recipient
                                    $mail->addAddress($ke);               // Name is optional
                                    $mail->addReplyTo('support@gubukdigital.net', 'Gubukdigital.net');
                                    // $mail->addCC('diomaulana25@gmail.com');
                                
                                    
                                
                                    // Content
                                    $mail->isHTML(true);                                  // Set email format to HTML
                                    $mail->Subject = $subject;
                                    $mail->Body    = $orderan_link;
                                    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                                    $mail->send();
				                    
                                    error_log("Pembayaran BNI  $total_price Telah berhasil diverifikasi invoice $kode_invoice");
                                    
                                    
                                }else {
                                    echo "Something wrong!";
                                }
                            
                        } else {
                            error_log("data Transfer BNI Tidak Ada");
                        }
                }
                
         } elseif(mysqli_num_rows($check_depo) > 0){
                while($data_depo = mysqli_fetch_assoc($check_depo)) {
                    $id_cart = $data_depo['id'];
                    $total_price = number_format($data_depo['amount'],0,',','.');
                    $cekpesan = preg_match("/Rek 913727155 ada dana masuk sebesar IDR$total_price,00./i", $isi_pesan);
                        if($cekpesan == true) {
                            $id = $data_depo['id'];
                            $user = $model->db_query($db, "*", "user", "id = '".$data_depo['user_id']."'");
                            $amount = $data_depo['amount'];
                            $saldo_user = $user['rows']['saldo_tersedia'];
                            $input_post_orders_active = array(
                            'status' => 'success',
                            );
                             $update_deposit = $model->db_update($db, "deposit", $input_post_orders_active, "id = '$id' ");  
                             
                          $input_post_user= array(
                                'saldo_tersedia' => $saldo_user + $amount,
                                );
                            $update_user = $model->db_update($db, "user", $input_post_user, "id = '".$data_depo['user_id']."' ");
                        if($update_deposit == true && $update_user == true){
    
                            error_log("Deposit BNI ID $id telah diterima");
                            
                            
                        }else {
                            error_log ("Something wrong!");
                        }
                    }
                    
                }
         }else {
             error_log("History BNI Not Found .");
         }
     } elseif($action->from == 'BRI-NOTIF' AND preg_match("/TO160301008969502MP/i", $isi_pesan)) {
         $pesan_isi = $action->message;
         $check_cart = mysqli_query($db, "SELECT * FROM cart WHERE status = 'pending' AND pembayaran_id_bank = '4'");
         $check_depo = mysqli_query($db, "SELECT * FROM deposit WHERE status = 'pending' AND id_bank = '4'");
         if (mysqli_num_rows($check_cart) > 0) {
             
             while($data_cart = mysqli_fetch_assoc($check_cart)) {
                        $id_cart = $data_cart['id'];
                        $kode_invoice = $data_cart['kode_invoice'];
                        $total_price = number_format($data_cart['price_kode_unik'],0,',','.');
                        
                        
                        $cekpesan = preg_match("/Rp. $total_price /i", $isi_pesan);
                        if($cekpesan == true) {
                           
                           $now_tgl = date('Y-m-d H:i:s');
                           
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
                                'pembayaran_id_bank' => '4',
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
                                    
                                     $email_orderan = $model->db_query($db, "*", "email", "id = '3'");
                                    $user_penjual = $model->db_query($db, "*", "user", "id = '".$data_services['rows']['author']."' ");			    
                                    $ke = decrypt($user_penjual['rows']['email']);
                                    $nama = $user_penjual['rows']['nama'];
                                    $format = $email_orderan['rows']['email'];
                                    $pisah = explode("{{link_penjualan}}", $format);
                                    $orderan_link = $pisah[0].$config['web']['base_url']."show-sales/".$order_detail['rows']['id'].$pisah[1];
                                    
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
                                    $invoice_mail = $pisah_pertama[0].$data_targetss['rows']['kode_invoice'].$pisah_kedua[0].$data_targetss['rows']['price_kode_unik'].$pisah_ketiga[0].$data_services['rows']['nama_layanan'].$pisah_keempat[0].$data_services['rows']['price'].$pisah_kelima[0].$data_targetss['rows']['quantity'].$pisah_keenam[0].$website['rows']['admin_fee'].$pisah_keenam[1];
                                    
                                    
                                   $mail = new PHPMailer(true);
    
                                    //Server settings
                                    $mail->SMTPDebug = 0;                      // Enable verbose debug output
                                    $mail->isSMTP();                                            // Send using SMTP
                                    $mail->Host       = 'mail.privateemail.com';                    // Set the SMTP server to send through
                                    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                                    $mail->Username   = 'no-reply@gubukdigital.net';                     // SMTP username
                                    $mail->Password   = 'Dio4pesek!';                               // SMTP password
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                                    $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                                
                                    //Recipients
                                    $mail->setFrom('no-reply@gubukdigital.net', 'Gubukdigital.net');
                                    $mail->addAddress($ke_pembeli, $nama_pembeli);     // Add a recipient
                                    $mail->addAddress($ke_pembeli);               // Name is optional
                                    $mail->addReplyTo('support@gubukdigital.net', 'Gubukdigital.net');
                                    // $mail->addCC('diomaulana25@gmail.com');
                                
                                    
                                
                                    // Content
                                    $mail->isHTML(true);                                  // Set email format to HTML
                                    $mail->Subject = $subject_pembeli;
                                    $mail->Body    = $invoice_mail;
                                    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                                    $mail->send();
                                    
                                    $mail = new PHPMailer(true);
    
                                    //Server settings
                                    $mail->SMTPDebug = 0;                      // Enable verbose debug output
                                    $mail->isSMTP();                                            // Send using SMTP
                                    $mail->Host       = 'mail.privateemail.com';                    // Set the SMTP server to send through
                                    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                                    $mail->Username   = 'no-reply@gubukdigital.net';                     // SMTP username
                                    $mail->Password   = 'Dio4pesek!';                               // SMTP password
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                                    $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                                
                                    //Recipients
                                    $mail->setFrom('no-reply@gubukdigital.net', 'Gubukdigital.net');
                                    $mail->addAddress($ke, $nama);     // Add a recipient
                                    $mail->addAddress($ke);               // Name is optional
                                    $mail->addReplyTo('support@gubukdigital.net', 'Gubukdigital.net');
                                    // $mail->addCC('diomaulana25@gmail.com');
                                
                                    
                                
                                    // Content
                                    $mail->isHTML(true);                                  // Set email format to HTML
                                    $mail->Subject = $subject;
                                    $mail->Body    = $orderan_link;
                                    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                                    $mail->send();
				                    kirim_sms($nohp, $message_sms);
				                    
                                    error_log("Pembayaran BRI  $total_price Telah berhasil diverifikasi invoice $kode_invoice");
                                    
                                    
                                }else {
                                    echo "Something wrong!";
                                }
                            
                        } else {
                            error_log("data Transfer BRI Tidak Ada");
                        }
                }
                
         } elseif(mysqli_num_rows($check_depo) > 0){
                while($data_depo = mysqli_fetch_assoc($check_depo)) {
                    $id_cart = $data_depo['id'];
                    $total_price = number_format($data_depo['amount'],0,',','.');
                    $cekpesan = preg_match("/Rek 913727155 ada dana masuk sebesar IDR$total_price,00./i", $isi_pesan);
                        if($cekpesan == true) {
                            $id = $data_depo['id'];
                            $user = $model->db_query($db, "*", "user", "id = '".$data_depo['user_id']."'");
                            $amount = $data_depo['amount'];
                            $saldo_user = $user['rows']['saldo_tersedia'];
                            $input_post_orders_active = array(
                            'status' => 'success',
                            );
                             $update_deposit = $model->db_update($db, "deposit", $input_post_orders_active, "id = '$id' ");  
                             
                          $input_post_user= array(
                                'saldo_tersedia' => $saldo_user + $amount,
                                );
                            $update_user = $model->db_update($db, "user", $input_post_user, "id = '".$data_depo['user_id']."' ");
                        if($update_deposit == true && $update_user == true){
    
                            error_log("Deposit BRI ID $id telah diterima");
                            
                            
                        }else {
                            error_log ("Something wrong!");
                        }
                    }
                    
                }
         }else {
             error_log("History BRI Not Found .");
         }
     }elseif($action->from == '858' AND preg_match("/Anda mendapatkan penambahan pulsa/i", $isi_pesan)) {
                $pesan_isi_tsel = encrypt_pulsa($action->message);   
                exit(header("Location: https://buffmedia.net/gateway/convert_tsel.php?isi_pesan=".$pesan_isi_tsel));
     }elseif($action->from == '168' AND preg_match("/Anda menerima Pulsa dari/i", $isi_pesan)) {
                $pesan_isi_xl = encrypt_pulsa($action->message);    
                exit(header("Location: https://buffmedia.net/gateway/convert_xl.php?isi_pesan_xl=".$pesan_isi_xl));
     } else {
        error_log("Received $type from {$action->from}");
        error_log(" message: {$action->message}");
     }                     
        
        return;
}