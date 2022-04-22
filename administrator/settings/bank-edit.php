<?php
require '../../web.php';
require '../../lib/check_session_admin.php';
require '../../lib/is_login.php';

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."sigin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."' AND role ='2' ")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']));
}
// // 	Delete untuk mengaktifkan
// 	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Tindakan Tidak Dapat Dilakukan Pada Akun Demo, Beli Script DI WA: +6285668708552.');
// 	exit(header("Location: ".$config['web']['base_url']."administrator/bank-pembayaran/"));
// // 	Delete untuk mengaktifkan
if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."administrator/bank-pembayaran/"));
} else {
    $data = array('bank', 'nama_pemilik', 'no_rek');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."administrator/bank-pembayaran/"));
	} else {
	    $validation = array(
			'bank' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['bank'])))),
			'nama_pemilik' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['nama_pemilik'])))),
			'no_rek' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['no_rek'])))),
			'status' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['status'])))),
			'status_depo' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['status_depo'])))),
			'status_fitur' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['status_fitur'])))),
			'buid' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['buid'])))),
		);
		$rate_dollar = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['rate_dollar']))));
		if(check_empty($validation) == true){
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
		    exit(header("Location: ".$config['web']['base_url']."administrator/bank-pembayaran/"));
		} else {
		    
		    $update = $model->db_update($db, "bank_information", array(
		        'bank' => strtoupper($validation['bank']), 
		        'nama_pemilik_bank' => $validation['nama_pemilik'], 
		        'no_rek' => $validation['no_rek'], 
		        'status' => $validation['status'], 
		        'deposit' => $validation['status_depo'], 
		        'fitur' => $validation['status_fitur'], 
		        'rate_dollar' => $rate_dollar), 
		        
		        "id = '".$validation['buid']."'");
		    if($update == true){
		        if(file_exists($_FILES['file']['tmp_name'])){
    		        $ekstensi_diperbolehkan	= array('png','jpg', 'jpeg');
    		        $nama_gambar = strtr(strtoupper($validation['bank']), "/", "-" );
            		$nama = $nama_gambar;
            		$nama_asli = $_FILES['file']['name'];
            		$x = explode('.', $nama_asli);
            		$ekstensi = strtolower(end($x));
            		$ukuran	= $_FILES['file']['size'];
            		$file_tmp = $_FILES['file']['tmp_name'];
            		if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
        			if($ukuran < 2097152){	
        			    $tempdir = "../../img/bank/"; 
        			    $target_path = $tempdir.$nama;
                        $files = glob('../../img/bank/'.$nama);
        				if(move_uploaded_file($file_tmp, $target_path) == true){
        				$update = $model->db_update($db, "bank_information", array('icon' => $nama), "id = '".$validation['buid']."'");
        				} else {
        				    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Icon tidak terupload.');
        					exit(header("Location: ".$config['web']['base_url']."administrator/bank-pembayaran/"));
        				}
        			}else{
        			    	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'UKURAN FILE TERLALU BESAR.');
        			    	exit(header("Location: ".$config['web']['base_url']."administrator/bank-pembayaran/"));
        			}
        		}
    		        
    		    }
		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Bank Berhasil di Edit.');
		        exit(header("Location: ".$config['web']['base_url']."administrator/bank-pembayaran/"));
		    } else {
		         $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Gagal Update.');
		        exit(header("Location: ".$config['web']['base_url']."administrator/bank-pembayaran/"));
		    }
		    
		}
	}
}