<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
require '../lib/csrf_token.php';



if (!isset($_GET['query_id'])) {
	exit("No direct script access allowed!3");
}
$id_query = mysqli_real_escape_string($db, $_GET['query_id']);
if (!isset($_SESSION['login'])) {
    $website = $model->db_query($db, "*", "website", "id = '1'");
            $data_target_service = $model->db_query($db, "*", "services", "id = '".mysqli_real_escape_string($db, $_GET['query_id'])."'");
            $services = $model->db_query($db, "*", "services", "id = '$id_query'");
    		$price_service = $services['rows']['price'];
    		$seller_info = $model->db_query($db, "*", "user", "id = '".$services['rows']['author']."'");
        
		    $kode_unik = rand(11111111,99999999);
		    
		    $extra_product = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['product1']))));
		    $extra_product1 = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['product2']))));
		    $extra_product2 = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['product3']))));
		    $quantity = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['quantity']))));
		    if($extra_product){
		        $price_extra_product = $services['rows']['price_extra_product'];
		    } 
		    if($extra_product1){
		        $price_extra_product1 = $services['rows']['price_extra_product1'];
		    } 
		    if($extra_product2){
		        $price_extra_product2 = $services['rows']['price_extra_product2']; 
		    } 
		
		    
		    $total_price_service = $price_service * $quantity;
		    
		    $total_price_setelah_extra = $total_price_service + $price_extra_product + $price_extra_product1 + $price_extra_product2 ;
		    $fee_admin_from_seller = ($website['rows']['admin_fee_seller']/100) * $total_price_setelah_extra ;
		    $price_seller = $total_price_setelah_extra - $fee_admin_from_seller ;
		    
		    $total_price_admin = $total_price_setelah_extra + $website['rows']['admin_fee'];
		    $kode_unik1 = rand(111,999);
		    $total_price_kode_unik = $total_price_admin + $kode_unik1 ;
		    $now = date("Y-m-d H:i:s");
            $expired_at = date('Y-m-d H:i:s',strtotime('+12 Hour',strtotime($now)));
		    
		    $input_post_cart = array(
		        'service_id' => $id_query,
		        'quantity' => $quantity,
		        'price' => $total_price_service,
		        'extra_product' => $extra_product,
		        'price_extra_product' => $price_extra_product,
		        'extra_product1' => $extra_product1,
		        'price_extra_product1' => $price_extra_product1,
		        'extra_product2' => $extra_product2,
		        'price_extra_product2' => $price_extra_product2,
		        'total_price' => $total_price_setelah_extra,
		        'total_price_admin' => $total_price_admin,
		        'price_kode_unik' => $total_price_kode_unik,
		        'status' => 'waiting',
		        'created_at' => date('Y-m-d H:i:s'),
		        'expired_date' => $expired_at,
		        'kode_unik' => $kode_unik,
		        'kode_invoice' => 'GD'.rand(11111111,99999999)
		        );
		       $insert_cart = $model->db_insert($db, "cart", $input_post_cart);
		       if ($insert_cart == true) {
		           
		        $input_post_orders = array(
		        'seller_id' => $services['rows']['author'],
		        'quantity' => $quantity,
		        'service_id' => $id_query,
		        'price' => $total_price_service,
		        'extra_product' => $extra_product,
		        'price_extra_product' => $price_extra_product,
		        'extra_product1' => $extra_product1,
		        'price_extra_product1' => $price_extra_product1,
		        'extra_product2' => $extra_product2,
		        'price_extra_product2' => $price_extra_product2,
		        'status' => 'unpaid',
		        'total_price' => $total_price_setelah_extra,
		        'price_for_seller' => $price_seller,
		        'kode_unik' => $kode_unik
		        );
		        $insert_orders = $model->db_insert($db, "orders", $input_post_orders);
		        if($insert_orders == true){
    			    exit(header("Location: ".$config['web']['base_url']."checkout-register/".$input_post_cart['kode_invoice']));
		        } else {
		            $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Terjadi Kesalahan! Tidak berhasil input Orderan, Silahkan Login');
		            exit(header("Location: ".$config['web']['base_url']."product/".$id_query."/".$data_target_service['rows']['url']));
		        }   
		       } else {
		           $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Terjadi Kesalahan! Tidak berhasil input Orderan, Silahkan Login');
		            exit(header("Location: ".$config['web']['base_url']."product/".$id_query."/".$data_target_service['rows']['url']));
		       }
}
$website = $model->db_query($db, "*", "website", "id = '1'");
$data_target_service = $model->db_query($db, "*", "services", "id = '".mysqli_real_escape_string($db, $_GET['query_id'])."'");
if ($data_target_service['count'] == 0) {
    exit("Data tidak ditemukan.");
}
if($data_target_service['rows']['author'] == $_SESSION['login']){
    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Anda Tidak Dapat Membeli Produk Milik Sendiri.');
    exit(header("Location: ".$config['web']['base_url']."product/".$id_query."/".$data_target_service['rows']['url']));
}
$id_service = mysqli_real_escape_string($db, $_GET['query_id']);

