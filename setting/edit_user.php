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
    $data = array('password');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."setting/"));
	} else {
	    $nama = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['nama']))));
		$profesi = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['profesi']))));
		$author_bio = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['author_bio']))));
	    $input_post = array(
			'password' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['password'])))),
			'email' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['email']))))
		);
		$telegram = encrypt($db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['telegram'])))));
		$terima_pesan = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['terima_tele_pesan']))));
		$terima_orderan = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['terima_tele_orderan']))));
		if (check_empty($input_post) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Password Tidak Boleh Kosong.');
			exit(header("Location: ".$config['web']['base_url']."setting/"));
		}else {
		    $check_user = $model->db_query($db, "*", "user", "id = '".$data_target['rows']['id']."'");
		    if ($check_user['count'] == 1) {
		        if (password_verify($input_post['password'], $check_user['rows']['password']) == true) {
		            if(!$telegram && ($terima_pesan == 1 || $terima_orderan == 1)){
		                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Untuk mengatifkan fitur telegram, silahkan isi telegram ID.');
			            exit(header("Location: ".$config['web']['base_url']."setting/"));
		                
		            }
		            $update = $model->db_update($db, "user", array('nama' => $nama), "id = '".$check_user['rows']['id']."'");
		            $update = $model->db_update($db, "user", array('bio' => $author_bio), "id = '".$check_user['rows']['id']."'");
		            $update = $model->db_update($db, "user", array('profesi' => $profesi), "id = '".$check_user['rows']['id']."'");
		            $update = $model->db_update($db, "user", array('telegram_id' => $telegram), "id = '".$check_user['rows']['id']."'");
		            $update = $model->db_update($db, "user", array('terima_tele_pesan' => $terima_pesan), "id = '".$check_user['rows']['id']."'");
		            $update = $model->db_update($db, "user", array('terima_tele_orderan' => $terima_orderan), "id = '".$check_user['rows']['id']."'");
		            if($check_user['rows']['role'] == '2'){
		               $update = $model->db_update($db, "user", array('email' => encrypt($input_post['email'])), "id = '".$check_user['rows']['id']."'"); 
		                  
		            }
		            if($update == true){
		                $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Data Berhasil Dirubah.');
		                exit(header("Location: ".$config['web']['base_url']."setting/"));
		            }
		            
		        } else {
		                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'gagal!', 'msg' => 'Password Salah.');
		                exit(header("Location: ".$config['web']['base_url']."setting/"));
		        }
		        
		        
		    }
		    
		    
		    
		}
	    
	}
    
    
    
}