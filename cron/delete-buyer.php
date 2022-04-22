<?php
require("../web.php");



$check_re= mysqli_query($db, "SELECT * FROM cart WHERE buyer_id = '0' AND status = 'cancel' ");
if (mysqli_num_rows($check_re) == 0) {
	die("Cart kosong.");
} else {
    while($data_re= mysqli_fetch_assoc($check_re)){
    
        $delete = $model->db_delete($db, "cart", "id = '".$data_re['id']."'");
         $delete = $model->db_delete($db, "orders", "kode_unik = '".$data_re['kode_unik']."'");
         if($delete == true){
             echo "berhasil <br>";
         } else {
             echo "gagal";
         }

  }
 }
?>