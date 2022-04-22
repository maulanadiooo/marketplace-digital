<?php
require '../../web.php';
require '../../lib/check_session_admin.php';
require '../../lib/result.php';
require '../../lib/csrf_token.php';
require '../../lib/class/class.phpmailer.php';
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
if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."admin/deposit/"));
}else{
    $data = array('did');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
		exit(header("Location: ".$config['web']['base_url']."administrator/deposit-history/"));
	} else {
	$validation = array(
			'did' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['did'])))),
		);    
	 
	$data_target = $model->db_query($db, "*", "deposit", "id = '".$validation['did']."' AND status ='pending' ");
    if ($data_target['count'] == 0) {
        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
		exit(header("Location: ".$config['web']['base_url']."administrator/deposit-history/"));
	} else {
        $now_tgl = date('Y-m-d H:i:s');
     
     $input_post_orders_active = array(
        'status' => 'success',
        );
    $update_deposit = $model->db_update($db, "deposit", $input_post_orders_active, "id = '".$validation['did']."' ");
    $user = $model->db_query($db, "*", "user", "id = '".$data_target['rows']['user_id']."'");
    $amount = $data_target['rows']['amount'];
    $saldo_user = $user['rows']['saldo_tersedia'];
    $input_post_user= array(
        'saldo_tersedia' => $saldo_user + $amount,
        );
    $update_user = $model->db_update($db, "user", $input_post_user, "id = '".$data_target['rows']['user_id']."' "); 
    
    if($update_deposit == true && $update_user == true){
        $now = date("Y-m-d H:i:s");
        $update_history_pembayaran = array(
        'user_id' => $data_target['rows']['user_id'],
        'amount' => $amount,
        'message' => 'Deposit Dengan ID #'.$data_target['rows']['kode_depo'],
        'created_at' => $now
        );
        $model->db_insert($db, "history_pembayaran", $update_history_pembayaran);
        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Kode Deposit '.$data_target['rows']['kode_depo'].' Berhasil Disetujui!');
	   exit(header("Location: ".$config['web']['base_url']."administrator/deposit-history/"));
        
        
    }else {
        echo "Something wrong!";
    }
		
	}
	}
}   