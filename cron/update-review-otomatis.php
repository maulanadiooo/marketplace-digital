<?php
require '../web.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
$lama_review = $website['rows']['review_otomatis'];

$check_reviewed = mysqli_query($db, "SELECT * FROM orders WHERE status = 'success' AND reviewed != '1' ");
if(mysqli_num_rows($check_reviewed) == 0){
    die("Tidak ada yang perlu di review.");
} else {
    while($data_orders_review = mysqli_fetch_assoc($check_reviewed)){
        $id = $data_orders_review['id'];
        $service_id = $data_orders_review['service_id'];
        $buyer_id = $data_orders_review['buyer_id'];
        $seller_id = $data_orders_review['seller_id'];
        $delivery_time = $data_orders_review['delivery_time'];
        $now = date("Y-m-d 23:59:59");
        $review_setelah = date('Y-m-d H:i:s',strtotime('+'.$lama_review.' Day',strtotime($delivery_time)));
        if (strtotime($now) >= strtotime($review_setelah)){
        $update_reviews = $db->query("UPDATE orders SET status = 'complete' WHERE id = '$id' ");
        
        if($update_reviews == TRUE){
            $input_post = array(
	        'order_id' => $id,
	        'service_id' => $service_id,
	        'rating' => 5,
	        'based_on' => 'sistem',
	        'comment' => '[Review Otomatis by Sistem]',
	        'user_id' => $buyer_id,
	        'seller_id' => $seller_id,
	        'created_at' => $now
	        );
            $model->db_insert($db, "review_order", $input_post);
            $update = $db->query("UPDATE services set total_sales = total_sales+1 WHERE id = '".$data_orders_review['service_id']."'");
            $model->db_update($db, "orders", array('reviewed' => '1'), "id = '$id'");
            $model->db_update($db, "orders", array('review_time' => $now), "id = '$id'");
        } else {
            echo "Database Eror.<br />";
        }
        } 
        
    }
    
}