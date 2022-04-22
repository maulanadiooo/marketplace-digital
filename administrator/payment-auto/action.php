<?php
require '../../web.php';
require '../../lib/check_session_admin.php';
require '../../lib/is_login.php';

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."' AND role ='2' ")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']));
}

if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."administrator/payment-auto/"));
} else {
    $data = array('server_key_midtrans', 'va_ipaymu', 'apikey_ipaymu','user_bca', 'password_bca');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong1.');
		exit(header("Location: ".$config['web']['base_url']."administrator/payment-auto/"));
	}else {
	    $validation = array(
			'server_key_midtrans' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['server_key_midtrans'])))),
			'va_ipaymu' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['va_ipaymu'])))),
			'apikey_ipaymu' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['apikey_ipaymu'])))),
			'user_bca' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['user_bca'])))),
			'password_bca' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['password_bca'])))),
			'public_key_cp' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['public_key_cp'])))),
			'private_key_cp' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['private_key_cp'])))),
			'merchant_id' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['merchant_id'])))),
			'ipn_secret' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['ipn_secret'])))),
			'debug_email' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['debug_email'])))),
		);
		
		   if(check_empty($validation) == true){
		      $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
		        exit(header("Location: ".$config['web']['base_url']."administrator/payment-auto/")); 
		   } else {
    		    $input_midtrans = array(
    		        
    		        'value_1' => encrypt($validation['server_key_midtrans']),
    		        );
		        $update_midtrans = $model->db_update($db, "payment_setting", $input_midtrans, "id= '1'");
		        $input_ipaymu = array(
    		        
    		        'value_1' => encrypt($validation['va_ipaymu']),
    		        'value_2' => encrypt($validation['apikey_ipaymu']),
    		        );
		        $update_ipaymu = $model->db_update($db, "payment_setting", $input_ipaymu, "id= '2'");
		        $input_bca = array(
    		        
    		        'value_1' => encrypt($validation['user_bca']),
    		        'value_2' => encrypt($validation['password_bca']),
    		        );
		        $update_bca = $model->db_update($db, "payment_setting", $input_bca, "id= '3'");
		        $input_cp = array(
    		        
    		        'value_1' => encrypt($validation['public_key_cp']),
    		        'value_2' => encrypt($validation['private_key_cp']),
    		        'value_3' => encrypt($validation['merchant_id']),
    		        'value_4' => encrypt($validation['ipn_secret']),
    		        'value_5' => encrypt($validation['debug_email']),
    		        );
		        $update_cp = $model->db_update($db, "payment_setting", $input_cp, "id= '4'");
		        
		        if($update_midtrans == true && $update_ipaymu == true && $update_bca == true && $update_cp == true){
		           $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Payment Setting Terupdate.');
		            exit(header("Location: ".$config['web']['base_url']."administrator/payment-auto/"));  
		        } else {
		            $_SESSION['result'] = array('alert' => 'danger', 'title' => 'gagal!', 'msg' => 'Gagal Update Payment.');
		            exit(header("Location: ".$config['web']['base_url']."administrator/payment-auto/"));
		        }
		        
		   }
	}
    
}