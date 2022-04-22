<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;

function format_date($date) {
	$split = explode("-", $date);
	$month = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des');
	if (in_array($split[1], array_keys($month)) == false) {
		return "Something went wrong.";
	}
	return $split[2].' '.$month[$split[1]].' '.$split[0]; 
}

function check_input($input, $data) { 
	$input = array_keys($input);
	$false = 0;
	foreach ($data as $key) {
		if (in_array($key, $input) == false) { 
			$false++;
		}
	}
	if ($false == 0) {
		return true;
	} else {
		return false;
	}
}

function check_empty($input) {
	$result = true;
	foreach ($input as $key => $value) {
		$result = false;
		if (empty($value) == true) {
			$result = true;
			break;
		}
	}
	return $result;
}

function str_rand($length = 10) {
	return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

function get_client_ip() {
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP')) {
		$ipaddress = getenv('HTTP_CLIENT_IP');
	} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	} elseif (getenv('HTTP_X_FORWARDED')) {
		$ipaddress = getenv('HTTP_X_FORWARDED');
	} elseif (getenv('HTTP_FORWARDED_FOR')) {
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	} elseif (getenv('HTTP_FORWARDED')) {
		$ipaddress = getenv('HTTP_FORWARDED');
	} elseif (getenv('REMOTE_ADDR')) {
		$ipaddress = getenv('REMOTE_ADDR');
	} else {
		$ipaddress = 'UNKNOWN';
	}
	return $ipaddress;
}

function post_curl($end_point, $post) {
	$_post = array();
	if (is_array($post)) {
		foreach ($post as $name => $value) {
			$_post[] = $name.'='.urlencode($value);
		}
	}
	$ch = curl_init($end_point);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	if (is_array($post)) {
		curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
	}
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	$result = curl_exec($ch);
	if (curl_errno($ch) != 0 && empty($result)) {
		$result = false;
	}
	curl_close($ch);
	return $result;
}

function validate_date($date) {
	$d = DateTime::createFromFormat('Y-m-d', $date);
	return $d && $d->format('Y-m-d') == $date;
}

function acak_nomor($length) {
	$str = "";
	$karakter = array_merge(range('0','9'));
	$max_karakter = count($karakter) - 1;
	for ($i = 0; $i < $length; $i++) {
		$rand = mt_rand(0, $max_karakter);
		$str .= $karakter[$rand];
	}
	return $str;
}
function hp($nohp) {
                    
                    
    // cek apakah no hp mengandung karakter + dan 0-9
    if(!preg_match('/[^+0-9]/',trim($nohp))){
        // cek apakah no hp karakter 1-2 adalah 62
        if(substr(trim($nohp), 0, 2)=='62'){
            $hp = trim($nohp);
        }
        // cek apakah no hp karakter 1 adalah 0
        elseif(substr(trim($nohp), 0, 1)=='0'){
            $hp = '62'.substr(trim($nohp), 1);
        } 
        // cek apakah no hp karakter 1-3 adalah +62
        elseif(substr(trim($nohp), 0, 3)=='+62'){
            $hp = '62'.substr(trim($nohp), 3);
        }else {
            $hp = trim($nohp);
        }
    }
    return $hp;
}

function rating($rate){
    if($rate == '5'){
    echo '<span><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i></span>';
    }elseif($rate >4.49 && $rate <5 ){
    echo '<span><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star-half-o" style="color:#ffed21"></i></span>';
    }elseif($rate >= 4 && $rate <= 4.49){
    echo '<span><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star-o"></i></span>';
    } elseif($rate >3.49 && $rate < 4 ){
    echo '<span><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star-half-o" style="color:#ffed21"></i><i class="fa fa-star-o"></i></span>';  
    }elseif($rate >= 3 && $rate <= 3.49){
    echo '<span><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></span>';    
    }elseif($rate > 2.49 && $rate < 3){
    echo '<span><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star-half-o" style="color:#ffed21"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></span>';
    }elseif($rate >= 2 && $rate <= 2.49){
    echo '<span><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></span>';
    } elseif($rate > 1.49 && $rate < 2){
    echo '<span><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star-half-o" style="color:#ffed21"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></span>';
    }elseif($rate == '1'){
     echo '<span><i class="fa fa-star" style="color:#ffed21"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></span>';   
    } else {
     echo '<span><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></span>';
    }
}

