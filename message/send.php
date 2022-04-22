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

if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."my-sales/"));
}else {
    $data = array('msgatr','message');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."show-sales/".$data_target['rows']['id']));
	}else {
    	$validation = array( 
    			'msgatr' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['msgatr'])))),
    			'message' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['message'])))),
    	);
    	$date = date('Y-m-d H:i:s');
		    
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
    	
    	
    	$format = $validation['msgatr'];
        $pisah = explode("-", $format);
        $user_pertama = $pisah[0];
        $user_kedua = $pisah[1];
        if($user_pertama == $login['id']){
            $id_penerima_pesan = $user_kedua;
            
        }else{
            $id_penerima_pesan = $user_pertama;
        }
        if($login['id'] > $id_penerima_pesan ){
            $kecil = $id_penerima_pesan;
            $besar = $login['id'];
            $chatAntara = $id_penerima_pesan."-".$login['id'];
        } else {
            $kecil = $login['id'];
            $besar = $id_penerima_pesan;
            $chatAntara = $login['id']."-".$id_penerima_pesan;
        }
        
        
        $user_penerima =  $model->db_query($db, "*", "user", "id = '$id_penerima_pesan' ");
        
    	if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
			exit(header("Location: ".$config['web']['base_url']."conversation/".$user_penerima['rows']['username']));
		} elseif (strlen($validation['message']) > 5000) {
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pesanya kepanjangan gan, Maksimal 5000 Karakter.');
			exit(header("Location: ".$config['web']['base_url']."conversation/".$user_penerima['rows']['username']));
		} else {
		    if(file_exists($_FILES['file']['tmp_name'])){
                if($fileSize > 2097152 ){
    	           $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'maksimal file 2MB');
    	            exit(header("Location: ".$config['web']['base_url']."conversation/".$user_penerima['rows']['username']));
    	        } elseif(in_array($fileExtension, $allowedfileExtensions) === false){
        		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Extensi File Tidak diizinkan');
        		    exit(header("Location: ".$config['web']['base_url']."conversation/".$user_penerima['rows']['username']));
        		} else { 
        		       $uploadFileDir = "../files-conversation/".$md5."/"; 
                      $dest_path = $uploadFileDir . $newFileName;
                      move_uploaded_file($fileTmpPath, $dest_path);
    		     
                   
                        $check_chatAntara = mysqli_query($db, "SELECT * FROM reply_message WHERE chat_antara = '$chatAntara'");
            	            $check_lagi = mysqli_num_rows($check_chatAntara);
            	            if($check_lagi == 0){
            	                $input_post_message = array(
            	                'chat_antara' => $chatAntara,
                		        'pengirim' => $login['id'],
                		        'penerima' => $id_penerima_pesan,
                		        'dibaca_penerima' => 'no',
                		        'date' => date('Y-m-d H:i:s'),
                		        'message' => $validation['message']
                		        );
                		        $insert_message = $model->db_insert($db, "message", $input_post_message);
                		        if($insert_message == true){
                		            $input_post_reply_message = array(
                		            'id_message' => $insert_message,
                		            'pengirim' => $login['id'],
                    		        'penerima' => $id_penerima_pesan,
                    		        'chat_antara' => $chatAntara,
                    		        'date' => date('Y-m-d H:i:s'),
                    		        'message' => $validation['message'],
                    		        'attachment' => $newFileName
                    		        );
                    		      $insert_reply_message = $model->db_insert($db, "reply_message", $input_post_reply_message);
                    		      if($insert_reply_message == true){
                    		          
                    		          // kirim email
		                            $pengirim_pesan = $model->db_query($db, "*", "user", "id = '".$login['id']."'");
                                     $email_message = $model->db_query($db, "*", "email", "id = '5'");
                                    $penerima_email = $model->db_query($db, "*", "user", "id = '$id_penerima_pesan' ");			    
                                    $ke = decrypt($penerima_email['rows']['email']);
                                    $nama = $penerima_email['rows']['nama'];
                                    $format = $email_message['rows']['email'];
                                    $pisah = explode("{{username}}", $format);
                                    $isi_pesan = $pisah[0].$pengirim_pesan['rows']['username'].$pisah[1];
                                    
                                    $subject = 'Anda Menerima Pesan Baru';
                                    kirim_email($ke, $nama, $isi_pesan, $subject);
                                    
                                    
//                                     if($penerima_email['rows']['terima_wa_pesan'] == '1'){
//                                       $pesan_wa = 'Hallo '.$penerima_email['rows']['username'].'

// Kamu baru saja mendapatkan pesan dari '.$pengirim_pesan['rows']['username'].' 
// Silahkan login pada gubukdigital.net

// Ini adalah pesan otomatis
// Jika tidak ingin menerima pemberitahuan melalui Whatsapp, silahkan ketik: 
// *matikan notifikasi pesan*

// Atau kamu bisa atur melalui pengaturan akunmu

// Regards
// GubukDigital.Net';
//                                     $no_hp = decrypt($penerima_email['rows']['no_hp']);  
//                                     kirim_wa_pesan($no_hp, $pesan_wa);
//                                     }
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
                                    $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Pesan Anda Telah Berhasil Terkirim.');
        			                  exit(header("Location: ".$config['web']['base_url']."conversation/".$user_penerima['rows']['username']));
                    		      } else {
                    		          $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Gagal Kirim Pesan (5).');
        			                 exit(header("Location: ".$config['web']['base_url']."conversation/".$user_penerima['rows']['username']));
                    		      }
                		            
                		        } else {
                		            echo "gagal request";
                		        }
                        	       } else {
                        	                $check_chatAntara = mysqli_query($db, "SELECT * FROM reply_message WHERE chat_antara = '$chatAntara'");
                        	                $detail_reply_message= mysqli_fetch_assoc($check_chatAntara);
                        	                $query="UPDATE message SET chat_antara='$chatAntara',pengirim='".$login['id']."',penerima='$id_penerima_pesan',dibaca_penerima='no',date='$date',message='".$validation['message']."' where id='".$detail_reply_message['id_message']."'";
                        	                $update2 = mysqli_query($db, $query);
                        	                        if($update2 == true){
                        	                            $input_post_reply_message2 = array(
                                    		            'id_message' => $detail_reply_message['id_message'],
                                    		            'pengirim' => $login['id'],
                                        		        'penerima' => $id_penerima_pesan,
                                        		        'chat_antara' => $chatAntara,
                                        		        'date' => date('Y-m-d H:i:s'),
                                        		        'message' => $validation['message'],
                                        		        'attachment' => $newFileName
                                        		        );
                                        		      $insert_reply_message2 = $model->db_insert($db, "reply_message", $input_post_reply_message2);
                                        		      if($insert_reply_message2 == true){
                                        		          
                                        		          // kirim email
                        		                            $pengirim_pesan = $model->db_query($db, "*", "user", "id = '".$login['id']."'");
                                                             $email_message = $model->db_query($db, "*", "email", "id = '5'");
                                                            $penerima_email = $model->db_query($db, "*", "user", "id = '$id_penerima_pesan' ");			    
                                                            $ke = decrypt($penerima_email['rows']['email']);
                                                            $nama = $penerima_email['rows']['nama'];
                                                            $format = $email_message['rows']['email'];
                                                            $pisah = explode("{{username}}", $format);
                                                            $isi_pesan = $pisah[0].$pengirim_pesan['rows']['username'].$pisah[1];
                                                            
                                                            $subject = 'Anda Menerima Pesan Baru';
                                                            kirim_email($ke, $nama, $isi_pesan, $subject);
//                                                             if($penerima_email['rows']['terima_wa_pesan'] == '1'){
//                                       $pesan_wa = 'Hallo '.$penerima_email['rows']['username'].'

// Kamu baru saja mendapatkan pesan dari '.$pengirim_pesan['rows']['username'].' 
// Silahkan login pada gubukdigital.net

// Ini adalah pesan otomatis
// Jika tidak ingin menerima pemberitahuan melalui Whatsapp, silahkan ketik:
// *matikan notifikasi pesan*

// Atau kamu bisa atur melalui pengaturan akunmu

// Regards
// GubukDigital.Net';
//                                     $no_hp = decrypt($penerima_email['rows']['no_hp']);  
//                                     kirim_wa_pesan($no_hp, $pesan_wa);
//                                     }
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
                                        		          $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Pesan Anda Telah Berhasil Terkirim.');
                            			                  exit(header("Location: ".$config['web']['base_url']."conversation/".$user_penerima['rows']['username']));
                                        		      } else {
                                        		          $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Gagal Kirim Pesan (2).');
                            			                 exit(header("Location: ".$config['web']['base_url']."conversation/".$user_penerima['rows']['username']));
                                        		      }
                        	                        } else {
                        	                            echo "gagal update";
                        	                        }
                            	                    
                        	                
                        	            }
        		    
        		}
                
            } else {
                            $check_chatAntara = mysqli_query($db, "SELECT * FROM reply_message WHERE chat_antara = '$chatAntara'");
            	            $check_lagi = mysqli_num_rows($check_chatAntara);
            	            if($check_lagi == 0){
            	                $input_post_message = array(
            	                'chat_antara' => $chatAntara,
                		        'pengirim' => $login['id'],
                		        'penerima' => $id_penerima_pesan,
                		        'dibaca_penerima' => 'no',
                		        'date' => date('Y-m-d H:i:s'),
                		        'message' => $validation['message']
                		        );
                		        $insert_message = $model->db_insert($db, "message", $input_post_message);
                		        if($insert_message == true){
                		            $input_post_reply_message = array(
                		            'id_message' => $insert_message,
                		            'pengirim' => $login['id'],
                    		        'penerima' => $id_penerima_pesan,
                    		        'chat_antara' => $chatAntara,
                    		        'date' => date('Y-m-d H:i:s'),
                    		        'message' => $validation['message']
                    		        );
                    		      $insert_reply_message = $model->db_insert($db, "reply_message", $input_post_reply_message);
                    		      if($insert_reply_message == true){
                    		          
                    		          // kirim email
		                            $pengirim_pesan = $model->db_query($db, "*", "user", "id = '".$login['id']."'");
                                     $email_message = $model->db_query($db, "*", "email", "id = '5'");
                                    $penerima_email = $model->db_query($db, "*", "user", "id = '$id_penerima_pesan' ");			    
                                    $ke = decrypt($penerima_email['rows']['email']);
                                    $nama = $penerima_email['rows']['nama'];
                                    $format = $email_message['rows']['email'];
                                    $pisah = explode("{{username}}", $format);
                                    $isi_pesan = $pisah[0].$pengirim_pesan['rows']['username'].$pisah[1];
                                    
                                    $subject = 'Anda Menerima Pesan Baru';
                                    kirim_email($ke, $nama, $isi_pesan, $subject);
//                                     if($penerima_email['rows']['terima_wa_pesan'] == '1'){
//                                       $pesan_wa = 'Hallo '.$penerima_email['rows']['username'].'

// Kamu baru saja mendapatkan pesan dari '.$pengirim_pesan['rows']['username'].' 
// Silahkan login pada gubukdigital.net

// Ini adalah pesan otomatis
// Jika tidak ingin menerima pemberitahuan melalui Whatsapp, silahkan ketik: 
// *matikan notifikasi pesan*

// Atau kamu bisa atur melalui pengaturan akunmu

// Regards
// GubukDigital.Net';
//                                     $no_hp = decrypt($penerima_email['rows']['no_hp']);  
//                                     kirim_wa_pesan($no_hp, $pesan_wa);
//                                     }
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
                    		          $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Pesan Anda Telah Berhasil Terkirim.');
        			                  exit(header("Location: ".$config['web']['base_url']."conversation/".$user_penerima['rows']['username']));
                    		      } else {
                    		          $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Gagal Kirim Pesan (5).');
        			                 exit(header("Location: ".$config['web']['base_url']."conversation/".$user_penerima['rows']['username']));
                    		      }
                		            
                		        } else {
                		            echo "gagal request";
                		        }
                	       } else {
                	                $check_chatAntara = mysqli_query($db, "SELECT * FROM reply_message WHERE chat_antara = '$chatAntara'");
                	                $detail_reply_message= mysqli_fetch_assoc($check_chatAntara);
                	                $query="UPDATE message SET chat_antara='$chatAntara',pengirim='".$login['id']."',penerima='$id_penerima_pesan',dibaca_penerima='no',date='$date',message='".$validation['message']."' where id='".$detail_reply_message['id_message']."'";
                	                $update2 = mysqli_query($db, $query);
                	                        if($update2 == true){
                	                            $input_post_reply_message2 = array(
                            		            'id_message' => $detail_reply_message['id_message'],
                            		            'pengirim' => $login['id'],
                                		        'penerima' => $id_penerima_pesan,
                                		        'chat_antara' => $chatAntara,
                                		        'date' => date('Y-m-d H:i:s'),
                                		        'message' => $validation['message']
                                		        );
                                		      $insert_reply_message2 = $model->db_insert($db, "reply_message", $input_post_reply_message2);
                                		      if($insert_reply_message2 == true){
                                		          // kirim email
                		                            $pengirim_pesan = $model->db_query($db, "*", "user", "id = '".$login['id']."'");
                                                     $email_message = $model->db_query($db, "*", "email", "id = '5'");
                                                    $penerima_email = $model->db_query($db, "*", "user", "id = '$id_penerima_pesan' ");			    
                                                    $ke = decrypt($penerima_email['rows']['email']);
                                                    $nama = $penerima_email['rows']['nama'];
                                                    $format = $email_message['rows']['email'];
                                                    $pisah = explode("{{username}}", $format);
                                                    $isi_pesan = $pisah[0].$pengirim_pesan['rows']['username'].$pisah[1];
                                                    $subject = 'Anda Menerima Pesan Baru';
                                                    $no_hp = decrypt($penerima_email['rows']['no_hp']);
                                                    kirim_email($ke, $nama, $isi_pesan, $subject);
//                                                     if($penerima_email['rows']['terima_wa_pesan'] == '1'){
//                                       $pesan_wa = 'Hallo '.$penerima_email['rows']['username'].'

// Kamu baru saja mendapatkan pesan dari '.$pengirim_pesan['rows']['username'].' 
// Silahkan login pada gubukdigital.net

// Ini adalah pesan otomatis
// Jika tidak ingin menerima pemberitahuan melalui Whatsapp, silahkan ketik: 
// *matikan notifikasi pesan*

// Atau kamu bisa atur melalui pengaturan akunmu

// Regards
// GubukDigital.Net';
//                                     $no_hp = decrypt($penerima_email['rows']['no_hp']);  
//                                     kirim_wa_pesan($no_hp, $pesan_wa);
//                                     }    
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
                                        
                        		          $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Pesan Anda Telah Berhasil Terkirim.');
            			                  exit(header("Location: ".$config['web']['base_url']."conversation/".$user_penerima['rows']['username']));
                                		      } else {
                                		          $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Gagal Kirim Pesan (2).');
                    			                 exit(header("Location: ".$config['web']['base_url']."conversation/".$user_penerima['rows']['username']));
                                		      }
                	                        } else {
                	                            echo "gagal update";
                	                        }
                    	                    
                	                
                	            }
                    }
		    
		}
	    
	}
    
    
}