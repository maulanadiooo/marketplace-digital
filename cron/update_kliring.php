<?php
require '../web.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
$lama_kliring = $website['rows']['lama_kliring'];
$admin_fee_seller = $website['rows']['admin_fee_seller'];

$check_orderan = mysqli_query($db, "SELECT * FROM orders WHERE status = 'complete' AND reviewed = '1' AND kliring = '0'");
if(mysqli_num_rows($check_orderan) == 0){
    die("Tidak ada yang perlu di kliring.");
} else {
    while($data_orderan = mysqli_fetch_assoc($check_orderan)){
        $id = $data_orderan['id'];
        $service_id = $data_orderan['service_id'];
        $buyer_id = $data_orderan['buyer_id'];
        $seller_id = $data_orderan['seller_id'];
        $price_for_seller = $data_orderan['price_for_seller'];
        $review_time = $data_orderan['review_time'];
        $admin_fee_from_seller = ($admin_fee_seller/100) * $data_orderan['total_price'];
        $now = date("Y-m-d 23:59:59");
        $kliring_setelah = date('Y-m-d H:i:s',strtotime('+'.$lama_kliring.' Day',strtotime($review_time)));
        if (strtotime($now) >= strtotime($kliring_setelah)){
        $update_saldo_user = $db->query("UPDATE user set saldo_tersedia = saldo_tersedia+$price_for_seller WHERE id = '$seller_id'");
        $model->db_update($db, "orders", array('kliring' => '1'), "id = '$id'");
        if($update_saldo_user == TRUE){
            
            $model->db_update($db, "penghasilan_admin", array('admin_fee_seller' => $admin_fee_from_seller), "order_id = '$id'");
        } else {
            echo "Database Eror.<br />";
        }
        } 
        
    }
    
}