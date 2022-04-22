<?php
require '../web.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
$lama_review = $website['rows']['review_otomatis'];

$check_depo = mysqli_query($db, "SELECT * FROM deposit WHERE status = 'pending' ");
if(mysqli_num_rows($check_depo) == 0){
    die("Tidak table depo pending.");
} else {
    while($data_depo = mysqli_fetch_assoc($check_depo)){
        $expire_depo = $data_depo['expired_at'];
        $now = date("Y-m-d H:i:s");
        $id = $data_depo['id'];
        if (strtotime($now) > strtotime($expire_depo)){
        $update_cart = $db->query("UPDATE deposit SET status = 'error' WHERE id = '$id' ");
        
        } else {
            echo "Tidak Ada yang expired";
        }
        
    }
    
}