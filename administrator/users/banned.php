<?php
require '../../web.php';
require '../../lib/check_session_admin.php';
require '../../lib/is_login.php';

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."' AND role ='2' ")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']));
}

if(!isset($_POST)){
    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Tidak method post.');
    exit(header("Location: ".$config['web']['base_url']."administrator/users/"));
} else {
    $data = array('status', 'banned');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.'.$_POST['status'].$_POST['banned'].$_POST['userid']);
		exit(header("Location: ".$config['web']['base_url']."admin/users/"));
	} else {
	    $validation = array(
			'status' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['status'])))),
			'banned' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['banned'])))),
			'userid' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['userid'])))),
		);
		$check_user_banned = $model->db_query($db, "*", "user", "id = '".$validation['userid']."'");
		if($validation['status'] == 'Banned'){
		    $reason = $validation['banned'];
		} else {
		    $reason = $check_user_banned['rows']['reason'];
		}
		if($validation['status'] == 'Banned' && !$validation['banned']){
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Silahkan Isi Alasan Penangguhan.');
		    exit(header("Location: ".$config['web']['base_url']."administrator/users/"));
		}else {
		    if($validation['status'] == 'Banned'){
    		    $ip_banned = mysqli_query($db, "SELECT * FROM banned_ip WHERE ip = '".$check_user_banned['rows']['ip']."'");
            	$check_lagi = mysqli_num_rows($ip_banned);
            	if($check_lagi == 0){
            	  $model->db_insert($db, "banned_ip", array('ip' => $check_user_banned['rows']['ip']));  
            	}
		        
		    }
		    $update = $model->db_update($db, "user", array('status' => $validation['status'], 'reason' => $reason), "id = '".$validation['userid']."'");
		    if($update == true){
		        
		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Status User Berhasil Dirubah menjadi '.$validation['status']);
		        exit(header("Location: ".$config['web']['base_url']."administrator/users/"));
		    } else {
		        echo "Gagal Update";
		    }
		    
		}
	}
}