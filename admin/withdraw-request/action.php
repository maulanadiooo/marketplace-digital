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
	$data_target = $model->db_query($db, "*", "withdraw_request", "id = '".mysqli_real_escape_string($db, $_GET['approve'])."' ");
	$user = $model->db_query($db, "*", "user", "id = '".$data_target['rows']['user_id']."' ");
    if ($data_target['count'] == 0) {
        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
		exit(header("Location: ".$config['web']['base_url']."administrator/withdraw-request/"));
	} else {
		if(isset($_GET['approve'])){
		   $model->db_update($db, "withdraw_request", array('status' => 'success'), "id = '".mysqli_real_escape_string($db, $_GET['approve'])."'");
		   $model->db_update($db, "user", array('withdraw' => $user['rows']['withdraw']+$data_target['rows']['amount']), "id = '".$data_target['rows']['user_id']."'");
		   $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Proses Withdraw Berhasil!');
		   exit(header("Location: ".$config['web']['base_url']."administrator/withdraw-request/"));
		} else {
		    echo "Tidak ada Aksi";
		}
	}
	require '../../lib/result.php';
    