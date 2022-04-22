<?php
require '../web.php';

$check_fitur = mysqli_query($db, "SELECT * FROM services WHERE featured = '1'");
if(mysqli_num_rows($check_fitur) == 0){
    die("Tidak ada produk featured.");
} else {
    while($data_featured = mysqli_fetch_assoc($check_fitur)){
        
        $now = date('Y-m-d H:i:s');
        if (strtotime($now) >= strtotime($data_featured['expired_featured'])){
        $update_featured = $db->query("UPDATE services SET featured = '0' WHERE id = '".$data_featured['id']."' ");
        if($update_featured == true){
            echo $data_featured['nama_layanan']." Berhasil dihentikan fitur featurednya";
        } else {
            echo "gagal update";
        }
        } 
        
    }
    
}