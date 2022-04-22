<?php
require '../web.php';

    if (!isset($_SESSION['login'])) {
		exit("No direct script access allowed!1");
	}
	if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
		exit("No direct script access allowed!2");
	}
	if (!isset($_GET['id'])) {
		exit("No direct script access allowed!11");
	}
	$data_target = $model->db_query($db, "*", "user", "id = '".mysqli_real_escape_string($db, $_GET['id'])."'");
	
	if ($data_target['count'] == 0) {
	    exit("Data tidak ditemukan.");
	} 

if(!isset($_POST)){
    exit("Permintaan tidak diterima.");
} else {
    $data = array('old_password', 'new_password', 'con_password', );
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."setting/"));
	} else {
	    $input_post = array(
			'old_password' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['old_password'])))),
			'new_password' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['new_password'])))),
			'con_password' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['con_password'])))),
		);
		if (check_empty($input_post) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
			exit(header("Location: ".$config['web']['base_url']."setting/"));
		}else {
		    $check_user = $model->db_query($db, "*", "user", "id = '".$data_target['rows']['id']."'");
		    if ($check_user['count'] == 1) {
		        if (password_verify($input_post['old_password'], $check_user['rows']['password']) == true) {
		            if($input_post['new_password']<>$input_post['con_password']){
		               $_SESSION['result'] = array('alert' => 'danger', 'title' => 'gagal!', 'msg' => 'Password Baru Tidak Cocok.');
		                exit(header("Location: ".$config['web']['base_url']."setting/")); 
		            } else {
		                $update = $model->db_update($db, "user", array('password' => password_hash($input_post['new_password'], PASSWORD_DEFAULT)), "id = '".$check_user['rows']['id']."'");
    		            if($update == true){
    		                $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Password Berhasil Dirubah.');
    		                exit(header("Location: ".$config['web']['base_url']."setting/"));
    		            }
		                
		            }
		            
		            
		        } else {
		                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'gagal!', 'msg' => 'Password Lama Salah.');
		                exit(header("Location: ".$config['web']['base_url']."setting/"));
		        }
		        
		        
		    }
		    
		    
		    
		}
	    
	}
    
    
    
}