function encrypt($data){
    $data = $data;
	$method="aes-128-ofb";
	$key ="maulanadioo040296";
	$option=0;
	//asal saja hehehe 
	//namun pajangnya sesuiai method cipher 
	//cek dengan openssl_cipher_iv_length($method);
	$iv="0402199627042019";
	$dataTerenkripsi=openssl_encrypt($data, $method, $key, $option, $iv);
	return $dataTerenkripsi;
}
function decrypt($data){
    $dataTerenkripsi = $data;
	$method="aes-128-ofb";
	$key ="maulanadioo040296";
	$option=0;
	//asal saja hehehe 
	//namun pajangnya sesuiai method cipher 
	//cek dengan openssl_cipher_iv_length($method);
	$iv="0402199627042019";
	$dataDecrypt=openssl_decrypt($dataTerenkripsi, $method, $key, $option, $iv);
	return $dataDecrypt;
}

function kirim_email($ke, $nama, $register_mail, $subject){
    include '../vendor/autoload.php';
    
    
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    
    // Load Composer's autoloader
    
    // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);
    
        //Server settings
        $mail->SMTPDebug = 0;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = '';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = '';                     // SMTP username
        $mail->Password   = '';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    
        //Recipients
        $mail->setFrom('', '');
        $mail->addAddress($ke, $nama);     // Add a recipient
        $mail->addAddress($ke);               // Name is optional
    
        
    
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $register_mail;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->send();

}

function kirim_sms($nohp, $message){
    include '../smsgatewayme/autoload.php';
    
    // Configure client
    $config = Configuration::getDefaultConfiguration();
    $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTYxMTU4NDgwNSwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjg2ODcxLCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.Ox9OkIAn1iJvDckgjs7ygL6kxxbSj-IBHJiXQKU7wuM');
    $apiClient = new ApiClient($config);
    $messageClient = new MessageApi($apiClient);
    
    // Sending a SMS Message
    $sendMessageRequest1 = new SendMessageRequest([
        'phoneNumber' => '+'.$nohp,
        'message' => $message,
        'deviceId' => 122635
    ]);
    $sendMessages = $messageClient->sendMessages([
        $sendMessageRequest1
    ]);
    return $sendMessages;
}

function terima_sms(){
    include '../smsgatewayme/autoload.php';
    
    // Configure client
    $config = Configuration::getDefaultConfiguration();
    $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTYxMTU4NDgwNSwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjg2ODcxLCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.Ox9OkIAn1iJvDckgjs7ygL6kxxbSj-IBHJiXQKU7wuM');
    $apiClient = new ApiClient($config);
    $messageClient = new MessageApi($apiClient);
    
    // Get SMS Message Information
    $message = $messageClient->getMessage(122635);
    return $message;

}

function temp_mail($email){
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.testmail.top/domain/check?data='.$email,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 3,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer TOKENTOKENTOKEN' // Bearer didapatkan pada dashboard https://testmail.top/
      ),
      
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    $result = json_decode($response, true);
    if($result ['error'] == 0 && $result['result'] == true){
    return false;   
    } else {
    return true;     
    }
    
}


function kirim_wa($number, $messages){


    // Send Message
    $my_apikey = "TOKENTOKEN"; // apikey rapiwha.com
    $destination = $number;
    $message = $messages;
    $api_url = "http://panel.rapiwha.com/send_message.php";
    $api_url .= "?apikey=". urlencode ($my_apikey);
    $api_url .= "&number=". urlencode ($destination);
    $api_url .= "&text=". urlencode ($message);
    $my_result_object = json_decode(file_get_contents($api_url, false));
    return $my_result_object->success;
}
function kirim_tele($tekss, $chat_id ){
$token = 'TOKEN';
        
$apiLink = "https://api.telegram.org/bot$token/";
file_get_contents($apiLink . "sendmessage?chat_id=$chat_id&text=$tekss&parse_mode=HTML&disable_web_page_preview=true");
}