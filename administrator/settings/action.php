<?php
require '../../web.php';
require '../../lib/check_session_admin.php';
$website = $model->db_query($db, "*", "website", "id = '1'");

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."administrator/auth/signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."' AND role = '2'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."administrator/auth/signin/"));
}


if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
} else {
    $data = array('title_website', 'email_notifikasi', 'deskripsi_website','keyword_website', 'admin_fee', 'admin_fee_seller', 'review_otomatis', 'lama_kliring', 'min_wd', 'min_depo', 'harga_featured', 'durasi_featured', 'harga_premium', 'durasi_premium');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
	}else {
	    $validation = array(
			'title_website' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['title_website'])))),
			'email_notifikasi' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['email_notifikasi'])))),
			'deskripsi_website' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['deskripsi_website'])))),
			'keyword_website' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['keyword_website'])))),
			'admin_fee' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['admin_fee'])))),
			'admin_fee_seller' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['admin_fee_seller'])))),
			'review_otomatis' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['review_otomatis'])))),
		    'lama_kliring' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['lama_kliring'])))),
		    'lama_wd' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['lama_wd'])))),
		    'min_wd' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['min_wd'])))),
		    'min_depo' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['min_depo'])))),
		    'harga_featured' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['harga_featured'])))),
		    'durasi_featured' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['durasi_featured'])))),
		    'harga_premium' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['harga_premium'])))),
		    'durasi_premium' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['durasi_premium'])))),
		);
		    $site_key = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['google_sitekey']))));
		    $secret_key = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['google_secretkey']))));
		    
		    $nohp = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['handphone']))));
		    $url_promotion = $_POST['url_promotion'];
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
			exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
		} else {
		    if (file_exists($_FILES['fav_icon']['tmp_name'])) {
		        
            $target = "../../file-photo/website/".$website['rows']['fav_icon'];
            unlink($target);
            }
            if (file_exists($_FILES['logo_website']['tmp_name'])) {
		        
            $target_logo = "../../file-photo/website/".$website['rows']['logo_web'];
            unlink($target_logo);
            }
    		$nama = strtr( $validation['title_website'], " ", "-" );
    		
    		$ekstensi_diperbolehkan	= array('png','jpg', 'jpeg');
    		$nama_asli_fav = strtr( $_FILES['fav_icon']['name'], " ", "-" );
    		$x = explode('.', $nama_asli_fav);
    		$ekstensi_fav = strtolower(end($x));
    		$ukuran_fav	= $_FILES['fav_icon']['size'];
    		$file_tmp_fav = $_FILES['fav_icon']['tmp_name'];
    		
    		$nama_asli_logo = strtr( $_FILES['logo_website']['name'], " ", "-" );
    		$x_logo = explode('.', $nama_asli_logo);
    		$ekstensi_logo = strtolower(end($x_logo));
    		$ukuran_logo	= $_FILES['logo_website']['size'];
    		$file_tmp_logo = $_FILES['logo_website']['tmp_name'];
    		
    		$nama_asli_promo = strtr( $_FILES['logo_promotion']['name'], " ", "-" );
    		$x_promo = explode('.', $nama_asli_promo);
    		$ekstensi_promo = strtolower(end($x_promo));
    		$ukuran_promo	= $_FILES['logo_promotion']['size'];
    		$file_tmp_promo = $_FILES['logo_promotion']['tmp_name'];
            
		    
		    if ($validation['title_website'] > 20) {
    			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maksimal20 Karakter.');
    			exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
    		} else {
    		    if(file_exists($_FILES['logo_website']['tmp_name']) && file_exists($_FILES['fav_icon']['tmp_name']) && file_exists($_FILES['logo_promotion']['tmp_name'])){
    		        if($ukuran_logo > 2097152 ){
    		           $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maksimal File Logo Website 2MB');
    		            exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
    		        } elseif($ukuran_fav > 1048576 ){
    		           $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maksimal Fav Icon 1MB');
    		            exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
    		        } elseif($ukuran_promo > 2097152 ){
    		           $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maksimal Fav Icon 2MB');
    		            exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
    		        } elseif(in_array($ekstensi_logo, $ekstensi_diperbolehkan) === false){
            		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'File Untuk Logo Web Hanya PNG, JPG dan JPEG');
            		    exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
            		} elseif(in_array($ekstensi_fav, $ekstensi_diperbolehkan) === false){
            		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'File Untuk Fav Icon Hanya PNG, JPG dan JPEG');
            		    exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
            		} elseif(in_array($ekstensi_promo, $ekstensi_diperbolehkan) === false){
            		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'File Untuk Promotion Hanya PNG, JPG dan JPEG');
            		    exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
            		}else {
            		    $tempdir = "../../file-photo/website/"; 
        			    $target_path_fav = $tempdir.$nama_asli_fav;
                        $target_path_logo = $tempdir.$nama_asli_logo;
                        $target_path_promo = $tempdir.$nama_asli_promo;
                        
                        move_uploaded_file($file_tmp_fav, $target_path_fav);
                        move_uploaded_file($file_tmp_logo, $target_path_logo);
                        move_uploaded_file($file_tmp_promo, $target_path_promo);
                        
            		    $input_post = array(
        		        'title' => $validation['title_website'],
        		        'description' => $validation['deskripsi_website'],
        		        'keyword' => $validation['keyword_website'],
        		        'email_notifikasi' => $validation['email_notifikasi'],
        		        'no_hp' => hp($nohp),
        		        'fav_icon' => $nama_asli_fav,
        		        'logo_web' => $nama_asli_logo,
        		        'logo_promotion' => $nama_asli_promo,
        		        'url_promotion' => $url_promotion,
        		        'admin_fee' => $validation['admin_fee'],
        		        'admin_fee_seller' => $validation['admin_fee_seller'],
        		        'review_otomatis' => $validation['review_otomatis'],
        		        'lama_kliring' => $validation['lama_kliring'],
        		        'min_wd' => $validation['min_wd'],
        		        'min_depo' => $validation['min_depo'],
        		        'lama_wd' => $validation['lama_wd'],
        		        'created_at' => $date,
        		        'site_key' => $site_key,
        		        'secret_key' => $secret_key,
        		        'durasi_fitur_featured' => $validation['durasi_featured'],
        		        'harga_fitur_featured' => $validation['harga_featured'],
        		        'durasi_fitur_premium' => $validation['durasi_premium'],
        		        'harga_fitur_premium' => $validation['harga_premium'],
        		        
        		        );
        		        $update = $model->db_update($db, "website", $input_post, "id = '1'");
    		            if ($update == true) {
        		            
            		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Web Terupdate.');
            			    exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
            		    } else {
            		        echo "Gagal Update";
            		    }
    		        }
    		    }
    		    if(file_exists($_FILES['fav_icon']['tmp_name'])){
    		        if($ukuran_fav > 1048576 ){
    		           $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maksimal File Fav Icon 1MB');
    		            exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
    		        } elseif(in_array($ekstensi_fav, $ekstensi_diperbolehkan) === false){
            		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'File Yang Untuk Fav Icon Hanya PNG, JPG dan JPEG');
            		    exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
            		}else {
            		    $tempdir = "../../file-photo/website/"; 
        			    $target_path = $tempdir.$nama_asli_fav;
                        
                         move_uploaded_file($file_tmp_fav, $target_path);
            		    $input_post = array(
        		        'title' => $validation['title_website'],
        		        'description' => $validation['deskripsi_website'],
        		        'keyword' => $validation['keyword_website'],
        		        'email_notifikasi' => $validation['email_notifikasi'],
        		        'no_hp' => hp($nohp),
        		        'fav_icon' => $nama_asli_fav,
        		        'admin_fee' => $validation['admin_fee'],
        		        'admin_fee_seller' => $validation['admin_fee_seller'],
        		        'url_promotion' => $url_promotion,
        		        'review_otomatis' => $validation['review_otomatis'],
        		        'lama_kliring' => $validation['lama_kliring'],
        		        'min_wd' => $validation['min_wd'],
        		        'min_depo' => $validation['min_depo'],
        		        'lama_wd' => $validation['lama_wd'],
        		        'created_at' => $date,
        		        'site_key' => $site_key,
        		        'secret_key' => $secret_key,
        		        'durasi_fitur_featured' => $validation['durasi_featured'],
        		        'harga_fitur_featured' => $validation['harga_featured'],
        		        'durasi_fitur_premium' => $validation['durasi_premium'],
        		        'harga_fitur_premium' => $validation['harga_premium'],
        		        
        		        
        		        );
        		        $update = $model->db_update($db, "website", $input_post, "id = '1'");
    		            if ($update == true) {
        		            
            		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Web Terupdate.');
            			    exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
            		    } else {
            		        echo "Gagal Update";
            		    }
    		        }
    		    }
    		    if(file_exists($_FILES['logo_website']['tmp_name'])){
    		        if($ukuran_logo > 2097152 ){
    		           $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maksimal File Logo Website 2MB');
    		            exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
    		        } elseif(in_array($ekstensi_logo, $ekstensi_diperbolehkan) === false){
            		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'File Yang Untuk Logo Web Hanya PNG, JPG dan JPEG');
            		    exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
            		}else {
            		    $tempdir = "../../file-photo/website/"; 
        			    $target_path = $tempdir.$nama_asli_logo;
                        
                         move_uploaded_file($file_tmp_logo, $target_path);
            		    $input_post = array(
        		        'title' => $validation['title_website'],
        		        'description' => $validation['deskripsi_website'],
        		        'keyword' => $validation['keyword_website'],
        		        'email_notifikasi' => $validation['email_notifikasi'],
        		        'no_hp' => hp($nohp),
        		        'logo_web' => $nama_asli_logo,
        		        'admin_fee' => $validation['admin_fee'],
        		        'admin_fee_seller' => $validation['admin_fee_seller'],
        		        'url_promotion' => $url_promotion,
        		        'review_otomatis' => $validation['review_otomatis'],
        		        'lama_kliring' => $validation['lama_kliring'],
        		        'min_wd' => $validation['min_wd'],
        		        'min_depo' => $validation['min_depo'],
        		        'lama_wd' => $validation['lama_wd'],
        		        'created_at' => $date,
        		        'site_key' => $site_key,
        		        'secret_key' => $secret_key,
        		        'durasi_fitur_featured' => $validation['durasi_featured'],
        		        'harga_fitur_featured' => $validation['harga_featured'],
        		        'durasi_fitur_premium' => $validation['durasi_premium'],
        		        'harga_fitur_premium' => $validation['harga_premium'],
        		        
        		        
        		        );
        		        $update = $model->db_update($db, "website", $input_post, "id = '1'");
    		            if ($update == true) {
        		            
            		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Web Terupdate.');
            			    exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
            		    } else {
            		        echo "Gagal Update";
            		    }
    		        }
    		    }
    		    if(file_exists($_FILES['logo_promotion']['tmp_name'])){
    		        if($ukuran_promo > 2097152 ){
    		           $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maksimal File Promotion Website 2MB');
    		            exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
    		        } elseif(in_array($ekstensi_promo, $ekstensi_diperbolehkan) === false){
            		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'File Untuk Promotion Web Hanya PNG, JPG dan JPEG');
            		    exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
            		}else {
            		    $tempdir = "../../file-photo/website/"; 
        			    $target_path = $tempdir.$nama_asli_promo;
                        
                         move_uploaded_file($file_tmp_promo, $target_path);
            		    $input_post = array(
        		        'title' => $validation['title_website'],
        		        'description' => $validation['deskripsi_website'],
        		        'keyword' => $validation['keyword_website'],
        		        'email_notifikasi' => $validation['email_notifikasi'],
        		        'no_hp' => hp($nohp),
        		        'logo_promotion' => $nama_asli_promo,
        		        'url_promotion' => $url_promotion,
        		        'admin_fee' => $validation['admin_fee'],
        		        'admin_fee_seller' => $validation['admin_fee_seller'],
        		        'review_otomatis' => $validation['review_otomatis'],
        		        'url_promotion' => $url_promotion,
        		        'lama_kliring' => $validation['lama_kliring'],
        		        'min_wd' => $validation['min_wd'],
        		        'min_depo' => $validation['min_depo'],
        		        'lama_wd' => $validation['lama_wd'],
        		        'created_at' => $date,
        		        'site_key' => $site_key,
        		        'secret_key' => $secret_key,
        		        'durasi_fitur_featured' => $validation['durasi_featured'],
        		        'harga_fitur_featured' => $validation['harga_featured'],
        		        'durasi_fitur_premium' => $validation['durasi_premium'],
        		        'harga_fitur_premium' => $validation['harga_premium'],
        		        
        		        
        		        );
        		        $update = $model->db_update($db, "website", $input_post, "id = '1'");
    		            if ($update == true) {
        		            
            		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Web Terupdate.');
            			    exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
            		    } else {
            		        echo "Gagal Update";
            		    }
    		        }
    		    }
    		    $input_post = array(
		        'title' => $validation['title_website'],
		        'description' => $validation['deskripsi_website'],
		        'keyword' => $validation['keyword_website'],
		        'email_notifikasi' => $validation['email_notifikasi'],
        		 'no_hp' => hp($nohp),
		        'admin_fee' => $validation['admin_fee'],
		        'admin_fee_seller' => $validation['admin_fee_seller'],
		        'review_otomatis' => $validation['review_otomatis'],
		        'lama_kliring' => $validation['lama_kliring'],
		        'min_wd' => $validation['min_wd'],
        		        'url_promotion' => $url_promotion,
		        'min_depo' => $validation['min_depo'],
        		'lama_wd' => $validation['lama_wd'],
		        'created_at' => $date,
		        'site_key' => $site_key,
		        'secret_key' => $secret_key,
		        'durasi_fitur_featured' => $validation['durasi_featured'],
		        'harga_fitur_featured' => $validation['harga_featured'],
		        'durasi_fitur_premium' => $validation['durasi_premium'],
		        'harga_fitur_premium' => $validation['harga_premium'],
		        
		        
		        );
		        $update = $model->db_update($db, "website", $input_post, "id = '1'");
		        if ($update == true) {
    		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Web Terupdate.');
    			    exit(header("Location: ".$config['web']['base_url']."administrator/settings/"));
    		    }
    		    
    		}
		    
		    
		}
	}
  }  