if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']));
} else {
    
    		$services = $model->db_query($db, "*", "services", "id = '$id_service'");
    		$price_service = $services['rows']['price'];
    		$seller_info = $model->db_query($db, "*", "user", "id = '".$services['rows']['author']."'");
        
		    $kode_unik = rand(11111111,99999999);
		    
		    $extra_product = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['product1']))));
		    $extra_product1 = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['product2']))));
		    $extra_product2 = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['product3']))));
		    $quantity = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['quantity']))));
		    if($extra_product){
		        $price_extra_product = $services['rows']['price_extra_product'];
		    } 
		    if($extra_product1){
		        $price_extra_product1 = $services['rows']['price_extra_product1'];
		    } 
		    if($extra_product2){
		        $price_extra_product2 = $services['rows']['price_extra_product2'];
		    } 
		
		    
		    $total_price_service = $price_service * $quantity;
		    
		    $total_price_setelah_extra = $total_price_service + $price_extra_product + $price_extra_product1 + $price_extra_product2 ;
		    $fee_admin_from_seller = ($website['rows']['admin_fee_seller']/100) * $total_price_setelah_extra ;
		    $price_seller = $total_price_setelah_extra - $fee_admin_from_seller ;
		    
		    $total_price_admin = $total_price_setelah_extra + $website['rows']['admin_fee'];
		    $kode_unik1 = rand(111,999);
		    $total_price_kode_unik = $total_price_admin + $kode_unik1 ;
		    $now = date("Y-m-d H:i:s");
            $expired_at = date('Y-m-d H:i:s',strtotime('+12 Hour',strtotime($now)));
		    
		    $input_post_cart = array(
		        'buyer_id' => $login['id'],
		        'service_id' => $id_service,
		        'quantity' => $quantity,
		        'price' => $total_price_service,
		        'extra_product' => $extra_product,
		        'price_extra_product' => $price_extra_product,
		        'extra_product1' => $extra_product1,
		        'price_extra_product1' => $price_extra_product1,
		        'extra_product2' => $extra_product2,
		        'price_extra_product2' => $price_extra_product2,
		        'total_price' => $total_price_setelah_extra,
		        'total_price_admin' => $total_price_admin,
		        'price_kode_unik' => $total_price_kode_unik,
		        'status' => 'waiting',
		        'created_at' => date('Y-m-d H:i:s'),
		        'expired_date' => $expired_at,
		        'kode_unik' => $kode_unik,
		        'kode_invoice' => 'GD'.rand(11111111,99999999)
		        );
		       $insert_cart = $model->db_insert($db, "cart", $input_post_cart);
		       if ($insert_cart == true) {
		           
		        $input_post_orders = array(
		        'buyer_id' => $login['id'],
		        'seller_id' => $services['rows']['author'],
		        'quantity' => $quantity,
		        'service_id' => $id_service,
		        'price' => $total_price_service,
		        'extra_product' => $extra_product,
		        'price_extra_product' => $price_extra_product,
		        'extra_product1' => $extra_product1,
		        'price_extra_product1' => $price_extra_product1,
		        'extra_product2' => $extra_product2,
		        'price_extra_product2' => $price_extra_product2,
		        'status' => 'unpaid',
		        'total_price' => $total_price_setelah_extra,
		        'price_for_seller' => $price_seller,
		        'kode_unik' => $kode_unik
		        );
		        $insert_orders = $model->db_insert($db, "orders", $input_post_orders);
		        if($insert_orders == true){
    			    exit(header("Location: ".$config['web']['base_url']."cart/"));
		        } else {
		            echo "Gagal Input Orderan";
		        }   
		       } else {
		           echo "Gagal Input Ke Keranjang";
		       }

}