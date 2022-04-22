<?php
require '../../web.php';
require '../../lib/check_session_admin.php';
require '../../lib/result.php';
if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']));
}


    if (!isset($_SESSION['login'])) {
		exit("No direct script access allowed!1");
	}
	if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."' AND role = '2' ")['count'] == 0) {
		exit("No direct script access allowed!2");
	}
	if (!isset($_GET['approve'])) {
		exit("No direct script access allowed!3");
	}
	$data_target = $model->db_query($db, "*", "services", "id = '".mysqli_real_escape_string($db, $_GET['approve'])."' ");
    if ($data_target['count'] == 0) {
        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
		exit(header("Location: ".$config['web']['base_url']."administrator/services-pending/"));
	} else {
		if(isset($_GET['approve'])){
		   $model->db_update($db, "services", array('status' => 'active'), "id = '".mysqli_real_escape_string($db, $_GET['approve'])."'");
		   $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Produk '.$data_target['rows']['nama_layanan'].' Berhasil Disetujui!');
		   exit(header("Location: ".$config['web']['base_url']."administrator/services-pending/"));
		} else {
		    echo "Tidak ada Aksi";
		}
	}
	require '../../lib/result.php';
    