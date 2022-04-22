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
 
$now = date('Y-m-d H:i:s');
if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."administrator/services/"));
} else {
    $data = array('status', 'featured', 'premium', 'cuid', 'tangguh');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."administrator/services/"));
	} else {
	    $validation = array(
			'status' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['status'])))),
			'featured' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['featured'])))),
			'durasi_featured' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['durasi_featured'])))),
			'premium' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['premium'])))),
			'durasi_premium' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['durasi_premium'])))),
			'cuid' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['cuid'])))),
			'tangguh' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['tangguh'])))),
			
		);
		$services = $model->db_query($db, "*", "services", "featured = '1'");
		$services_premi = $model->db_query($db, "*", "services", "premium = '1'");
		if($validation['featured'] == '1' && $services['count'] >= 3){
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Fitur Featured Penuh Max 3.');
		    exit(header("Location: ".$config['web']['base_url']."administrator/services/"));
		}elseif($validation['premium'] == '1' && $services_premi['count'] >= 3){
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Fitur Premium Penuh Max 3.');
		    exit(header("Location: ".$config['web']['base_url']."administrator/services/"));
		} 
// 		elseif($validation['featured'] == '1' && $validation['premium'] == '1'){
// 		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Hanya Bisa Memilih Salah 1 Fitur.');
// 		    exit(header("Location: ".$config['web']['base_url']."administrator/services/"));
// 		} 
		elseif($validation['status'] == 'revisi' && !$validation['tangguh']){
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Silahkan Isi Alasan Penangguhan.');
		    exit(header("Location: ".$config['web']['base_url']."administrator/services/"));
		}else {
		    $update = $model->db_update($db, "services", array('status' => $validation['status'], 'featured' => $validation['featured'], 'premium' => $validation['premium'], 'ket_revisi' => $validation['tangguh']), "id = '".$validation['cuid']."'");
		    if($validation['featured'] == '1'){
		    $update = $model->db_update($db, "services", array('expired_featured' => date('Y-m-d H:i:s',strtotime('+'.$validation['durasi_featured'].' Day',strtotime($now))) ), "id = '".$validation['cuid']."'");    
		    }
		    if($validation['premium'] == '1'){
		    $update = $model->db_update($db, "services", array('expired_premium' => date('Y-m-d H:i:s',strtotime('+'.$validation['durasi_premium'].' Day',strtotime($now))) ), "id = '".$validation['cuid']."'");    
		    }
		    if($update == true){
		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Produk Berhasil Diubah.');
		        exit(header("Location: ".$config['web']['base_url']."administrator/services/"));
		    } else {
		        echo "Gagal Update";
		    }
		    
		}
	}
}