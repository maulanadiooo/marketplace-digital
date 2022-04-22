<?php
require("../web.php");



$check_re= mysqli_query($db, "SELECT * FROM redirect");
if (mysqli_num_rows($check_re) == 0) {
	die("Redirect Kosong.");
} else {
    while($data_re= mysqli_fetch_assoc($check_re)){
    $expired = $data_re['expired_at'];
    $now = date("Y-m-d H:i:s");
    $token = $data_re['token'];
    
    if (strtotime($now) > strtotime($expired)){
        $delete = $model->db_delete($db, "redirect", "token = '$token'");
    }
  }
 }
?>