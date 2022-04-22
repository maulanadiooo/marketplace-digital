<?php
require '../web.php';
require '../lib/is_login.php';
require '../lib/csrf_token.php';

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']."signin/"));
}
if (!isset($_GET['id'])) {
	exit("No direct script access allowed!3");
} else{
$data_target = $model->db_query($db, "*", "services", "id = '".mysqli_real_escape_string($db, $_GET['id'])."'");
if ($data_target['count'] == 0) {
    exit("Data tidak ditemukan.");
}
if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."my-product/"));
} elseif(!isset($_FILES)){
    exit(header("Location: ".$config['web']['base_url']."my-product/"));
} else {
    $data = array('product_name', 'category', 'deskripsi','price', 'tags', 'faq', 'jangka_waktu');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
	}else {
	    $validation = array(
			'product_name' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['product_name'])))),
			'category' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['category'])))),
			'deskripsi' => $_POST['deskripsi'],
			'price' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['price'])))),
			'tags' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['tags'])))),
			'faq' => $_POST['faq'],
			'jangka_waktu' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['jangka_waktu'])))),
		);
		if (check_empty($validation) == true) {
			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
			exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
		} else {
		    if (file_exists($_FILES['file']['tmp_name'])) {
		        
            $target = "../file-photo/".$data_target['rows']['id']."/".$data_target['rows']['photo'];
            unlink($target);
            }
            $result_tags = preg_replace("/[^a-zA-Z 0-9,]/", "", $validation['tags']);
		    $tags_count = substr_count($result_tags, " ");
		    $result = preg_replace("/[^a-zA-Z 0-9]/", "", $validation['product_name']);
    		$url_nama_produk = strtr($result, " ", "-" );
    		
    		
    		$informasi_pembeli = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['buyer_information']))));
            $extra_product = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['extra_produk']))));
            $extra_product1 = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['extra_produk1']))));
            $extra_product2 = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['extra_produk2']))));
            $price_extra_product = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['price_extra_produk']))));
            $price_extra_product1 = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['price_extra_produk1']))));
            $price_extra_product2 = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['price_extra_produk2']))));
            $quantity = $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['quantity']))));
            
            if(!$informasi_pembeli){
		        $allow_buyer_information = 'no';
		    } else {
		        $allow_buyer_information = 'yes';
		    }
		    if($quantity == '1'){
		        $allow_multi_quantity = 'no';
		    } else {
		        $allow_multi_quantity = 'yes';
		    }
		    
		    if ($tags_count > 9) {
    			$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maksimal Hanya 10 tags.');
    			exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
    		} elseif (strlen($validation['product_name']) > 100) {
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maksimal Nama Produk 100 Karakter.');
    			exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
    		} elseif (strlen($validation['product_name']) < 10) {
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Nama Produk Terlalu Pendek, minimal 10 Karakter.');
    			exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
    		} elseif (strlen($validation['deskripsi']) < 496) {
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Deksripsi Terlalu Singkat, minimal 400 Karakter.');
    			exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
    		} elseif (strlen($validation['faq']) < 344) {
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Faq Terlalu Singkat, minimal 250 Karakter.');
    			exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
    		} elseif($extra_product != null && $price_extra_product == null){
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Harga Untuk Ekstra Produk1 Belum Terisi');
    		    exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
    		} elseif($extra_product == null && $price_extra_product != null){
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Nama Untuk Ekstra Produk1 Belum Terisi');
    		    exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
    		} elseif($extra_product1 != null && $price_extra_product1 == null){
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Harga Untuk Ekstra Produk2 Belum Terisi');
    		    exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
    		} elseif($extra_product1 == null && $price_extra_product1 != null){
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Nama Untuk Ekstra Produk2 Belum Terisi');
    		    exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
    		} elseif($extra_product2 != null && $price_extra_product2 == null){
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Harga Untuk Ekstra Produk3 Belum Terisi');
    		    exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
    		} elseif($extra_product2 == null && $price_extra_product2 != null){
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Nama Untuk Ekstra Produk3 Belum Terisi');
    		    exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
    		} else {
    		    $input_post = array(
		        'nama_layanan' => $validation['product_name'],
		        'categories_id' => $validation['category'],
		        'author' => $login['id'],
		        'url' => $url_nama_produk,
		        'description' => $validation['deskripsi'],
		        'allow_buyer_information' => $allow_buyer_information,
		        'faq' => $validation['faq'],
		        'price' => $validation['price'],
		        'tags' => $result_tags,
		        'jangka_waktu' => $validation['jangka_waktu'],
		        'updated_at' => date('Y-m-d H:i:s'),
		        'status' => 'pending',
		        'buyer_information' => $informasi_pembeli,
		        'extra_product' => $extra_product,
		        'price_extra_product' => $price_extra_product,
		        'extra_product1' => $extra_product1,
		        'price_extra_product1' => $price_extra_product1,
		        'extra_product2' => $extra_product2,
		        'price_extra_product2' => $price_extra_product2,
		        'allow_multisale' => $allow_multi_quantity,
		         'max_pembelian' => $quantity
		        );
		        $update = $model->db_update($db, "services", $input_post, "id = '".$db->real_escape_string(trim(htmlspecialchars(htmlentities($_GET['id']))))."'");
		        if ($update == true) {
    		            if(file_exists($_FILES['file']['tmp_name'])){
        		        $ekstensi_diperbolehkan	= array('png','jpg', 'jpeg');
                		$nama = $url_nama_produk;
                		$nama_asli = $_FILES['file']['name'];
                		$x = explode('.', $nama_asli);
                		$ekstensi = strtolower(end($x));
                		$ukuran	= $_FILES['file']['size'];
                		$file_tmp = $_FILES['file']['tmp_name'];
        		        if($ukuran > 2097152 ){
        		           $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maksimal File Thumbnail 2MB');
        		            exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id'])); 
        		        } elseif(in_array($ekstensi, $ekstensi_diperbolehkan) === false){
                		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'File Yang diperbolehkan hanya PNG, JPG dan JPEG');
                		    exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
                		}else {
                		   $tempdir = "../file-photo/".$data_target['rows']['id']."/"; 
            			    $target_path = $tempdir.$nama.".".$ekstensi;
                            if (!file_exists($tempdir))
                             mkdir($tempdir,0755);
                             $files = glob('../file-photo/'.$data_target['rows']['id'].$nama.".".$ekstensi);
                             move_uploaded_file($file_tmp, $target_path);
                             $model->db_update($db, "services", array('photo' => $nama.".".$ekstensi), "id = '".$data_target['rows']['id']."'"); 
            		      
                		    
        		        }
        		    }
        		    if(file_exists($_FILES['file1']['tmp_name'])){
        		        $ekstensi_diperbolehkan	= array('png','jpg', 'jpeg');
                		$nama1 = $url_nama_produk."1";
                		$nama_asli = $_FILES['file1']['name'];
                		$x = explode('.', $nama_asli);
                		$ekstensi = strtolower(end($x));
                		$ukuran	= $_FILES['file1']['size'];
                		$file_tmp = $_FILES['file1']['tmp_name'];
        		        if($ukuran > 2097152 ){
        		           $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maksimal File Other Pict Pertama 2MB');
        		            exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id'])); 
        		        } elseif(in_array($ekstensi, $ekstensi_diperbolehkan) === false){
                		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'File Yang diperbolehkan hanya PNG, JPG dan JPEG');
                		    exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
                		}else {
                		   $tempdir = "../file-photo/".$data_target['rows']['id']."/"; 
            			    $target_path = $tempdir.$nama1.".".$ekstensi;
                            if (!file_exists($tempdir))
                             mkdir($tempdir,0755);
                             $files = glob('../file-photo/'.$data_target['rows']['id'].$nama1.".".$ekstensi);
                             move_uploaded_file($file_tmp, $target_path);
                             $model->db_update($db, "services", array('photo_1' => $nama1.".".$ekstensi), "id = '".$data_target['rows']['id']."'"); 
            		      
                		    
        		        }
        		    }
        		    if(file_exists($_FILES['file2']['tmp_name'])){
        		        $ekstensi_diperbolehkan	= array('png','jpg', 'jpeg');
                		$nama2 = $url_nama_produk."2";
                		$nama_asli = $_FILES['file2']['name'];
                		$x = explode('.', $nama_asli);
                		$ekstensi = strtolower(end($x));
                		$ukuran	= $_FILES['file2']['size'];
                		$file_tmp = $_FILES['file2']['tmp_name'];
        		        if($ukuran > 2097152 ){
        		           $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Maksimal File Other Pict Kedua 2MB');
        		            exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id'])); 
        		        } elseif(in_array($ekstensi, $ekstensi_diperbolehkan) === false){
                		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'File Yang diperbolehkan hanya PNG, JPG dan JPEG');
                		    exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
                		}else {
                		   $tempdir = "../file-photo/".$data_target['rows']['id']."/"; 
            			    $target_path = $tempdir.$nama2.".".$ekstensi;
                            if (!file_exists($tempdir))
                             mkdir($tempdir,0755);
                             $files = glob('../file-photo/'.$data_target['rows']['id'].$nama2.".".$ekstensi);
                             move_uploaded_file($file_tmp, $target_path);
                             $model->db_update($db, "services", array('photo_2' => $nama2.".".$ekstensi), "id = '".$data_target['rows']['id']."'"); 
            		      
                		    
        		        }
        		    }
    		        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Produk Berhasil Diupdate.');
    			exit(header("Location: ".$config['web']['base_url']."edit/".$data_target['rows']['id']));
    		    }
    		    
    		}
		    
		    
		}
	}
  }  
}