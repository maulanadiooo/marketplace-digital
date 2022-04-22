<?php

if (isset($_SESSION['login'])) {
	$login = $model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'");
	if ($login['count'] <> 1) {
		exit(header("Location: ".$config['web']['base_url']."signout/"));
	} elseif($login['rows']['status'] == "Banned"){
	    exit(header("Location: ".$config['web']['base_url']."banned"));
	}elseif ($login['rows']['status'] <> "Verified") {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Akun Anda belum terverifikasi');
		exit(header("Location: ".$config['web']['base_url']."signout/"));
	}
	$login = $login['rows'];
}