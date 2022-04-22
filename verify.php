<?php
session_start();
require("web.php");


if (isset($_GET['token']) && isset($_GET['mail'])) {
    
    $get_token = mysqli_real_escape_string($db, trim(stripslashes(strip_tags(htmlspecialchars($_GET['token'])))));
    $get_mail = encrypt(mysqli_real_escape_string($db, trim(stripslashes(strip_tags(htmlspecialchars($_GET['mail']))))));
    $check_user = mysqli_query($db, "SELECT * FROM user WHERE email = '$get_mail' AND status = 'Not Verified'");
    $check_users = mysqli_query($db, "SELECT * FROM user WHERE email = '$get_mail' AND status = 'Verified'");
    
    $data_user = mysqli_fetch_assoc($check_user);

    $cek_kode = password_verify($get_token, $data_user['verifikasi']);
if(mysqli_num_rows($check_users) == true){
    $_SESSION['result'] = array('alert' => 'success', 'title' => 'Sudah Terverifikasi', 'msg' => 'Akun kamu Sudah Terverifikasi, Silahkan Login ^.^');
	exit(header("Location: ".$config['web']['base_url']."signin/")); 
} else { 
    if (password_verify($get_token, $data_user['verifikasi']) == true){
        $update_user = $db->query("UPDATE user SET status = 'Verified' WHERE email = '$get_mail'");
        $update_user = $db->query("UPDATE user SET verifikasi = '' WHERE email = '$get_mail'");
        $_SESSION['login'] = $data_user['id'];
        $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil', 'msg' => 'Akun Anda Telah Diverifikasi, Selamat Bergabung ^.^');
	    exit(header("Location: ".$config['web']['base_url'])); 
    } else {
        $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Akun Anda Tidak Berhasil DIverifikasi, Hubungi Admin support@gubukdigital.id ^.^');
	    exit(header("Location: ".$config['web']['base_url'])); 
    } 
}    
    
    
    
    
} else {
    exit(header("Location: ".$config['web']['base_url'])); 
}