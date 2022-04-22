<?php
require '../web.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
$lama_review = $website['rows']['review_otomatis'];

$check_services = mysqli_query($db, "SELECT * FROM services WHERE status = 'delete' AND deleted = '1' ");
if(mysqli_num_rows($check_services) == 0){
    die("Tidak ada produk yang akan di delete pengguna.");
} else {
    while($data_services = mysqli_fetch_assoc($check_services)){
        $expire_delete = $data_services['deleted_time'];
        $now = date("Y-m-d H:i:s");
        $id = $data_services['id'];
        if (strtotime($now) > strtotime($expire_delete)){
        $update_service = $db->query("UPDATE services SET deleted = '2' WHERE id = '$id' ");
        
        } 
        
    }
    
}