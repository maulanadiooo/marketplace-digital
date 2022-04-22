<?php
require '../web.php';
    $content = file_get_contents("php://input");
    if($content){
        $token = '1906327901:AAGwGzRsnNuBnovw9VO_MtIrSpYlyrPrztI';
        
        $apiLink = "https://api.telegram.org/bot$token/";
        
        echo '<pre>content = '; print_r($content); echo '</pre>';
        $update = json_decode($content, true);
        file_put_contents('tele-isi.txt', '[' . date('Y-m-d H:i:s') . "]\n" . json_encode($update) . "\n\n", FILE_APPEND);
        if(!@$update["message"]) $val = $update['callback_query'];
        else $val = $update;
        
        $chat_id = $val['message']['chat']['id'];
        $text = $val['message']['text'];
        $update_id = $val['update_id'];
        $sender = $val['message']['from'];
        $isi_text = explode('.', $text);
        $clue = $isi_text[0];
        $email = explode(".", $text, 2);
        $email = $email[1];
        $check_telegrams = $model->db_query($db, "*", "telegram", "telegram_id = '".$sender['id']."' AND is_active = '1' ");
        $checkUserTele = $model->db_query($db, "*", "users", "id = '".$check_telegrams['rows']['user_id']."' AND telegram_id = '".$check_telegrams['rows']['id']."' ");
        if (strtolower($text) == '/start') {
            $teks = 'Hallo '.$sender['username'].'
Selamat datang di bot <a href="https://gubukdigital.net/">Gubuk Digital</a>
ID Telegram anda: <strong><i><code>'.$sender['id'].'</code></i></strong>
Silahkan kembali ke <a href="https://gubukdigital.net/setting/">settingan</a> akun anda
Dan masukkan ID Telegram di atas lalu aktifkan ^.^

Regards
Gubuk Digital';
$tekss = urlencode($teks);
            file_get_contents($apiLink . "sendmessage?chat_id=$chat_id&text=$tekss&parse_mode=HTML&disable_web_page_preview=true");
            exit();
        } else {
            $teks = 'Bot Kami Hanya Mengerti Perintah <code>/start</code> untuk mengetahui ID Telegram anda ^.^

Regards
Gubuk Digital';
$tekss = urlencode($teks);
            file_get_contents($apiLink . "sendmessage?chat_id=$chat_id&text=$tekss&parse_mode=HTML&disable_web_page_preview=true");
            exit();
        }
        
        echo 'Response sent.<br /><br />';
    } else echo 'Only telegram can access this url.';
    ?>