<?php
require '../web.php';
require '../lib/csrf_token.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
if(!isset($_POST)){
    exit("Permintaan tidak diterima.");
} else {
    $input_data = array('email', 'token', 'n_password', 'c_password');
    if (check_input($_POST, $input_data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
		exit(header("Location: ".$config['web']['base_url']."signup/"));
	} else {
	    
        $captcha = $_POST['g-recaptcha-response'];
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$website['rows']['secret_key']."&response=".$captcha."");
		$validation = array(
			'email' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['email'])))),
			'token' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['token'])))),
			'n_password' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['n_password'])))),
			'c_password' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['c_password'])))),
		);
		
		$check_user = $model->db_query($db, "*", "user", "email = '".$validation['email']."'");
		$now = date("Y-m-d H:i:s");
		if($website['rows']['secret_key'] != null){
    		if (!$captcha)  {
    			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Ceklis Captcha!');
    			exit(header("Location: ".$config['web']['base_url']."new_password/?token=".$validation['token']."&mail=".$validation['email']));
    		} 
    		if ($response.success != true) {
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'You Are Robot!');
    		    exit(header("Location: ".$config['web']['base_url']."new_password/?token=".$validation['token']."&mail=".$validation['email']));
    		}
		}
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
			exit(header("Location: ".$config['web']['base_url']."reset_password/"));
		} 
		 
		elseif ($check_user['count'] == 0) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Email Tidak Terdaftar Pada Website Kami.');
			exit(header("Location: ".$config['web']['base_url']."reset_password/"));
		} else if ($validation['n_password'] <> $validation['c_password']) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Konfirmasi Password Tidak Cocok.');
			exit(header("Location: ".$config['web']['base_url']."new_password/?token=".$validation['token']."&mail=".$validation['email']));
		} else {
		    $hash_password = password_hash($validation['n_password'], PASSWORD_DEFAULT);
		    $update_paswrod = $model->db_update($db, "user",array('password' => $hash_password, 'reset_link' => '', 'exp_reset' => $now) ,"email = '".$validation['email']."'");
		    if($update_paswrod == true){
		        $_SESSION['login'] = $check_user['rows']['id'];
		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Password Telah Diganti, Anda Login Dengan Password Baru ^.^');
		        exit(header("Location: ".$config['web']['base_url']));
		        
		    } else {
		        echo "something wrong, Code (1)";
		    }
		    
		}
	}
    
}
?>