<?php
require '../web.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
$lama_review = $website['rows']['review_otomatis'];

$check_cart = mysqli_query($db, "SELECT * FROM cart WHERE status in ('waiting','pending') ");
if(mysqli_num_rows($check_cart) == 0){
    die("Tidak table cart pending.");
} else {
    while($data_cart = mysqli_fetch_assoc($check_cart)){
        $expire_cart = $data_cart['expired_date'];
        $now = date("Y-m-d H:i:s");
        $id = $data_cart['kode_unik'];
        if (strtotime($now) > strtotime($expire_cart)){
        $update_cart = $db->query("UPDATE cart SET status = 'cancel' WHERE kode_unik = '$id' ");
        $update_cart = $db->query("UPDATE orders SET status = 'unpaid' WHERE kode_unik = '$id' ");
        
        } else {
            echo "Tidak Ada yang expired";
        }
        
    }
    
}