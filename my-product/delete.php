<?
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';
if (!isset($_SESSION['login'])) {
	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Otentikasi dibutuhkan!', 'msg' => 'Silahkan masuk ke akun Anda.');
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}

    if (!isset($_SESSION['login'])) {
		exit("No direct script access allowed!1");
	}
	if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
		exit("No direct script access allowed!2");
	}
	if (!isset($_GET['id']) OR !isset($_GET['delete'])) {
		exit("No direct script access allowed!3");
	}
	if (in_array($_GET['delete'], array('0','1')) == false) {
		exit("No direct script access allowed!4");
	}
	$data_target = $model->db_query($db, "*", "services", "id = '".mysqli_real_escape_string($db, $_GET['id'])."'");
    if ($data_target['count'] == 0) {
        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
		exit(header("Location: ".$config['web']['base_url']."my-product/"));
	} else {
		if ($model->db_update($db, "services", array('deleted' => $_GET['delete']), "id = '".$_GET['id']."'") == true) {
			if ($_GET['delete'] == '1') {
			    $now = date("Y-m-d H:i:s");
			    $expire = date('Y-m-d H:i:s',strtotime('+24 Hour',strtotime($now)));
			    $model->db_update($db, "services", array('status' => 'delete'), "id = '".$_GET['id']."'");
			    $model->db_update($db, "services", array('deleted_time' => $expire), "id = '".$_GET['id']."'");
			    $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Akan Terdelete Otomatis Setelah 24 Jam');
			    exit(header("Location: ".$config['web']['base_url']."my-product/"));
			} else {
			    $model->db_update($db, "services", array('status' => 'pending'), "id = '".$_GET['id']."'");
				$_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Berhasil Undo Delete, Produk Akan Direview Kembali.');
				exit(header("Location: ".$config['web']['base_url']."my-product/"));
			}
		} else {
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Status gagal diubah.');
			exit(header("Location: ".$config['web']['base_url']."my-product/"));
		}
	}
	require '../lib/result.php';
    