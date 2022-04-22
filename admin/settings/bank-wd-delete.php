<?php
require '../../web.php';
require '../../lib/check_session_admin.php';
require '../../lib/is_login.php';

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."sigin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."' AND role ='2' ")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']));
}

if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."administrator/bank-withdraws/"));
} else {
    $data = array('delid');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."administrator/bank-withdraws/"));
	} else {
	    $validation = array(
			'delid' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['delid'])))),
		);
		if(check_empty($validation) == true){
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
		    exit(header("Location: ".$config['web']['base_url']."administrator/bank-withdraws/"));
		} else {
		    $update = $model->db_delete($db, "bank_penarikan", "id = '".$validation['delid']."'");
		    if($update == true){
		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Bank Admin Berhasil Dihapus.');
		        exit(header("Location: ".$config['web']['base_url']."administrator/bank-withdraws/"));
		    } else {
		        echo "Gagal Update";
		    }
		    
		}
	}
}