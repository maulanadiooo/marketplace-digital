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
    exit(header("Location: ".$config['web']['base_url']."administrator/withdraw-request"));
} else {
    $data = array('alasan_gagal');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."administrator/withdraw-request/"));
	} else {
	    $validation = array(
			'alasan_gagal' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['alasan_gagal'])))),
			'wuid' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['wuid'])))),
		);
		if(check_empty($validation) == true){
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
		    exit(header("Location: ".$config['web']['base_url']."administrator/withdraw-request/"));
		} else {
		    $wd_request_info = $model->db_query($db, "*", "withdraw_request", "id = '".$validation['wuid']."'");
		    $user = $model->db_query($db, "*", "user", "id = '".$wd_request_info['rows']['user_id']."'");
		    $update_wdreq = $model->db_update($db, "withdraw_request", array('status' => 'error', 'ket_error' => $validation['alasan_gagal']), "id = '".$validation['wuid']."'");
		    $update_balance = $model->db_update($db, "user", array('saldo_tersedia' => $user['rows']['saldo_tersedia']+$wd_request_info['rows']['amount']), "id = '".$wd_request_info['rows']['user_id']."'");
		    if($update_wdreq == true && $update_balance == true){
		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Withdraw Telah Dirubah Menjadi Gagal Dengan Alasan '.$validation['alasan_gagal']);
		        exit(header("Location: ".$config['web']['base_url']."administrator/withdraw-request/"));
		    } else {
		        echo "Gagal Update";
		    }
		    
		}
	}
}