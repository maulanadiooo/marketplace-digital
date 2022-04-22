<?

require '../web.php';
require '../lib/is_login.php';
require '../lib/check_session.php';
require '../lib/csrf_token.php';
include "../lib/class/class.phpmailer.php";
// echo "untuk sementara silahkan kirim pesan langsung ke seller dengan klik kirim pesan do profile seller";
// exit;
if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$login['id']."'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}

if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."my-sales/"));
}else {
    $data = array('puid','supmsg', 'uid');
    $puid = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['puid']))));
    $service = $model->db_query($db, "*", "services", "id = '$puid' ");
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."product/".$service['rows']['id']."/".$service['rows']['url']));
	}else {
    	$validation = array(
    			'puid' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['puid'])))),
    			'supmsg' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['supmsg'])))),
    			'uid' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['uid'])))),
    	);
    	$date = date('Y-m-d H:i:s');
		    
        if($login['id'] > $validation['uid'] ){
            $chatAntara = $validation['uid']."-".$login['id'];
        } else {
            $chatAntara = $login['id']."-".$validation['uid'];
        }
        
        $user_penerima =  $model->db_query($db, "*", "user", "id = '".$validation['uid']."' ");
        
        $message = $validation['supmsg'];
        
    	if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
			exit(header("Location: ".$config['web']['base_url']."product/".$service['rows']['id']."/".$service['rows']['url']));
		} elseif (strlen($validation['supmsg']) > 800) {
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pesanya kepanjangan gan, Maksimal 800 Karakter.');
			exit(header("Location: ".$config['web']['base_url']."product/".$service['rows']['id']."/".$service['rows']['url']));
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
    		        'terkait' => $puid
    		        );
    		      $insert_reply_message = $model->db_insert($db, "reply_message", $input_post_reply_message);
    		      if($insert_reply_message == true){
    		          // kirim email
                        $pengirim_pesan = $model->db_query($db, "*", "user", "id = '".$login['id']."'");
                         $email_message = $model->db_query($db, "*", "email", "id = '5'");
                        $penerima_email = $model->db_query($db, "*", "user", "id = '".$validation['uid']."' ");			    
                        $ke = decrypt($penerima_email['rows']['email']);
                        $nama = $penerima_email['rows']['nama'];
                        $format = $email_message['rows']['email'];
                        $pisah = explode("{{username}}", $format);
                        $isi_pesan = $pisah[0].$pengirim_pesan['rows']['username'].$pisah[1];
                        
                        $subject = "Anda Menerima Pesan Baru";
				        kirim_email($ke, $nama, $isi_pesan, $subject); 
				        
    		          $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Pesan Anda Telah Berhasil Terkirim Kepada Penjual.');
	                  exit(header("Location: ".$config['web']['base_url']."product/".$service['rows']['id']."/".$service['rows']['url']));
    		      
		            
		        } else {
		            echo "bad request!";
		        }
	       } else {
	                
    	         echo "bad request!";           
	                
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
        		        'terkait' => $puid
        		        );
        		      $insert_reply_message2 = $model->db_insert($db, "reply_message", $input_post_reply_message2);
        		      if($insert_reply_message2 == true){
        		          // kirim email
                        $pengirim_pesan = $model->db_query($db, "*", "user", "id = '".$login['id']."'");
                         $email_message = $model->db_query($db, "*", "email", "id = '5'");
                        $penerima_email = $model->db_query($db, "*", "user", "id = '".$validation['uid']."' ");			    
                        $ke = decrypt($penerima_email['rows']['email']);
                        $nama = $penerima_email['rows']['nama'];
                        $format = $email_message['rows']['email'];
                        $pisah = explode("{{username}}", $format);
                        $isi_pesan = $pisah[0].$pengirim_pesan['rows']['username'].$pisah[1];
                        
                        $subject = "Anda Menerima Pesan Baru";
		                kirim_email($ke, $nama, $isi_pesan, $subject); 
		                if($penerima_email['rows']['terima_tele_pesan'] == '1'){
                                           $text = 'Hallo '.$penerima_email['rows']['username'].'
Kamu mendapatkan pesan baru dari '.$pengirim_pesan['rows']['username'].'
Silahkan masuk ke Gubukdigital.net untuk membaca pesannya ya ^.^

Regards
GubukDigital';
                                        $teks = urlencode($text);
                                        
                                        $chat_id = decrypt($penerima_email['rows']['telegram_id']);
                                        kirim_tele($teks, $chat_id); 
                                        }
        		          $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Pesan Anda Telah Berhasil Terkirim Kepada Penjual.');
		                  exit(header("Location: ".$config['web']['base_url']."product/".$service['rows']['id']."/".$service['rows']['url']));
        		      } else {
        		          $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Gagal Kirim Pesan (2).');
		                 exit(header("Location: ".$config['web']['base_url']."product/".$service['rows']['id']."/".$service['rows']['url']));
        		      }
                    } else {
                        echo "gagal update";
                    }
		}
	    
	}
    
	}
}