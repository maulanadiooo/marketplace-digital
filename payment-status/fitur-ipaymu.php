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
 $data_targetss = $model->db_query($db, "*", "fitur_order", "tx_id = '$sid' ");
 $now = date("Y-m-d H:i:s");
 if($data_targetss['count'] == 1){
        if($status == 'berhasil'){
            $input_post_orders_active = array(
            'status' => 'PAID',
        );
        $update_orders = $model->db_update($db, "fitur_order", $input_post_orders_active, "tx_id = '$sid' ");
        
        $user = $model->db_query($db, "*", "user", "id = '".$data_targetss['rows']['user_id']."'");
        $amount = $data_targetss['rows']['amount'];
        $data_services = $data_targetss['rows']['service_id'];
        
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
        } elseif($status == 'pending') { // transaksi gagal
            $input_post_update_active = array(
            'status' => 'PENDING'
            );
            $update_deposit = $model->db_update($db, "fitur_order", $input_post_update_active, "tx_id = '".$sid."' ");
            
        } elseif($status == 'gagal'){
            $input_post_update_active = array(
            'status' => 'NOT PAID'
            );
            $update_deposit = $model->db_update($db, "fitur_order", $input_post_update_active, "tx_id = '".$sid."' ");
        } else {
            $input_post_update_active = array(
            'status' => 'NOT PAID'
            );
            $update_deposit = $model->db_update($db, "fitur_order", $input_post_update_active, "tx_id = '".$sid."' ");
        }
         
 } else {
     error_log ("Session ID Tidak ditemukan");
 }     