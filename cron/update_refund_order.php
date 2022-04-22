<?php
require '../web.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
$lama_kliring = $website['rows']['lama_kliring'];
$admin_fee_seller = $website['rows']['admin_fee_seller'];

$check_orderan = mysqli_query($db, "SELECT * FROM orders WHERE status = 'refund'");
if(mysqli_num_rows($check_orderan) == 0){
    die("Tidak ada yang perlu di refund.");
} else {
    while($data_orderan = mysqli_fetch_assoc($check_orderan)){
        $id = $data_orderan['id'];
        $buyer_id = $data_orderan['buyer_id'];
        $seller_id = $data_orderan['seller_id'];
        $refund_time = $data_orderan['refund_time'];
        $now = date("Y-m-d H:i:s");
        $kode_unik = $data_orderan['kode_unik'];
        if (strtotime($now) > strtotime($refund_time)){
        $sent = 'setujurefund';
        $input_posts_refund = array(
        'user_id' => $seller_id,
        'orders_id' => $id,
        'message' => '[Batal Otomatis by Sistem]',
        'message_status' => $sent,
        'created_at' => date('Y-m-d H:i:s')
        );
        $insert_refund = $model->db_insert($db, "conversation_order", $input_posts_refund);
            $updatea_refund = $model->db_update($db, "orders", array('status' => 'refunded'), "id = '$id'"); 
	        if($insert_refund == true && $updatea_refund == true){
	            $cart = $model->db_query($db, "*", "cart", "kode_unik = '$kode_unik'");
	            $buyer = $model->db_query($db, "*", "user", "id = '$buyer_id'");
	            $updatea_balance = $model->db_update($db, "user", array('saldo_tersedia' => $buyer['rows']['saldo_tersedia'] + $cart['rows']['total_price_admin']), "id = '$buyer_id'"); 
	            $updatea_penghasilan = $model->db_update($db, "penghasilan_admin", array('admin_fee' => '0'), "order_id = '$id'");
	            
	        } 
        
            
        } 
        
    }
    
}