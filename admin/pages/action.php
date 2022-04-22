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
    exit(header("Location: ".$config['web']['base_url']."administrator/pages"));
} else {
    $data = array('carakerja1', 'carakerja2', 'carakerja3','tentang1', 'tentang2', 'syarat', 'kebijakan');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
		exit(header("Location: ".$config['web']['base_url']."administrator/pages"));
	}else {
	    $validation = array(
			'carakerja1' => $_POST['carakerja1'],
			'carakerja2' => $_POST['carakerja2'],
			'carakerja3' => $_POST['carakerja3'],
			'tentang1' => $_POST['tentang1'],
			'tentang2' => $_POST['tentang2'],
			'syarat' => $_POST['syarat'],
			'kebijakan' => $_POST['kebijakan'],
		);
		
		   if(check_empty($validation) == true){
		      $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
		        exit(header("Location: ".$config['web']['base_url']."administrator/pages")); 
		   } else {
    		    $input_post_carakerja = array(
    		        
    		        'value' => $validation['carakerja1'],
    		        'value_1' => $validation['carakerja2'],
    		        'value_2' => $validation['carakerja3'],
    		        'created_at' => $date,
    		        );
		        $insert_cara = $model->db_update($db, "pages", $input_post_carakerja, "id= '1'");
		        $input_post_syarat = array(
    		        
    		        'value' => $validation['syarat'],
    		        'created_at' => $date,
    		        );
		        $insert_syarat= $model->db_update($db, "pages", $input_post_syarat, "id= '2'");
		        $input_post_tentang = array(
    		        
    		        'value' => $validation['tentang1'],
    		        'value_1' => $validation['tentang2'],
    		        'created_at' => $date,
    		        );
		        $insert_tentang= $model->db_update($db, "pages", $input_post_tentang, "id= '3'");
		        $input_post_kebijakan = array(
    		        
    		        'value' => $validation['kebijakan'],
    		        'created_at' => $date,
    		        );
		        $insert_kebijakan= $model->db_update($db, "pages", $input_post_kebijakan, "id= '4'");
		        
		        if($insert_kebijakan == true && $insert_tentang == true && $insert_cara == true){
		           $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Halaman Web Berhasil diubah.');
		            exit(header("Location: ".$config['web']['base_url']."administrator/pages"));  
		        } else {
		            echo "Gagal Update";
		        }
		        
		   }
	}
    
}