<?php
require '../../web.php';
require '../../lib/check_session_admin.php';
require '../../lib/is_login.php';

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."administrator/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."' AND role ='2' ")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']));
}

if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."administrator/mail_template/"));
} else {
    $data = array('verif_email', 'reset_password_mail', 'order_mail','pembaruan_orderan', 'pesan_masuk', 'validasi_pembayaran', 'invoice');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
		exit(header("Location: ".$config['web']['base_url']."administrator/mail_template/"));
	}else {
	    $validation = array(
			'verif_email' => $_POST['verif_email'],
			'reset_password_mail' => $_POST['reset_password_mail'],
			'order_mail' => $_POST['order_mail'],
			'pembaruan_orderan' => $_POST['pembaruan_orderan'],
			'pesan_masuk' => $_POST['pesan_masuk'],
			'validasi_pembayaran' => $_POST['validasi_pembayaran'],
			'invoice' => $_POST['invoice'],
		);
		
		   if(check_empty($validation) == true){
		      $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
		        exit(header("Location: ".$config['web']['base_url']."administrator/mail_template/")); 
		   } else {
    		    $input_post_verif_email = array(
    		        
    		        'email' => $validation['verif_email'],
    		        );
		        $update_verif = $model->db_update($db, "email", $input_post_verif_email, "id= '1'");
		        $input_post_reset_password_mail = array(
    		        
    		        'email' => $validation['reset_password_mail'],
    		        );
		        $update_reset_password_mail = $model->db_update($db, "email", $input_post_reset_password_mail, "id= '2'");
		        $input_post_order_mail = array(
    		        
    		        'email' => $validation['order_mail'],
    		        );
		        $update_order_mail = $model->db_update($db, "email", $input_post_order_mail, "id= '3'");
		        $input_post_pembaruan_orderan = array(
    		        
    		        'email' => $validation['pembaruan_orderan'],
    		        );
		        $update_pembaruan_orderan = $model->db_update($db, "email", $input_post_pembaruan_orderan, "id= '4'");
		        $input_post_pesan_masuk = array(
    		        
    		        'email' => $validation['pesan_masuk'],
    		        );
		        $update_pesan_masuk = $model->db_update($db, "email", $input_post_pesan_masuk, "id= '5'");
		        $input_validasi_pembayaran = array(
    		        
    		        'email' => $validation['validasi_pembayaran'],
    		        );
		        $update_validasi_pembayaran = $model->db_update($db, "email", $input_validasi_pembayaran, "id= '6'");
		        $input_post_invoice = array(
    		        
    		        'email' => $validation['invoice'],
    		        );
		        $update_invoice = $model->db_update($db, "email", $input_post_invoice, "id= '7'");
		        
		        if($update_verif == true && $update_reset_password_mail == true && $update_order_mail == true && $update_pembaruan_orderan == true && $update_pesan_masuk == true && $update_validasi_pembayaran == true && $update_invoice == true){
		           $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Template Berubah.');
		            exit(header("Location: ".$config['web']['base_url']."administrator/mail_template/"));  
		        } else {
		            $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagl!', 'msg' => 'Gagal merubah template.');
		            exit(header("Location: ".$config['web']['base_url']."administrator/mail_template/"));
		        }
		        
		   }
	}
    
}