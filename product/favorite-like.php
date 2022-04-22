<?php
require '../web.php';
require '../lib/result.php';

if (!isset($_GET['query_id']) OR !isset($_GET['status'])) {
	exit("No direct script access allowed!3");
}

$data_target = $model->db_query($db, "*", "services", "id = '".mysqli_real_escape_string($db, $_GET['query_id'])."'");
$id = $data_target['rows']['id'];
$nama_layanan =$data_target['rows']['url'];
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Kamu harus login untuk like produk ini ^.^');
	exit(header("Location: ".$config['web']['base_url']."product/".$id."/".$nama_layanan));
}

if (in_array($_GET['status'], array('like','unlike')) == false) {
	exit("No direct script access allowed!4");
}
if ($data_target['count'] == 0) {
    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Layanan Tidak Ditemukan.');
	exit(header("Location: ".$config['web']['base_url']."product/".$id."/".$nama_layanan));
} else {
    $url = $config['web']['base_url']."product/".$id."/".$nama_layanan;
    if ($_GET['status'] == 'like') {
        $check_like = mysqli_query($db, "SELECT * FROM favorite WHERE user_id = '".$_SESSION['login']."' AND service_id = '".$data_target['rows']['id']."'");
        $input_post_notifikasi = array(
	        'buyer_id' => $_SESSION['login'],
	        'seller_id' => $data_target['rows']['author'],
	        'service_id' => $data_target['rows']['id'],
	        'type' => 'favorit',
	        'go' => "product/".$data_target['rows']['id']."/".$data_target['rows']['url'],
	        'created_at' => date('Y-m-d H:i:s'),
	        );
	        $insert_post_notifikasi = $model->db_insert($db, "notifikasi", $input_post_notifikasi);
        if (mysqli_num_rows($check_like) == 0) {
		        $input_post = array(
				'service_id' => mysqli_real_escape_string($db, $_GET['query_id']),
				'user_id' => $_SESSION['login'],	
				'status' => 'like'
		        );
		        $update = $model->db_insert($db, "favorite", $input_post);
		        if($update == true){
        	           $update_like = $db->query("UPDATE services set like_fav = like_fav+1 WHERE id = '".mysqli_real_escape_string($db, $_GET['query_id'])."'"); 
        	           if($update == true){
        	               $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Produk Sudah berhasil di like/Favoritkan!.');
		                    exit(header("Location: ".$config['web']['base_url']."product/".$id."/".$nama_layanan));
        	           }
        	   } else {
        	       $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Like Tidak Berhasil di Input.');
            	    exit(header("Location: ".$config['web']['base_url']."product/".$id."/".$nama_layanan));
        	   }
        } else {
            $update_like = $db->query("UPDATE services set like_fav = like_fav+1 WHERE id = '".mysqli_real_escape_string($db, $_GET['query_id'])."'"); 
            $update_like = $db->query("UPDATE favorite set status = 'like' WHERE service_id = '".$data_target['rows']['id']."' AND user_id ='".$_SESSION['login']."' "); 
               if($update_like == true){
                 $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Produk Sudah berhasil di like/Favoritkan!.');
    		    exit(header("Location: ".$config['web']['base_url']."product/".$id."/".$nama_layanan));
               }
        }
	} else {
	    $input_post_notifikasi = array(
	        'buyer_id' => $_SESSION['login'],
	        'seller_id' => $data_target['rows']['author'],
	        'service_id' => $data_target['rows']['id'],
	        'type' => 'unfavorit',
	        'go' => "product/".$data_target['rows']['id']."/".$data_target['rows']['url'],
	        'created_at' => date('Y-m-d H:i:s'),
	        );
	        $insert_post_notifikasi = $model->db_insert($db, "notifikasi", $input_post_notifikasi);
	    
	    $update_unlike1 = $db->query("UPDATE favorite set status = 'unlike' WHERE user_id = '".$_SESSION['login']."' AND service_id = '".$data_target['rows']['id']."'");
	    $update_unlike = $db->query("UPDATE services set like_fav = like_fav-1 WHERE id = '".mysqli_real_escape_string($db, $_GET['query_id'])."'");
	    if($update_unlike1 == true){
	       
		$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Produk Sudah berhasil di unlike/unfavoritkan!.');
		exit(header("Location: ".$config['web']['base_url']."product/".$id."/".$nama_layanan));
	    }
	}
    
    
    
    
	
    
}
require '../lib/result.php';


