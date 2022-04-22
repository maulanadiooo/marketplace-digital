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
    exit(header("Location: ".$config['web']['base_url']."administrator/services-pending/"));
} else {
    $data = array('alasan_revisi');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."administrator/services-pending/"));
	} else {
	    $validation = array(
			'alasan_revisi' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['alasan_revisi'])))),
			'suid' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['suid'])))),
		);
		if(check_empty($validation) == true){
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
		    exit(header("Location: ".$config['web']['base_url']."administrator/services-pending/"));
		} else {
		    $update = $model->db_update($db, "services", array('status' => 'revisi', 'ket_revisi' => $validation['alasan_revisi']), "id = '".$validation['suid']."'");
		    if($update == true){
		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Produk Telah Diminta untuk revisi.');
		        exit(header("Location: ".$config['web']['base_url']."administrator/services-pending/"));
		    } else {
		        echo "Gagal Update";
		    }
		    
		}
	}
}