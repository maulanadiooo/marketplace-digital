<?php
require '../../web.php';
require '../../lib/check_session_admin.php';
require '../../lib/result.php';
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
    exit(header("Location: ".$config['web']['base_url']."create/"));
}else{
    $data = array('oid','inid');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
		exit(header("Location: ".$config['web']['base_url']."create/"));
	} else {
	$validation = array(
			'oid' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['oid'])))),
			'inid' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['inid'])))),
		);    
	 
	$data_target = $model->db_query($db, "*", "cart", "id = '".$validation['oid']."' AND kode_invoice = '".$validation['inid']."' ");
    if ($data_target['count'] == 0) {
        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Data tidak ditemukan.');
		exit(header("Location: ".$config['web']['base_url']."administrator/orders/"));
	} else {
        $now_tgl = date('Y-m-d H:i:s');
                           
      $website = $model->db_query($db, "*", "website", "id = '1'");
     $data_targetss = $model->db_query($db, "*", "cart", "kode_invoice = '".$validation['inid']."' ");
     $now = date("Y-m-d H:i:s");
     $data_services = $model->db_query($db, "*", "services", "id = '".$data_targetss['rows']['service_id']."' ");
     $jangka_waktu = $data_services['rows']['jangka_waktu'];
     $send_before = date('Y-m-d H:i:s',strtotime('+'.$jangka_waktu.' Day',strtotime($now)));
     
     $input_post_orders_active = array(
        'status' => 'active',
        'created_at' => $now,
        'send_before' => $send_before
        );
    $update_orders = $model->db_update($db, "orders", $input_post_orders_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' ");
    
    $input_post_update_active = array(
    'pembayaran_id_bank' => '2',
    'status' => 'success'
    );
    $update_cart = $model->db_update($db, "cart", $input_post_update_active, "kode_unik = '".$data_targetss['rows']['kode_unik']."' "); 
    
     $order_detail = $model->db_query($db, "*", "orders", "kode_unik = '".$data_targetss['rows']['kode_unik']."'");
     $input_post_penghasilan_admin = array(
    'admin_fee' => $website['rows']['admin_fee'],
    'order_id' => $order_detail['rows']['id'],
    'created_at' => $now
    );
    $insert = $model->db_insert($db, "penghasilan_admin", $input_post_penghasilan_admin);
    if($update_orders == true && $update_cart == true){
        $update_history_pembayaran = array(
        'user_id' => $data_targetss['rows']['buyer_id'],
        'amount' => $data_targetss['rows']['price_kode_unik'],
        'message' => 'Pembelian Produk #'.$order_detail['rows']['id']." - ".$data_services['rows']['nama_layanan'],
        'created_at' => $now
        );
        $model->db_insert($db, "history_pembayaran", $update_history_pembayaran);
        $update_notifikasi = array(
        'buyer_id' => $data_targetss['rows']['buyer_id'],
        'seller_id' => $data_services['rows']['author'],
        'service_id' => $data_targetss['rows']['service_id'],
        'type' => 'pembelian',
        'go' => "show-sales/".$order_detail['rows']['id'],
        'created_at' => $now
        );
        $model->db_insert($db, "notifikasi", $update_notifikasi);
        
        
         $email_orderan = $model->db_query($db, "*", "email", "id = '3'");
        $user_penjual = $model->db_query($db, "*", "user", "id = '".$data_services['rows']['author']."' ");	
        $ke = decrypt($user_penjual['rows']['email']);
        $message_sms = 'Halo '.$username.' ada pesanan baru nih, silahkan di cek pada '.$message_sm;
        $nohp = decrypt($user_penjual['rows']['no_hp']);
        $nama = $user_penjual['rows']['nama'];
        $format = $email_orderan['rows']['email'];
        $pisah = explode("{{link_penjualan}}", $format);
        $orderan_link = $pisah[0].$config['web']['base_url']."show-sales/".$order_detail['rows']['id'].$pisah[1];
        $subject = "Pesanan Baru";
        $mail = new PHPMailer; 
        $mail->IsSMTP();
        $mail->SMTPSecure = 'ssl'; 
        $mail->Host = $smtp; //host masing2 provider email
        $mail->SMTPDebug = 0;
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->Username = $username_smtp; //user email
        $mail->Password = $password_smtp; //password email 
        $mail->SetFrom('no-reply@gubukdigital.net','Gubuk Digital - Marketplace Terpercaya'); //set email pengirim
        $mail->AddReplyTo('support@gubukdigital.net','Gubuk Digital - Marketplace Terpercaya'); //set email reply
        $mail->Subject = "Pesanan Baru"; //subyek email
        $mail->AddAddress($ke,$nama);  //tujuan email
        $mail->MsgHTML($orderan_link);
        $mail->send();
        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Invoice '.$validation['inid'].' Berhasil Disetujui!');
	   exit(header("Location: ".$config['web']['base_url']."administrator/orders/"));
        
        
    }else {
        echo "Something wrong!";
    }
		
	}
	}
}   