<?php

require '../web.php';


    if (!isset($_SESSION['login'])) {
		exit("No direct script access allowed!1");
	}
	if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
		exit("No direct script access allowed!2");
	}
	if (!isset($_GET['id']) OR !isset($_GET['status'])) {
		exit("No direct script access allowed!3");
	}
	if (in_array($_GET['status'], array('active','not-active')) == false) {
		exit("No direct script access allowed!4");
	}
	$data_target = $model->db_query($db, "*", "services", "id = '".mysqli_real_escape_string($db, $_GET['id'])."'");
    if ($data_target['count'] == 0) {
        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
		exit(header("Location: ".$config['web']['base_url']."my-product/"));
	} else {
		if ($model->db_update($db, "services", array('status' => $_GET['status']), "id = '".$_GET['id']."'") == true) {
			if ($_GET['status'] == 'active') {
			    $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Status berhasil diubah.');
			    exit(header("Location: ".$config['web']['base_url']."my-product/"));
			} else {
				$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Status berhasil diubah.');
				exit(header("Location: ".$config['web']['base_url']."my-product/"));
			}
		} else {
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Status gagal diubah.');
			exit(header("Location: ".$config['web']['base_url']."my-product/"));
		}
	}
	require '../lib/result.php';
    