<?php
require '../web.php';
require '../lib/csrf_token.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
if(!isset($_POST)){
    exit("Permintaan tidak diterima.");
} else {
    $data = array('email', 'password');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
		exit(header("Location: ".$config['web']['base_url']."signin/"));
	} else {
	    
	    $captcha = $_POST['g-recaptcha-response'];
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$website['rows']['secret_key']."&response=".$captcha."");
		$response = json_decode($response);
	    $input_post = array(
			'email' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['email'])))),
			'password' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['password'])))),
		);
		$redirect = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['redirect']))));
		$check_redirect= $model->db_query($db, "*", "redirect", "token = '$redirect'");
		$go = $check_redirect['rows']['go'];
		$mail_l = strtolower($input_post['email']);
		$email = encrypt($mail_l);
		if($website['rows']['secret_key'] != null){
    		if ($response->success == false) {
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Silahkan Klik Google Recaptcha');
    		    exit(header("Location: ".$config['web']['base_url']."signin/"));
    		} 
		}
		
		if (check_empty($input_post) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
			exit(header("Location: ".$config['web']['base_url']."signin/"));
		} else {
		    $check_user = $model->db_query($db, "*", "user", "email = '$email'");
		    if ($check_user['count'] == 1) {
		        if ($check_user['rows']['status'] == 'Not Verified'){
    			    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Email anda belum terverifikasi, Cek Spam Mail Jika tidak menemukannya pada inbox. Contact email: suppport@gubukdigital.net jika terjadi masalah');
    			    exit(header("Location: ".$config['web']['base_url']."signin/"));
				} else {
				    if (password_verify($input_post['password'], $check_user['rows']['password']) == true) {
				        $model->db_update($db, "user", array('last_login' => date('Y-m-d H:i:s'), 'ip' => get_client_ip()), "id ='".$check_user['rows']['id']."' ");
    					$model->db_insert($db, "login_logs", array('user_id' => $check_user['rows']['id'], 'ip_address' => get_client_ip(), 'created_at' => date('Y-m-d H:i:s')));
    					
                        $_SESSION['login'] = $check_user['rows']['id'];
    					if(isset($_POST['rememberme'])) {
                           
                            // setcookie('username', $input_post['username'], time() + (60 * 60 * 24 * 30), '/');
                            $token_login = str_rand(50);
                            setcookie('token_login', $token_login, time() + (60 * 60 * 24 * 30), '/');
                            
                            $hash_login = password_hash($token_login, PASSWORD_DEFAULT);
                            $model->db_update($db, "user", array('token_login' => $token_login), "email = '$email'");
                            
                         }
                        
                        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil masuk!', 'msg' => '');   
                        exit(header("Location: ".$config['web']['base_url'].$go)); 
                         
                        
                     
                            
    					
    				} else {
    					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Password yang anda masukkan salah.');
    					exit(header("Location: ".$config['web']['base_url']."signin/"));
    				}
				    
				}
		        
		        
		    } else {
		        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'user Tidak Ditemukan.');
    					exit(header("Location: ".$config['web']['base_url']."signin/"));
		    }
		    
		}
		
		
	}
    
    
}