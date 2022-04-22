<?

require '../web.php';
require '../lib/is_login.php';
require '../lib/check_session.php';
require '../lib/csrf_token.php';
include "../lib/class/class.phpmailer.php";

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}

if (!isset($_GET['query_id'])) {
	exit(header("Location: ".$config['web']['base_url']."my-sales/".$data_target['rows']['id']));
}
$data_target = $model->db_query($db, "*", "orders", "id = '".mysqli_real_escape_string($db, $_GET['query_id'])."'");
if ($data_target['count'] == 0) {
    exit(header("Location: ".$config['web']['base_url']."my-sales/"));
}
if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."my-sales/")); 
} else {
    $data = array('deskripsi');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id']));
	}else {
	    $validation = array(
			'deskripsi' => htmlentities($_POST['deskripsi'], ENT_NOQUOTES),
		);
		$send_product = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['send_product']))));
		if($send_product == "send"){
            $status = 'success';
            $sent = 'success';
        } elseif($send_product == "cancel"){
            $status = 'cancel';
            $sent = 'cancel';
        }elseif($send_product == "refund"){
            $status = 'refund';
            $sent = 'refund';
        }else {
            if($data_target['rows']['status'] == 'refund'){
                $status = 'active';
            } else {
                $status = $data_target['rows']['status'];
            }
            $sent = 'just_message';
        }
        
        if($login['id'] == $data_target['rows']['seller_id']){
               $penerima_notifikasi = $data_target['rows']['buyer_id'];
           } elseif($login['id'] == $data_target['rows']['buyer_id']) {
               $penerima_notifikasi = $data_target['rows']['seller_id'];
           }
        
        if($send_product == "setujurefund"){
            $sent = 'setujurefund';
            $input_posts_refund = array(
	        'user_id' => $login['id'],
	        'orders_id' => $data_target['rows']['id'],
	        'message' => $validation['deskripsi'],
	        'message_status' => $sent,
	        'created_at' => date('Y-m-d H:i:s')
	        );
	        $insert_refund = $model->db_insert($db, "conversation_order", $input_posts_refund);
	        $updatea_refund = $model->db_update($db, "orders", array('status' => 'refunded', 'price_for_seller' => '0' ), "id = '".$data_target['rows']['id']."'"); 
	        if($insert_refund == true && $updatea_refund == true){
	            $cart = $model->db_query($db, "*", "cart", "kode_unik = '".$data_target['rows']['kode_unik']."'");
	            $buyer = $model->db_query($db, "*", "user", "id = '".$data_target['rows']['buyer_id']."'");
	            $updatea_balance = $model->db_update($db, "user", array('saldo_tersedia' => $buyer['rows']['saldo_tersedia'] + $cart['rows']['total_price_admin']), "id = '".$data_target['rows']['buyer_id']."'"); 
	            $updatea_penghasilan = $model->db_update($db, "penghasilan_admin", array('admin_fee' => '0'), "order_id = '".$data_target['rows']['id']."'");
	            if($updatea_balance == true && $updatea_penghasilan == true){
	                $email_orderan = $model->db_query($db, "*", "email", "id = '4'");
                    $penerima_email = $model->db_query($db, "*", "user", "id = '$penerima_notifikasi' ");			    
                    $ke = decrypt($penerima_email['rows']['email']);
                    $nama = $penerima_email['rows']['nama'];
                    $format = $email_orderan['rows']['email'];
                    $pisah = explode("{{link_penjualan}}", $format);
                    $orderan_link = $pisah[0].$config['web']['base_url']."show-sales/".$data_target['rows']['id'].$pisah[1];
                    
                    $subject = "Pembaruan Pesanan";
				    kirim_email($ke, $nama, $orderan_link, $subject); 
	                if($penerima_email['rows']['terima_tele_orderan'] == '1'){
                                           $text = 'Hallo '.$penerima_email['rows']['username'].'
Pesananmu baru saja diperbaharui
Silahkan Login Pada Gubukdigital.net untuk memproses penjualanmu
            
Pesan Ini Dibuat Secara Otomatis

Regards
Gubuk Digital';
                                        $teks = urlencode($text);
                                        
                                        $chat_id = decrypt($penerima_email['rows']['telegram_id']);
                                        kirim_tele($teks, $chat_id); 
                                        }
	                $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Pembatalan Bersama Telah Disetujui, Dana Kembali Ke Pembeli');
    		        exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id']));
	            } else {
	               echo "kesalahan, hubungi admin code: 01";
	            }
	        } else {
	            echo "kesalahan, hubungi admin code: 02";
	        }
            
        }
		$fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_FILES['file']['name']))));
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        // sanitize file-name
        $namaGabung = strtr( $fileName, " ", "-" );
        
        $newFileName = md5(time() . $fileName) .'-'. $namaGabung;
     
        // check if file has one of the following extensions
        $allowedfileExtensions = array('jpeg', 'jpg', 'gif', 'png', 'tif', 'bmp', 'avi', 'mpeg', 'mpg', 'mov', 'rm', '3gp', 'flv', 'mp4', 'zip', 'rar', 'mp3', 'wav', 'wma', 'ogg');
     
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
			exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id']));
		} elseif (strlen($validation['deskripsi']) > 1610) {
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maksimal 1500 Karakter.');
			exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id']));
		} elseif (strlen($validation['deskripsi']) <= 110) {
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pesan Tidak boleh kosong.');
			exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id']));
		}  else {
		    if(file_exists($_FILES['file']['tmp_name'])){
		    $ekstensi_diperbolehkan	= array('png','jpg', 'jpeg', 'rar', 'zip', 'txt', 'xls', 'xlt', 'xlsx', 'doc', 'docx');
		    if($fileSize > 20971520 ){
	           $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'maksimal file 20MB');
	            exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id'])); 
	        } elseif(in_array($fileExtension, $allowedfileExtensions) === false){
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Extensi File Tidak diizinkan');
    		    exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id']));
    		} else {
    		    $uploadFileDir = '../files-produk/';
                  $dest_path = $uploadFileDir . $newFileName;
                  
                  move_uploaded_file($fileTmpPath, $dest_path);
                  
                 $update = $model->db_update($db, "orders", array('file' => $newFileName), "id = '".$data_target['rows']['id']."'"); 
	           
    		    $input_posts = array(
		        'user_id' => $login['id'],
		        'orders_id' => $data_target['rows']['id'],
		        'message' => $validation['deskripsi'],
		        'message_status' => $sent,
		        'file' => $newFileName,
		        'created_at' => date('Y-m-d H:i:s')
		        );
		        $insert = $model->db_insert($db, "conversation_order", $input_posts);
		        $updatea = $model->db_update($db, "orders", array('status' => $status), "id = '".$data_target['rows']['id']."'"); 
		        if($send_product == 'send'){
    	           $now = date('Y-m-d H:i:s');
		            $update_complete = $model->db_update($db, "orders", array('delivery_time' => $now), "id = '".$data_target['rows']['id']."'");  
    	        }
		        if($updatea == true && $insert == true){
		            
		      //  if($send_product == 'send'){
		      //      $now = date('Y-m-d H:i:s');
		      //      $update_complete = $model->db_update($db, "orders", array('delivery_time' => $now), "id = '".$data_target['rows']['id']."'"); 
		      //  }
		        if($send_product == 'refund'){
    	            $now = date('Y-m-d H:i:s');
    	            $refund_time = date('Y-m-d H:i:s',strtotime('+24 Hour',strtotime($now)));
    	            $update_complete = $model->db_update($db, "orders", array('refund_time' => $refund_time), "id = '".$data_target['rows']['id']."'"); 
    	        }
		            
		           if($send_product == 'send'){
		               $type = 'pesanan-success';
		           } elseif($send_product == 'cancel'){
		               $type = 'pesanan-cancel';
		           }else{
		               $type = 'pesanan-perbarui';
		           }
		            
		           $input_posts_notifikasi = array(
    		        'buyer_id' => $login['id'],
    		        'seller_id' => $penerima_notifikasi,
    		        'service_id' => $data_target['rows']['service_id'],
    		        'type' => $type,
    		        'go' => "show-sales/".$data_target['rows']['id'],
    		        'created_at' => date('Y-m-d H:i:s')
    		        );
    		        $model->db_insert($db, "notifikasi", $input_posts_notifikasi); 
		            
		            // kirim email
		           
                     $email_orderan = $model->db_query($db, "*", "email", "id = '4'");
                    $penerima_email = $model->db_query($db, "*", "user", "id = '$penerima_notifikasi' ");			    
                    $ke = decrypt($penerima_email['rows']['email']);
                    $nama = $penerima_email['rows']['nama'];
                    $format = $email_orderan['rows']['email'];
                    $pisah = explode("{{link_penjualan}}", $format);
                    $orderan_link = $pisah[0].$config['web']['base_url']."show-sales/".$data_target['rows']['id'].$pisah[1];
                    
                     $subject = "Pembaruan Pesanan";
				    kirim_email($ke, $nama, $orderan_link, $subject); 
		            if($penerima_email['rows']['terima_tele_orderan'] == '1'){
                                           $text = 'Hallo '.$penerima_email['rows']['username'].'
Pesananmu baru saja diperbaharui
Silahkan Login Pada Gubukdigital.net untuk memproses penjualanmu
            
Pesan Ini Dibuat Secara Otomatis

Regards
Gubuk Digital';
                                        $teks = urlencode($text);
                                        
                                        $chat_id = decrypt($penerima_email['rows']['telegram_id']);
                                        kirim_tele($teks, $chat_id); 
                                        }
		            
		           $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Orderan diupdate.');
            	  exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id'])); 
		        } else {
		            $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Orderan tidak berhasil diupdate.');
            	  exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id'])); 
		        }
		         
    		    
    		}
		  } else {
		      $input_postsn = array(
    	        'user_id' => $login['id'],
    	        'orders_id' => $data_target['rows']['id'],
    	        'message' => $validation['deskripsi'],
    	        'message_status' => $sent,
    	        'created_at' => date('Y-m-d H:i:s')
    	        );
    	        $insert = $model->db_insert($db, "conversation_order", $input_postsn);
    	        $updatean = $model->db_update($db, "orders", array('status' => $status), "id = '".$data_target['rows']['id']."'"); 
    	        if($send_product == 'send'){
    	           $now = date('Y-m-d H:i:s');
		            $update_complete = $model->db_update($db, "orders", array('delivery_time' => $now), "id = '".$data_target['rows']['id']."'");  
    	        }
    	        if($send_product == 'refund'){
    	            $now = date('Y-m-d H:i:s');
    	            $refund_time = date('Y-m-d H:i:s',strtotime('+24 Hour',strtotime($now)));
    	            $update_complete = $model->db_update($db, "orders", array('refund_time' => $refund_time), "id = '".$data_target['rows']['id']."'"); 
    	        }
    	        if($updatean == true && $insert == true){
    	             if($login['id'] == $data_target['rows']['seller_id']){
		               $penerima_notifikasi = $data_target['rows']['buyer_id'];
		           } elseif($login['id'] == $data_target['rows']['buyer_id']) {
		               $penerima_notifikasi = $data_target['rows']['seller_id'];
		           }
		           if($send_product == 'send'){
		               $type = 'pesanan-success';
		           } elseif($send_product == 'cancel'){
		               $type = 'pesanan-cancel';
		           }else{
		               $type = 'pesanan-perbarui';
		           }
		            
		           $input_posts_notifikasi = array(
    		        'buyer_id' => $login['id'],
    		        'seller_id' => $penerima_notifikasi,
    		        'service_id' => $data_target['rows']['service_id'],
    		        'type' => $type,
    		        'go' => "show-sales/".$data_target['rows']['id'],
    		        'created_at' => date('Y-m-d H:i:s')
    		        );
    		        
    		        // kirim email
		           
                     $email_orderan = $model->db_query($db, "*", "email", "id = '4'");
                    $penerima_email = $model->db_query($db, "*", "user", "id = '$penerima_notifikasi' ");			    
                    $ke = decrypt($penerima_email['rows']['email']);
                    $nama = $penerima_email['rows']['nama'];
                    $format = $email_orderan['rows']['email'];
                    $pisah = explode("{{link_penjualan}}", $format);
                    $orderan_link = $pisah[0].$config['web']['base_url']."show-sales/".$data_target['rows']['id'].$pisah[1];
                    
                    $subject = "Pembaruan Pesanan";
				    kirim_email($ke, $nama, $orderan_link, $subject); 
                    if($penerima_email['rows']['terima_tele_orderan'] == '1'){
                                           $text = 'Hallo '.$penerima_email['rows']['username'].'
Pesananmu baru saja diperbaharui
Silahkan Login Pada Gubukdigital.net untuk memproses penjualanmu
            
Pesan Ini Dibuat Secara Otomatis

Regards
Gubuk Digital';
                                        $teks = urlencode($text);
                                        
                                        $chat_id = decrypt($penerima_email['rows']['telegram_id']);
                                        kirim_tele($teks, $chat_id); 
                                        }
    		        $model->db_insert($db, "notifikasi", $input_posts_notifikasi); 
		           $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Orderan diupdate.');
            	  exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id'])); 
		        } else {
		            $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Orderan tidak berhasil diupdate.');
            	  exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id'])); 
		        }
		      
		  }
		}
	}
    
}


