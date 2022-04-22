<?php
require '../web.php';

$check_fitur = mysqli_query($db, "SELECT * FROM fitur_order WHERE status = 'PENDING'");
if(mysqli_num_rows($check_fitur) == 0){
    die("Tidak ada Orderan Fitur.");
} else {
    while($data_featured = mysqli_fetch_assoc($check_fitur)){
        
        $now = date('Y-m-d H:i:s');
        if (strtotime($now) >= strtotime($data_featured['expired'])){
        $update_featured = $db->query("UPDATE fitur_order SET status = 'NOT PAID' WHERE id = '".$data_featured['id']."' ");
        if($update_featured == true){
            echo " Berhasil update order fitur id #".$data_featured['id']."<br>";
        } else {
            echo "gagal update";
        }
        } 
        
    }
    
}