<?

require 'web.php';
require 'lib/is_login.php';
require 'lib/check_session.php';
require 'lib/csrf_token.php';
include "lib/class/class.phpmailer.php";

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}

if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."my-sales/"));
}else {
    $data = array('puid','message_off', 'uid','produk');
    $puid = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['puid'])))); //puid =idpermintaan
     $produk = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['produk']))));
     $permintaan = $model->db_query($db, "*", "permintaan_pembeli", "id = '$puid' ");
    $service = $model->db_query($db, "*", "services", "id = '$produk' ");
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']));
	}else {
    	$validation = array(
    			'puid' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['puid'])))),
    			'message_off' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['message_off'])))),
    			'uid' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['uid'])))),
    			'produk' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['produk'])))),
    	);
    	$date = date('Y-m-d H:i:s');
		   $id_penerima_pesan = $validation['uid'];
        if($login['id'] > $validation['uid'] ){
            $chatAntara = $validation['uid']."-".$login['id'];
        } else {
            $chatAntara = $login['id']."-".$validation['uid'];
        }
        
        $user_penerima =  $model->db_query($db, "*", "user", "id = '".$validation['uid']."' ");
        $url = $config['web']['base_url']."product/".$service['rows']['id']."/".$service['rows']['url'];
        $message = $validation['message_off'];
        
    	if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
			exit(header("Location: ".$config['web']['base_url']));
		} elseif (strlen($validation['message_off']) > 800) {
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pesanya kepanjangan gan, Maksimal 800 Karakter.');
			exit(header("Location: ".$config['web']['base_url']));
		} else {
		    
            $check_chatAntara = mysqli_query($db, "SELECT * FROM reply_message WHERE chat_antara = '$chatAntara'");
            $check_lagi = mysqli_num_rows($check_chatAntara);
            if($check_lagi == 0){
                $input_post_message = array(
                'chat_antara' => $chatAntara,
		        'pengirim' => $login['id'],
		        'penerima' => $validation['uid'],
		        'dibaca_penerima' => 'no',
		        'date' => date('Y-m-d H:i:s'),
		        'message' => $message
		        );
		        $insert_message = $model->db_insert($db, "message", $input_post_message);
		        if($insert_message == true){
		            $input_post_reply_message = array(
		            'id_message' => $insert_message,
		            'pengirim' => $login['id'],
    		        'penerima' => $validation['uid'],
    		        'chat_antara' => $chatAntara,
    		        'date' => date('Y-m-d H:i:s'),
    		        'message' => $message,
    		        'permintaan' => $puid,
    		        'service_id' => $produk
    		        );
    		      $insert_reply_message = $model->db_insert($db, "reply_message", $input_post_reply_message);
    		      if($insert_reply_message == true){
    		           
    		          $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Penawaran Anda Telah Berhasil Terkirim.');
	                  exit(header("Location: ".$config['web']['base_url']));
    		      } else {
    		          $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Gagal Kirim Penawaran Code: (5).');
	                 exit(header("Location: ".$config['web']['base_url']));
    		      }
		            
		        } else {
		            echo "gagal request";
		        }
	       } else {
	                $check_chatAntara = mysqli_query($db, "SELECT * FROM reply_message WHERE chat_antara = '$chatAntara'");
	                $detail_reply_message= mysqli_fetch_assoc($check_chatAntara);
	                $query="UPDATE message SET chat_antara='$chatAntara',pengirim='".$login['id']."',penerima='".$validation['uid']."',dibaca_penerima='no',date='$date',message='$message' where id='".$detail_reply_message['id_message']."'";
	                $update2 = mysqli_query($db, $query);
	                        if($update2 == true){
	                            $input_post_reply_message2 = array(
            		            'id_message' => $detail_reply_message['id_message'],
            		            'pengirim' => $login['id'],
                		        'penerima' => $validation['uid'],
                		        'chat_antara' => $chatAntara,
                		        'date' => date('Y-m-d H:i:s'),
                		        'message' => $message,
    		                    'permintaan' => $puid,
    		                    'service_id' => $produk
                		        );
                		      $insert_reply_message2 = $model->db_insert($db, "reply_message", $input_post_reply_message2);
                		      if($insert_reply_message2 == true){
                		           
                		          $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Pesan Anda Telah Berhasil Terkirim.');
    			                  exit(header("Location: ".$config['web']['base_url']));
                		      } else {
                		          $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Gagal Kirim Pesan (2).');
    			                 exit(header("Location: ".$config['web']['base_url']));
                		      }
	                        } else {
	                            echo "gagal update";
	                        }
    	                    
	                
	            }
                   
		    
		}
	    
	}
    
    
}