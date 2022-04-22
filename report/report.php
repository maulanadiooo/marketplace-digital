<?php
require '../web.php';
require '../lib/result.php';
require '../lib/is_login.php';

if (!isset($_GET['report']) && !isset($_GET['query'])) {
	exit("No direct script access allowed!3");
}
$report = mysqli_real_escape_string($db, $_GET['report']);
$id_report = mysqli_real_escape_string($db, $_GET['query']);
$check_message = $model->db_query($db, "*", "reply_message", "id = '$id_report'");
$user_penerima = $model->db_query($db, "*", "user", "id = '".$check_message['rows']['pengirim']."'");
if($check_message['rows']['penerima'] != $login['id']){
    exit("No direct script access allowed!!4");
}

   
if($report == 'message'){
    $db->query("UPDATE reply_message set report = '1' WHERE id = '$id_report'");
}

$input_post_report = array(
    'report' => $report,
	'id_report' => $id_report,
	'pelapor' => $login['id'],
	'created_at' => date("Y-m-d H:i:s"),
);
$input_report = $model->db_insert($db, "report", $input_post_report);

if($input_report == true && $report == 'message'){
    $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'Report Anda Telah Kami Terima, Terimakasih Telah Membantu Kami ^.^ ');
	exit(header("Location: ".$config['web']['base_url']."conversation/".$user_penerima['rows']['username']));
}
