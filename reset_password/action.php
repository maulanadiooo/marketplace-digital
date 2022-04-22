<?php
require '../web.php';
require '../lib/csrf_token.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
if(!isset($_POST)){
    exit("Permintaan tidak diterima.");
} else {
    $input_data = array('email');
    if (check_input($_POST, $input_data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
		exit(header("Location: ".$config['web']['base_url']."signup/"));
	} else {
	    
	    $captcha = $_POST['g-recaptcha-response'];
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$website['rows']['secret_key']."&response=".$captcha."");
		$response = json_decode($response);
		$validation = array(
			'email' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['email'])))),
		);
		
		$check_user = $model->db_query($db, "*", "user", "email = '".encrypt($validation['email'])."'");
		$now = date("Y-m-d H:i:s");
		if($website['rows']['secret_key'] != null){
    		if ($response->success == false) {
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Silahkan Klik Google Recaptcha');
    		    exit(header("Location: ".$config['web']['base_url']."reset_password/"));
    		} 
		}
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
			exit(header("Location: ".$config['web']['base_url']."reset_password/"));
		} 
		
		elseif ($check_user['count'] == 0) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Email Tidak Terdaftar Pada Website Kami.');
			exit(header("Location: ".$config['web']['base_url']."reset_password/"));
		} else {
		    
		    $post_kode = str_rand(50);
		    $hash_code = password_hash($post_kode, PASSWORD_DEFAULT);
		    $expire = date('Y-m-d H:i:s',strtotime('+15 Minute',strtotime($now)));
		    $exp_reset = $check_user['rows']['exp_reset'];
			$permintaa_ulang = format_date(substr($exp_reset, 0, -9)).", ".substr($expire, -8);
			
			if (strtotime($now) > strtotime($exp_reset)) {
				$update = $model->db_update($db, "user",array('reset_link' => $hash_code, 'exp_reset' => $expire) ,"email = '".encrypt($validation['email'])."'");
				if($update == true){
				    include "../lib/class/class.phpmailer.php";
				    $ke = $validation['email'];
				    $nama = $check_user['rows']['nama'];
				    $email_reset = $model->db_query($db, "*", "email", "id = '2'");
				    
				    $format = $email_reset['rows']['email'];
                    $pisah = explode("{{reset_link}}", $format);
                    $reset_link = $pisah[0].$config['web']['base_url']."new_password/?token=".$post_kode."&mail=".$validation['email'].$pisah[1];
                    
                    $subject = "Reset Password";
				    kirim_email($ke, $nama, $reset_link, $subject);
                    $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Link Reset Password Telah Dikirim, Cek Inbox/Spam ^.^');
				    exit(header("Location: ".$config['web']['base_url']."reset_password/"));
                    
				}
			} else {
				$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Permintaan Ulang Di Izinkan Setelah '.$permintaa_ulang.' UTC+7.');
				exit(header("Location: ".$config['web']['base_url']."reset_password/"));
			}
		}
	}
    
}
?>