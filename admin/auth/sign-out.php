<?php

require '../../web.php';
require '../../lib/is_login.php';


$update = $model->db_update($db, "user", array('token_login' => ''), "id = '".$login['id']."'");
if($update == true){
    unset($_SESSION['login']);
    unset($_COOKIE['username']);
	setcookie('username', NULL, -1);
	unset($_COOKIE['token_login']);
	setcookie('token_login', NULL, -1);
    exit(header("Location: ".$config['web']['base_url']."administrator/auth/signin/"));
} else {
    echo "gagal";
}
