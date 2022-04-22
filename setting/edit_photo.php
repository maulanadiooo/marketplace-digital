<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/result.php';

    if (!isset($_SESSION['login'])) {
		exit("No direct script access allowed!1");
	}
	if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
		exit("No direct script access allowed!2");
	}
	if (!isset($_GET['id'])) {
		exit("No direct script access allowed!11");
	}
	$data_target = $model->db_query($db, "*", "user", "id = '".mysqli_real_escape_string($db, $_GET['id'])."'");
	
	if ($data_target['count'] == 0) {
	    exit("Data tidak ditemukan.");
	} 
if(isset($_POST['upload'])){
        $ekstensi_diperbolehkan	= array('png','jpg', 'jpeg');
		$nama = $data_target['rows']['username'];
		$nama_asli = $_FILES['file']['name'];
		$x = explode('.', $nama_asli);
		$ekstensi = strtolower(end($x));
		$ukuran	= $_FILES['file']['size'];
		$file_tmp = $_FILES['file']['tmp_name'];	
		
		if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
			if($ukuran < 1044070){	
			    $tempdir = "../user-photo/"; 
			    $target_path = $tempdir.$nama;
                if (!file_exists($tempdir))
                mkdir($tempdir,0755); 
                $files = glob('../user-photo/'.$nama);
				if(move_uploaded_file($file_tmp, $target_path) == true){
				$update = $model->db_update($db, "user", array('photo' => $nama), "id = '".$_GET['id']."'");
				if($update == true){
				    $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Foto Profile Berhasil Diganti.');
					exit(header("Location: ".$config['web']['base_url']."setting/"));
				}else{
				    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'GAGAL MENGUPLOAD GAMBAR.');
				    exit(header("Location: ".$config['web']['base_url']."setting/"));
				}
				} else {
				    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'tidak terupload.');
					exit(header("Location: ".$config['web']['base_url']."setting/"));
				}
			}else{
			    	$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'UKURAN FILE TERLALU BESAR MAX 1MB.');
			    	exit(header("Location: ".$config['web']['base_url']."setting/"));
			}
		}else{
		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN, HANYA PNG, JPG, dan JPEG.');
		    exit(header("Location: ".$config['web']['base_url']."setting/"));
		}
	    
} else {
    echo "gagal";
    
}