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
 $data_targetss = $model->db_query($db, "*", "deposit", "tx_id = '$sid' ");
 $now = date("Y-m-d H:i:s");
 if($data_targetss['count'] == 1){
        if($status == 'berhasil'){
            $input_post_orders_active = array(
            'status' => 'success'
            );
        $update_orders = $model->db_update($db, "deposit", $input_post_orders_active, "tx_id = '".$sid."' ");
        
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
        error_log("Deposit IPAYMU Sukses tx id ".$sid);
        } elseif($status == 'pending') { // transaksi gagal
            $input_post_update_active = array(
            'status' => 'pending'
            );
            $update_deposit = $model->db_update($db, "deposit", $input_post_update_active, "tx_id = '".$sid."' ");
            error_log("Deposit IPAYMU Masih Pending tx id ".$sid);
        } elseif($status == 'gagal'){
            $input_post_update_active = array(
            'status' => 'error'
            );
            $update_deposit = $model->db_update($db, "deposit", $input_post_update_active, "tx_id = '".$sid."' ");
            error_log("Deposit IPAYMU Gagal tx id ".$sid);
        } else {
            $input_post_update_active = array(
            'status' => 'error'
            );
            $update_deposit = $model->db_update($db, "deposit", $input_post_update_active, "tx_id = '".$sid."' ");
            error_log("Deposit IPAYMU Gagal tx id ".$sid);
        }
         
 } else {
     error_log ("Session ID Tidak ditemukan");
 }     