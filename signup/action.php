<?php
require '../web.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
if(!isset($_POST)){
    exit("Permintaan tidak diterima.");
} else {
    $input_data = array('full_name', 'email', 'username', 'password', 'co_password');
    if (check_input($_POST, $input_data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak sesuai.');
		exit(header("Location: ".$config['web']['base_url']."signup/"));
	} else {
	    
	    $captcha = $_POST['g-recaptcha-response'];
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$website['rows']['secret_key']."&response=".$captcha."");
		$response = json_decode($response);
		$validation = array(
			'full_name' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['full_name'])))),
			'email' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['email'])))),
			'username' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['username'])))),
			'password' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['password'])))),
			'co_password' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['co_password']))))
		);
		$no_hp = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['no_hp']))));
		$format = $validation['email'];
        $pisah = explode("@", $format);
        $data_email = $pisah[1];
		$validasi_hp = hp($no_hp);
		$check_user = $model->db_query($db, "*", "user", "username = '".$validation['username']."'");
		$check_email = $model->db_query($db, "*", "user", "email = '".encrypt($validation['email'])."'");
		$ip = get_client_ip();
		$check_ip = $model->db_query($db, "*", "banned_ip", "ip = '$ip'");
		$_SESSION['full_name'] = $validation['full_name'];
		$_SESSION['email'] = $validation['email'];
		$_SESSION['username'] = $validation['username'];
		$_SESSION['no_hp'] = $no_hp;
		$enkrip_hp = encrypt($validasi_hp);
		
		if($no_hp != null){
		   $check_hp = $model->db_query($db, "*", "user", "no_hp = '$enkrip_hp'"); 
		}
		if($website['rows']['secret_key'] != null){
    		if ($response->success == false) {
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Silahkan Klik Google Recaptcha');
    		    exit(header("Location: ".$config['web']['base_url']."signup/"));
    		} 
		}
		
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input tidak boleh kosong.');
			exit(header("Location: ".$config['web']['base_url']."signup/"));
		}  
		elseif (strlen($validation['username']) < 5) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Username minimal 5 karakter.');
			exit(header("Location: ".$config['web']['base_url']."signup/")); 
		} elseif (strlen($validation['password']) < 5) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Password minimal 5 karakter.');
			exit(header("Location: ".$config['web']['base_url']."signup/"));
		} 
		elseif (!$no_hp ) {
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'No hp Tidak Boleh Kosong.');
			exit(header("Location: ".$config['web']['base_url']."signup/"));
			
		} 
		elseif(strlen($no_hp) < 10 OR strlen($no_hp) > 20){
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Nomer Hp Harus Terdiri minimal 10 Karakter dan Max 20 Karakter.');
			exit(header("Location: ".$config['web']['base_url']."signup/"));
		}
		else if (!isset($_POST['accept_terms'])) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Silahkan setujui syarat dan ketentuan.');
			exit(header("Location: ".$config['web']['base_url']."signup/"));
		} else if ($check_user['count'] == 1) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Username Telah Terdaftar.');
			exit(header("Location: ".$config['web']['base_url']."signup/"));
		} else if ($check_email['count'] == 1) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Email Telah Terdaftar.');
			exit(header("Location: ".$config['web']['base_url']."signup/"));
		}else if ($check_hp['count'] > 0) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'No Hp Telah Terdaftar.');
			exit(header("Location: ".$config['web']['base_url']."signup/"));
		}else if ($check_ip['count'] > 0) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Kami Mendeteksi, IP yang anda gunakan pernah melakukan tindakan melanggar pada website kami! Sehingga Anda Tidak Di izinkan Untuk Mendaftar. Hubungi Kami Untuk Informasi Lebih Lanjut');
			exit(header("Location: ".$config['web']['base_url']."signup/"));
		} else if ($validation['password'] <> $validation['co_password']) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Konfirmasi Password Tidak Cocok.');
			exit(header("Location: ".$config['web']['base_url']."signup/"));
		} elseif(temp_mail($validation['email']) == true){
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Anda tidak dapat menggunakan email sementara atau email yang tidak valid.');
			exit(header("Location: ".$config['web']['base_url']."signup/"));
		} else {
		    $username_cek = preg_replace("/[^a-zA-Z0-9]/", "", $_POST['username']);
		    $post_kode = str_rand(50);
		    $hash_code = password_hash($post_kode, PASSWORD_DEFAULT);
			$input_post = array(
			    'role' => '1',
				'nama' => $validation['full_name'],
				'email' => encrypt($db->real_escape_string(trim(htmlspecialchars(htmlentities(strtolower(strtolower($_POST['email']))))))),
				'no_hp' => encrypt(hp($no_hp)),
				'username' => $db->real_escape_string(trim(htmlspecialchars(htmlentities(strtolower($username_cek))))),		
				'password' => password_hash($validation['password'], PASSWORD_DEFAULT),
				'saldo_tersedia' => 0,
				'saldo_kliring' => 0,
				'withdraw' => 0,
				'status' => 'Not Verified',
				'created_at' => date('Y-m-d H:i:s'),
				'verifikasi' => $hash_code,
				'terima_tele_pesan' => '0',
				'terima_tele_orderan' => '0',
			);
			if ($model->db_query($db, "username", "user", "username = '".mysqli_real_escape_string($db, $input_post['username'])."'")['count'] > 0) {
				$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Username sudah terdaftar.');
				exit(header("Location: ".$config['web']['base_url']."signup/"));
			} else if ($model->db_query($db, "email", "user", "email = '".mysqli_real_escape_string($db, encrypt($input_post['email']))."'")['count'] > 0) {
				$result_msg = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Email sudah terdaftar.');
				exit(header("Location: ".$config['web']['base_url']."signup/"));
			} else {
				if ($model->db_insert($db, "user", $input_post) == true) {
				    
				    $mail_verif = $model->db_query($db, "*", "email", "id = '1'");
				    $username = $input_post['username'];
    				$ke = decrypt($input_post['email']);
    				$nama = $input_post['nama'];
    				
    				$format = $mail_verif['rows']['email'];
                    $pisah = explode("{{full_name}}", $format);
                    $pisah_1 = explode("{{post_kode}}", $pisah[1]);
                    $pisah_2 = explode("{{alternatif_link}}", $pisah_1[1]);
                    
				    $register_mail = $pisah[0].$username.$pisah_1[0].$config['web']['base_url']."verify?token=".$post_kode."&mail=".decrypt($input_post['email']).$pisah_2[0].$config['web']['base_url']."verify?token=".$post_kode."&mail=".decrypt($input_post['email']).$pisah_2[1];
				    $subject = "Konfirmasi Akun";
				    kirim_email($ke, $nama, $register_mail, $subject);
				    
				        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Pendaftaran berhasil!', 'msg' => 'Cek Inbox/Spam/Promotion/Primary Email Kamu Untuk konfirmasi akun ^.^ Contact email: suppport@gubukdigital.net jika terjadi masalah');
			            exit(header("Location: ".$config['web']['base_url']."signin/"));  
				   
                       
                        
                        
				   
				       
				 
				} else {
					$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pengguna gagal didaftarkan.');
					exit(header("Location: ".$config['web']['base_url']."signup/"));
				}
			}
		}
	}
    
}
?>