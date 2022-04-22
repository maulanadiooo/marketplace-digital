<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/csrf_token.php';
if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."sigin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}

if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."withdraw/"));
}else {
    $website = $model->db_query($db, "*", "website", "id = '1'");
    $lama_wd = $website['rows']['lama_wd'];
    $data = array('withdraw_bank', 'withdraw_nama', 'withdraw_norek');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."withdraw/"));
	} else {
	    
	    $user_info = $model->db_query($db, "*", "user", "id = '".$login['id']."'");
	    $saldo = $user_info['rows']['saldo_tersedia'];
	    $status_wd = $model->db_query($db, "*", "withdraw_request", "user_id = '".$login['id']."' AND status = 'pending'");
	    
	    $validation = array(
			'withdraw_bank' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['withdraw_bank'])))),
			'withdraw_nama' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['withdraw_nama'])))),
			'withdraw_norek' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['withdraw_norek'])))),
			'filter_opt' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['filter_opt'])))),
		);
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
			exit(header("Location: ".$config['web']['base_url']."withdraw/"));
		} else {
		    if($validation['filter_opt'] == "on"){
		        $amount = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['amount']))));
		    } else {
		        $amount = $validation['filter_opt'];
		    }
		    
		    if ($amount > $user_info['rows']['saldo_tersedia']) {
    			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Saldo Anda Kurang Dari Permintaan '.$amount);
    			exit(header("Location: ".$config['web']['base_url']."withdraw/"));
    		} elseif ($amount < $website['rows']['min_wd']) {
    			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Minimal Penarikan Rp '.number_format($website['rows']['min_wd'],0,',','.'));
    			exit(header("Location: ".$config['web']['base_url']."withdraw/"));
    		} elseif($status_wd['count'] > 0){
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Penarikan Sebelumnya masih dalam proses ^.^ ');
    			exit(header("Location: ".$config['web']['base_url']."withdraw/"));
    		}else {
    		    $now = date("Y-m-d H:i:s");
    		    $estimasi_wd = date('Y-m-d H:i:s',strtotime('+'.$lama_wd.' Day',strtotime($now)));
    		    $namahari = date('D', strtotime($estimasi_wd));
    		    if($namahari === 'Sat'){
    		        $estimasi_wd_akhir = date('Y-m-d H:i:s',strtotime('+2 Day',strtotime($estimasi_wd)));
    		    } elseif($namahari === 'Sun') {
    		        $estimasi_wd_akhir = date('Y-m-d H:i:s',strtotime('+1 Day',strtotime($estimasi_wd)));
    		    } else {
    		        $estimasi_wd_akhir = $estimasi_wd;
    		    }
    		    $estimasi_wd_1 = format_date(substr($estimasi_wd, 0, -9));
    		    $input_post = array(
		        'user_id' => $login['id'],
		        'bank' => $validation['withdraw_bank'],
		        'nama_pemilik' => $validation['withdraw_nama'],
		        'no_rek' => encrypt($validation['withdraw_norek']),
		        'amount' => $amount,
		        'created_at' => date('Y-m-d H:i:s'),
		        'estimasi_wd' => $estimasi_wd_akhir,
		        'status' => 'pending'
		        );
		        $insert = $model->db_insert($db, "withdraw_request", $input_post);
		        if ($insert == true) {
		            $update = $db->query("UPDATE user set saldo_tersedia = saldo_tersedia-$amount WHERE id = '".$login['id']."' ");
		            if($update == true){
		                $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Proses Withdraw Berhasil, Estimasi Pencairan : '.$estimasi_wd_1.' Pukul 19.00 - 21.00 WIB');
    			        exit(header("Location: ".$config['web']['base_url']."withdraw/"));
		            } else {
		                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Permintaan Withdraw Tidak Berhasil, Hubungi Admin Website(1)!');
    			        exit(header("Location: ".$config['web']['base_url']."withdraw/"));
		            }
		        } else {
		            $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Permintaan Withdraw Tidak Berhasil, Hubungi Admin Website!');
    			    exit(header("Location: ".$config['web']['base_url']."withdraw/"));
		        }
    		    
    		}
		    
		    
		}
	    
	}
    
    
}