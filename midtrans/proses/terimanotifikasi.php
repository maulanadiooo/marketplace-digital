<?php

namespace Midtrans;

require_once dirname(__FILE__) . '/../../web.php';
require_once dirname(__FILE__) . '/../Midtrans.php';
$midtrans = $model->db_query($db, "*", "payment_setting", "id = '1'");
Config::$isProduction = true;
Config::$serverKey = decrypt($midtrans['rows']['value_1']); //ubah serverkey
$notif = new Notification();

$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;
$potong = 3;
$kode_ftr = substr($order_id, 0, $potong);
$num_char = 4;
$kode_depo = substr($order_id, 0, $num_char);
if($kode_depo == "DEPO"){
    $website = $model->db_query($db, "*", "website", "id = '1'");
    $data_targetss = $model->db_query($db, "*", "deposit", "kode_depo = '$order_id' AND status = 'pending' ");
    $now = date("Y-m-d H:i:s");
    $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
    $jangka_waktu = $data_services['rows']['jangka_waktu'];
    $send_before = date('Y-m-d H:i:s',strtotime('+'.$jangka_waktu.' Day',strtotime($now)));
    
    
    if ($transaction == 'settlement' && $data_targetss['count'] > 0) {
        $input_post_orders_active = array(
            'status' => 'success'
            );
        $update_orders = $model->db_update($db, "deposit", $input_post_orders_active, "kode_depo = '$order_id' ");
        
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
            'message' => 'Deposit Dengan ID #'.$order_id,
            'created_at' => $now
            );
            $model->db_insert($db, "history_pembayaran", $update_history_pembayaran);
        header("Location: ".$config['web']['base_url']."email_midtrans/deposit-success.php?order_id=".$order_id, true, 307);
    } else if ($transaction == 'pending') {
        $input_post_orders_active = array(
            'status' => 'pending'
            );
        $update_orders = $model->db_update($db, "deposit", $input_post_orders_active, "kode_depo = '".$order_id."' ");
       
    } else if ($transaction == 'deny') {
        $input_post_orders_active = array(
            'status' => 'error'
            );
        $update_orders = $model->db_update($db, "deposit", $input_post_orders_active, "kode_depo = '".$order_id."' ");
       
    } else if ($transaction == 'expire') {
        $input_post_orders_active = array(
            'status' => 'error'
            );
        $update_orders = $model->db_update($db, "deposit", $input_post_orders_active, "kode_depo = '".$order_id."' "); 
        
    } else if ($transaction == 'cancel') {
        $input_post_orders_active = array(
            'status' => 'error'
            );
        $update_orders = $model->db_update($db, "deposit", $input_post_orders_active, "kode_depo = '".$order_id."' ");
        
        
    }
} elseif($kode_ftr == "FTR"){
    $website = $model->db_query($db, "*", "website", "id = '1'");
    $data_targetss = $model->db_query($db, "*", "fitur_order", "kode_invoice = '$order_id' AND status = 'PENDING' ");
    $now = date("Y-m-d H:i:s");
    $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
    
    
    if ($transaction == 'settlement' && $data_targetss['count'] > 0) {
        $input_post_orders_active = array(
            'status' => 'PAID',
        );
        $update_orders = $model->db_update($db, "fitur_order", $input_post_orders_active, "kode_invoice = '$order_id' ");
        
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
        $model->db_insert($db, "history_pembayaran", $update_history_pembayaran);
    } else if ($transaction == 'pending') {
        $input_post_orders_active = array(
            'status' => 'PENDING'
            );
        $update_orders = $model->db_update($db, "fitur_order", $input_post_orders_active, "kode_invoice = '".$order_id."' ");
       
    } else if ($transaction == 'deny') {
        $input_post_orders_active = array(
            'status' => 'NOT PAID'
            );
        
        $update_orders = $model->db_update($db, "fitur_order", $input_post_orders_active, "kode_invoice = '".$order_id."' ");
       
    } else if ($transaction == 'expire') {
        $input_post_orders_active = array(
            'status' => 'NOT PAID'
            );
        $update_orders = $model->db_update($db, "fitur_order", $input_post_orders_active, "kode_invoice = '".$order_id."' ");
        
    } else if ($transaction == 'cancel') {
        $input_post_orders_active = array(
            'status' => 'NOT PAID'
            );
        $update_orders = $model->db_update($db, "fitur_order", $input_post_orders_active, "kode_invoice = '".$order_id."' ");
        
        
    }
} else {
    $website = $model->db_query($db, "*", "website", "id = '1'");
    $data_targetss = $model->db_query($db, "*", "cart", "kode_invoice = '$order_id' AND status = 'pending' ");
    $now = date("Y-m-d H:i:s");
    $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
    $jangka_waktu = $data_services['rows']['jangka_waktu'];
    $send_before = date('Y-m-d H:i:s',strtotime('+'.$jangka_waktu.' Day',strtotime($now)));
    
    
    if ($transaction == 'settlement' && $data_targetss['count'] > 0) {
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
        if($update_orders == true && $update_cart == true){
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
            
            header("Location: ".$config['web']['base_url']."email_midtrans/success.php?order_id=".$data_targetss['rows']['kode_unik']."", true, 307);
            
            
            
        }
       
    } else if ($transaction == 'pending') {
        $input_post_orders_active = array(
            'status' => 'unpaid',
            'created_at' => $now,
            );
        $update_orders = $model->db_update($db, "orders", $input_post_orders_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' ");
        $input_post_update_active = array(
            'status' => 'pending'
            );
        $update_cart = $model->db_update($db, "cart", $input_post_update_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' "); 
       
    } else if ($transaction == 'deny') {
        $input_post_orders_active = array(
            'status' => 'unpaid',
            'created_at' => $now,
            );
        $update_orders = $model->db_update($db, "orders", $input_post_orders_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' ");
        $input_post_update_active = array(
            'status' => 'cancel'
            );
        $update_cart = $model->db_update($db, "cart", $input_post_update_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' "); 
       
    } else if ($transaction == 'expire') {
        $input_post_orders_active = array(
            'status' => 'unpaid',
            'created_at' => $now,
            );
        $update_orders = $model->db_update($db, "orders", $input_post_orders_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' ");
        $input_post_update_active = array(
            'status' => 'cancel'
            );
        $update_cart = $model->db_update($db, "cart", $input_post_update_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' "); 
        
    } else if ($transaction == 'cancel') {
        $input_post_orders_active = array(
            'status' => 'unpaid',
            'created_at' => $now,
            );
        $update_orders = $model->db_update($db, "orders", $input_post_orders_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' ");
        $input_post_update_active = array(
            'status' => 'cancel'
            );
        $update_cart = $model->db_update($db, "cart", $input_post_update_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' ");
        
        
    } 
}


?>