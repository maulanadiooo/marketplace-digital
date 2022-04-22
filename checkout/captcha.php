<?php
require '../web.php';
function acakCaptcha() {
    $alphabet = "0123456789";
   
//untuk menyatakan $pass sebagai array
$pass = array(); 
 
   //masukkan -2 dalam string length
    $panjangAlpha = strlen($alphabet) - 2; 
    for ($i = 0; $i < 5; $i++) {
        $n = rand(0, $panjangAlpha);
        $pass[] = $alphabet[$n];
    }
 
   //ubah array menjadi string
    return implode($pass); 
}
 
 // untuk mengacak captcha
$code = acakCaptcha();
$_SESSION["code"] = $code;
 
//lebar dan tinggi captcha
$wh = imagecreatetruecolor(250, 80);
 
//background color biru
$bgc = imagecolorallocate($wh, 0, 0, 0);
 
//text color abu-abu
$fc = imagecolorallocate($wh, 255, 255, 255);
imagefill($wh, 0, 0, $bgc);
 
//( $image , $fontsize , $string , $fontcolor )
imagestring($wh, 10, 100, 30,  $code, $fc);
 
//buat gambar
header('content-type: image/jpg');
imagejpeg($wh);
imagedestroy($wh);
?>