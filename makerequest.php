<?

require 'web.php';
require 'lib/is_login.php';
require 'lib/check_session.php';
require 'lib/csrf_token.php';

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}

if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."my-sales/"));
}else {
    $data = array('jangka_waktu','message_req', 'price');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']));
	}else {
    	$validation = array(
    			'jangka_waktu' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['jangka_waktu'])))),
    			'message_req' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['message_req'])))),
    			'price' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['price'])))),
    	);
    	$date = date('Y-m-d H:i:s');
		
        
    	if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
			exit(header("Location: ".$config['web']['base_url']));
		} elseif (strlen($validation['message_req']) > 800) {
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Pesanya kepanjangan gan, Maksimal 800 Karakter.');
			exit(header("Location: ".$config['web']['base_url']));
		} else {
		    
             $input_post = array(
	            'user_id' => $login['id'],
	            'permintaan' => $validation['message_req'],
		        'budget' => $validation['price'],
		        'jangka_waktu' => $validation['jangka_waktu'],
		        'status' => 'pending',
		        'created_at' => date('Y-m-d H:i:s')
		        );
		      $insert = $model->db_insert($db, "permintaan_pembeli", $input_post);
		      if($insert == true){
		          $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Permintaan Anda Telah Berhasil Terkirim Untuk di Review.');
                  exit(header("Location: ".$config['web']['base_url']));
		      } else {
		          $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Gagal Buat Permintaan, Code (2).');
                 exit(header("Location: ".$config['web']['base_url']));
		      }
                   
		    
		}
	    
	}
    
    
}