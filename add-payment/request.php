<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/csrf_token.php';

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."sigin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}

if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."add-payment/"));
}else {
    $data = array('bank', 'nama_pemilik', 'no_rek');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."add-payment/"));
	} else {
	    
	    $user_info = $model->db_query($db, "*", "user", "id = '".$login['id']."'");
	    
	    $validation = array(
			'bank' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['bank'])))),
			'nama_pemilik' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['nama_pemilik'])))),
			'no_rek' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['no_rek'])))),
		);
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
			exit(header("Location: ".$config['web']['base_url']."add-payment/"));
		} else {
		    
    		    
    		    $estimasi_wd_1 = format_date(substr($estimasi_wd, 0, -9));
    		    $input_post = array(
		        'id_bank' => $validation['bank'],
		        'nama_pemilik_bank' => $validation['nama_pemilik'],
		        'no_rek' => encrypt($validation['no_rek'])
		        );
		        $update= $model->db_update($db, "user", $input_post, "id = '".$login['id']."' ");
		        if ($update == true) {
		            
                $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Metode Penarikan Telah Diperbarui');
		        exit(header("Location: ".$config['web']['base_url']."add-payment/"));
		            
		        } else {
		            $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Gagal Menambahkan Metode Penarikan, Hubungi Admin Website!');
    			    exit(header("Location: ".$config['web']['base_url']."add-payment/"));
		        }
		    
		    
		}
	    
	}
    
    
}