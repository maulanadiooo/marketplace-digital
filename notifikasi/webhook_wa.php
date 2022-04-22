<?php
require '../web.php';

// ------------------------------------------------------------------//
header('content-type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('whatsapp.txt', '[' . date('Y-m-d H:i:s') . "]\n" . json_encode($data) . "\n\n", FILE_APPEND);
$message = $data['message']; // ini menangkap pesan masuk
$from = $data['from']; // ini menangkap nomor pengirim pesan
$pisah = explode("@", $from);
$no_hp = encrypt($pisah[0]);
if (strtolower($message) == 'matikan notifikasi pesan') {
    $checknoHP = $model->db_query($db, "*", "user", "no_hp = '$no_hp'");
    if ($checknoHP['count'] == 1) {
        $update = $model->db_update($db, "user", array('terima_wa_pesan' => '0'), "id = '".$checknoHP['rows']['id']."'");
        if($update == true){
        $result = [
            'mode' => 'chat', // mode chat = chat biasa
            'pesan' => 'Hai '.$checknoHP['rows']['username'].'

Sayang sekali, Notifikasi pesan Whatsapp kamu telah berhasil di Non Aktifkan
Setelah ini kamu tidak akan menerima notifikasi whatsapp untuk pesan yang masuk kepada akunmu

Untuk mengatifkan kembali, silahkan ketik: 
*aktifkan notifikasi pesan*

Regards
GubukDigital.Net'
        ];    
        } else {
          $result = [
            'mode' => 'chat', // mode chat = chat biasa
            'pesan' => 'Mohon maaf sedang ada gangguan pada sistem kami sehingga perintah tidak dapat diproses, silahkan Non Aktifkan Pemberitahuan melalui pengaturan akunmu ya ^.^

Regards
GubukDigital.Net'
        ];  
        }
        
    } else {
       $result = [
            'mode' => 'chat', // mode chat = chat biasa
            'pesan' => 'Mohon Maaf, No Whatsapp kamu belum terdaftar pada database kami..
            
Regards
GubukDigital.Net'
        ]; 
    }
    
} elseif (strtolower($message) == 'aktifkan notifikasi pesan') {
    $checknoHP = $model->db_query($db, "*", "user", "no_hp = '$no_hp'");
    if ($checknoHP['count'] == 1) {
        $update = $model->db_update($db, "user", array('terima_wa_pesan' => '1'), "id = '".$checknoHP['rows']['id']."'");
        if($update == true){
        $result = [
            'mode' => 'chat', // mode chat = chat biasa
            'pesan' => 'Hai '.$checknoHP['rows']['username'].'

Notifikasi pesan Whatsapp kamu telah berhasil di Aktifkan ^.^

Untuk Non Aktifkan silahkan ketik: 
*matikan notifikasi pesan*

Regards
GubukDigital.Net'
        ];    
        } else {
          $result = [
            'mode' => 'chat', // mode chat = chat biasa
            'pesan' => 'Mohon maaf sedang ada gangguan pada sistem kami sehingga perintah tidak dapat diproses, silahkan Non Aktifkan Pemberitahuan melalui pengaturan akunmu ya ^.^

Regards
GubukDigital.Net'
        ];  
        }
        
    } else {
       $result = [
            'mode' => 'chat', // mode chat = chat biasa
            'pesan' => 'Mohon Maaf, No Whatsapp kamu belum terdaftar pada database kami..
            
Regards
GubukDigital.Net'
        ]; 
    }
    
} elseif (strtolower($message) == 'matikan notifikasi orderan') {
    $checknoHP = $model->db_query($db, "*", "user", "no_hp = '$no_hp'");
    if ($checknoHP['count'] == 1) {
        $update = $model->db_update($db, "user", array('terima_wa_orderan' => '0'), "id = '".$checknoHP['rows']['id']."'");
        if($update == true){
        $result = [
            'mode' => 'chat', // mode chat = chat biasa
            'pesan' => 'Hai '.$checknoHP['rows']['username'].'

Sayang sekali, Notifikasi Orderan kamu telah berhasil di Non Aktifkan
Setelah ini kamu tidak akan menerima notifikasi whatsapp untuk orderan yang masuk kepada akunmu

Untuk mengatifkan kembali, silahkan ketik: 
*aktifkan notifikasi orderan*

Regards
GubukDigital.Net'
        ];    
        } else {
          $result = [
            'mode' => 'chat', // mode chat = chat biasa
            'pesan' => 'Mohon maaf sedang ada gangguan pada sistem kami sehingga perintah tidak dapat diproses, silahkan Non Aktifkan Pemberitahuan melalui pengaturan akunmu ya ^.^

Regards
GubukDigital.Net'
        ];  
        }
        
    } else {
       $result = [
            'mode' => 'chat', // mode chat = chat biasa
            'pesan' => 'Mohon Maaf, No Whatsapp kamu belum terdaftar pada database kami..
            
Regards
GubukDigital.Net'
        ]; 
    }
    
} elseif (strtolower($message) == 'aktifkan notifikasi orderan') {
    $checknoHP = $model->db_query($db, "*", "user", "no_hp = '$no_hp'");
    if ($checknoHP['count'] == 1) {
        $update = $model->db_update($db, "user", array('terima_wa_orderan' => '1'), "id = '".$checknoHP['rows']['id']."'");
        if($update == true){
        $result = [
            'mode' => 'chat', // mode chat = chat biasa
            'pesan' => 'Hai '.$checknoHP['rows']['username'].'

Notifikasi orderan kamu telah berhasil di Aktifkan ^.^

Untuk Non Aktifkan silahkan ketik:
*matikan notifikasi orderan*

Regards
GubukDigital.Net'
        ];    
        } else {
          $result = [
            'mode' => 'chat', // mode chat = chat biasa
            'pesan' => 'Mohon maaf sedang ada gangguan pada sistem kami sehingga perintah tidak dapat diproses, silahkan Non Aktifkan Pemberitahuan melalui pengaturan akunmu ya ^.^

Regards
GubukDigital.Net'
        ];  
        }
        
    } else {
       $result = [
            'mode' => 'chat', // mode chat = chat biasa
            'pesan' => 'Mohon Maaf, No Whatsapp kamu belum terdaftar pada database kami..
            
Regards
GubukDigital.Net'
        ]; 
    }
    
}



// if (strtolower($message) == 'hai') {
//     $result = [
//         'mode' => 'chat', // mode chat = chat biasa
//         'pesan' => 'Hai juga'
//     ];
// } else if (strtolower($message) == 'hallo') {
//     $result = [
//         'mode' => 'reply', // mode reply = reply pessan
//         'pesan' => 'Halo juga'
//     ];
// } else if (strtolower($message) == 'gambar') {
//     $result = [
//         'mode' => 'picture', // type picture = kirim pesan gambar
//         'data' => [
//             'caption' => '*webhook picture*',
//             'url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZ2Ox4zgP799q86H56GbPMNWAdQQrfIWD-Mw&usqp=CAU'
//         ]
//     ];
// }

print json_encode($result);


// kami akan memberitahu jika update. :)