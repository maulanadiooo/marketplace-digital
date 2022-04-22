<?php
require '../../web.php';
require '../../lib/check_session_admin.php';
$website = $model->db_query($db, "*", "website", "id = '1'");

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."administrator/auth/signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."' AND role = '2'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."administrator/auth/signin/"));
}


if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."administrator/smtp/"));
} else {
    $data = array('host', 'username', 'password','reply_to');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."administrator/smtp/"));
	}else {
	    $validation = array(
			'host' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['host'])))),
			'username' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['username'])))),
			'password' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['password'])))),
			'reply_to' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['reply_to'])))),
			'nama' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['nama'])))),
			'port' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['port'])))),
		);
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
			exit(header("Location: ".$config['web']['base_url']."administrator/smtp/"));
		} else {
                
    		    $input_post = array(
		        'host' => encrypt($validation['host']),
		        'port' => $validation['port'],
		        'username' => encrypt($validation['username']),
		        'password' => encrypt($validation['password']),
		        'reply_to' => encrypt($validation['reply_to']),
		        'name' => $validation['nama'],
		        
		        
		        );
		        $update = $model->db_update($db, "smtp", $input_post, "id = '1'");
		        if ($update == true) {
    		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'SMTP Terupdate.');
    			    exit(header("Location: ".$config['web']['base_url']."administrator/smtp/"));
    		    } else {
    		        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Ada Kesalahan Data Tidak Terupdate.');
    			    exit(header("Location: ".$config['web']['base_url']."administrator/smtp/"));
    		    }
    		    
		    
		    
		}
	}
  }  