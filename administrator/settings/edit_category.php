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
    exit(header("Location: ".$config['web']['base_url']."administrator/category/"));
} else {
    $data = array('category', 'ket_category');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."administrator/category/"));
	} else {
	    $validation = array(
			'category' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['category'])))),
			'ket_category' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['ket_category'])))),
			'cuid' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['cuid'])))),
		);
		if(check_empty($validation) == true){
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
		    exit(header("Location: ".$config['web']['base_url']."administrator/category/"));
		} else {
		    $result = preg_replace("/[^a-zA-Z 0-9]/", "", $validation['category']);
    		$url_nama_produk = strtr($result, " ", "-" );
		    $update = $model->db_update($db, "categories", array('category' => $validation['category'], 'ket' => $validation['ket_category'], 'url' => $url_nama_produk), "id = '".$validation['cuid']."'");
		    if($update == true){
		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Kategori Berhasil di Edit.');
		        exit(header("Location: ".$config['web']['base_url']."administrator/category/"));
		    } else {
		        echo "Gagal Update";
		    }
		    
		}
	}
}