<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/check_session.php';
require '../lib/csrf_token.php';

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if (!isset($_GET['query_id'])) {
	exit("No direct script access allowed!3");
} else{
    $data_target = $model->db_query($db, "*", "orders", "id = '".mysqli_real_escape_string($db, $_GET['query_id'])."'");
    if ($data_target['count'] == 0) {
        exit("Data tidak ditemukan.");
    }
    if(!isset($_POST)){
        exit(header("Location: ".$config['web']['base_url']."my-product/"));
    } else {
        $data = array('rating', 'review_reason', 'rating_field');
        if (check_input($_POST, $data) == false) {
    		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
    		exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id']));
    	}else {
    	    $validation = array(
        		'rating' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['rating'])))),
        		'review_reason' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['review_reason'])))),
        		'rating_field' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['rating_field'])))),
        	);
        	if (check_empty($validation) == true) {
    			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
    			exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id']));
    		} else {
    		    if(strlen($validation['rating_field']) < 20){
    		         $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Minimal Review 20 Karakter.');
    			    exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id']));
    		    } elseif(strlen($validation['rating_field']) > 150){
    		         $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maksimal Review 150 Karakter.');
    			    exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id']));
    		    } else {
    		        $input_post = array(
        		        'order_id' => $data_target['rows']['id'],
        		        'service_id' => $data_target['rows']['service_id'],
        		        'rating' => $validation['rating'],
        		        'based_on' => $validation['review_reason'],
        		        'comment' => $validation['rating_field'],
        		        'user_id' => $login['id'],
        		        'seller_id' => $data_target['rows']['seller_id'],
        		        'created_at' => date('Y-m-d H:i:s')
        		        );
        		      if ($model->db_insert($db, "review_order", $input_post) == true) {
        		          if($login['id'] == $data_target['rows']['seller_id']){
        		               $penerima_notifikasi = $data_target['rows']['buyer_id'];
        		           } elseif($login['id'] == $data_target['rows']['buyer_id']) {
        		               $penerima_notifikasi = $data_target['rows']['seller_id'];
        		           }
        		            $input_posts_notifikasi = array(
            		        'buyer_id' => $login['id'],
            		        'seller_id' => $penerima_notifikasi,
            		        'service_id' => $data_target['rows']['service_id'],
            		        'type' => 'review',
            		        'go' => "show-sales/".$data_target['rows']['id'],
            		        'created_at' => date('Y-m-d H:i:s')
            		        );
            		        $model->db_insert($db, "notifikasi", $input_posts_notifikasi); 
        		          
        		            $now =date('Y-m-d H:i:s');    
        		            $update = $db->query("UPDATE services set total_sales = total_sales+1 WHERE id = '".$data_target['rows']['service_id']."'");
				            $update = $model->db_update($db, "orders", array('status' => 'complete'), "id = '".$data_target['rows']['id']."'");
				            $update = $model->db_update($db, "orders", array('reviewed' => '1'), "id = '".$data_target['rows']['id']."'");
				            $update = $model->db_update($db, "orders", array('review_time' => $now), "id = '".$data_target['rows']['id']."'");
				            
				            // kirim email
        		           include "../lib/class/class.phpmailer.php";
                             $email_orderan = $model->db_query($db, "*", "email", "id = '4'");
                            $penerima_email = $model->db_query($db, "*", "user", "id = '$penerima_notifikasi' ");			    
                            $ke = decrypt($penerima_email['rows']['email']);
                            $nama = $penerima_email['rows']['nama'];
                            $format = $email_orderan['rows']['email'];
                            $pisah = explode("{{link_penjualan}}", $format);
                            $orderan_link = $pisah[0].$config['web']['base_url']."show-sales/".$data_target['rows']['id'].$pisah[1];
                            
                            $subject = "Pembaruan Pesanan";
				            kirim_email($ke, $nama, $orderan_link, $subject); 
				            
        					$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Review Anda Telah Terkirim, Terimakasih ^.^');
        				    exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id']));
        				       
        				 
        				}else {
        					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Tidak Berhasil input review, Contact Admin Website.');
        					exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id']));
        				}
    		    }
    		    
    		}
    	    
    	}
        
    }

}