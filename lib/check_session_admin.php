<?php

if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."administrator/auth/signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."' AND role = '2'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."administrator/auth/signin/"));
}