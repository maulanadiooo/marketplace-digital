<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../web.php';
require '../lib/result.php';
include '../lib/phpmailer/src/Exception.php';
include '../lib/phpmailer/src/PHPMailer.php';
include '../lib/phpmailer/src/SMTP.php';
if (!isset($_GET['order_id'])) {
	error_log("No direct script access allowed!!!");
	exit("No direct script access allowed!!!");
}
else {
    $order_id = mysqli_real_escape_string($db, $_GET['order_id']);
    $data_targetss = $model->db_query($db, "*", "deposit", "kode_depo = '".$order_id."'");
    if ($data_targetss['count'] <> 1) {
		error_log("Deposit Not Found!, Kode Depo : ".$order_id);
	} else {
	   
	   
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
        
    }
    
    
}
