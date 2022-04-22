<?php
require '../../web.php';
require '../../lib/check_session_admin.php';
require '../../lib/is_login.php';
$website = $model->db_query($db, "*", "website", "id = '1'");

if (!isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."sigin/"));
}
if ($model->db_query($db, "*", "user", "id = '".$_SESSION['login']."' AND role ='2' ")['count'] == 0) {
	exit(header("Location: ".$config['web']['base_url']));
}

if(!isset($_POST)){
    exit(header("Location: ".$config['web']['base_url']."administrator/orders/"));
} else {
    $data = array('oid', 'refund_amount','admin_fee');
    if (check_input($_POST, $data) == false) {
		$_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Sesuai.');
		exit(header("Location: ".$config['web']['base_url']."administrator/orders/"));
	} else { 
    	    $validation = array(
    			'oid' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['oid'])))), 
    			'refund_amount' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['refund_amount'])))),
    			'admin_fee' => $db->real_escape_string(trim(htmlspecialchars(htmlentities($_POST['admin_fee'])))),
    		);
    		if(check_empty($validation) == true){
    		    $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Input Tidak Boleh Kosong.');
    		    exit(header("Location: ".$config['web']['base_url']."administrator/orders/"));
    		} else {
    		    $data_orderan = $model->db_query($db, "*", "orders", "id = '".$validation['oid']."'");
    		    $id = $validation['oid'];
            $buyer_id = $data_orderan['rows']['buyer_id'];
            $seller_id = $data_orderan['rows']['seller_id'];
            $now = date("Y-m-d H:i:s");
            $kode_unik = $data_orderan['rows']['kode_unik'];
            $sent = 'setujurefund';
            $carts = $model->db_query($db, "*", "cart", "kode_unik = '$kode_unik'");
            if($validation['refund_amount'] > $carts['rows']['price_kode_unik']){
                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Jumlah Refund Lebih Besar Daripada Total Pembayaran' );
	            exit(header("Location: ".$config['web']['base_url']."administrator/orders/"));
            }
             
            $input_posts_refund = array(
            'user_id' => $seller_id,
            'orders_id' => $id,
            'message' => '[Dibatalkan Oleh Admin]',
            'message_status' => $sent,
            'created_at' => date('Y-m-d H:i:s')
            );
            $insert_refund = $model->db_insert($db, "conversation_order", $input_posts_refund);
                $updatea_refund = $model->db_update($db, "orders", array('status' => 'refunded'), "id = '$id'"); 
    	        if($insert_refund == true && $updatea_refund == true){
    	            $cart = $model->db_query($db, "*", "cart", "kode_unik = '$kode_unik'");
    	            $buyer = $model->db_query($db, "*", "user", "id = '$buyer_id'");
    	            $seller = $model->db_query($db, "*", "user", "id = '$seller_id'");
    	            $refund_amount = $validation['refund_amount'];
    	            
    	            
    	            if($data_orderan['rows']['kliring'] == '1' AND $data_orderan['rows']['status'] == 'complete' ){
    	                if($validation['admin_fee'] == 'ya'){
    	                    if($seller['rows']['saldo_tersedia'] < $data_orderan['rows']['price_for_seller']){
    	                       $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Saldo Penjual Kurang Dari Jumlah Yang Direfund!');
    		                   exit(header("Location: ".$config['web']['base_url']."administrator/orders/"));  
    	                    }
    	                    $update_balance_seller = $model->db_update($db, "user", array('saldo_tersedia' => $seller['rows']['saldo_tersedia'] - $data_orderan['rows']['price_for_seller']), "id = '$seller_id'");
    	                    $updatea_penghasilan_admin = $model->db_update($db, "penghasilan_admin", array('admin_fee_seller' => '0'), "order_id = '$id'");
    	                    $updatea_price_seller = $model->db_update($db, "orders", array('price_for_seller' => '0'), "id = '$id'");
    	                    
    	                } else {
    	                    $amount_refund_seller_sementara =  ($website['rows']['admin_fee_seller']/100) * $refund_amount;
    	                    $amount_refund_seller_fix = $refund_amount - $amount_refund_seller_sementara;
    	                    if($seller['rows']['saldo_tersedia'] < $amount_refund_seller_fix){
    	                       $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Saldo Penjual Kurang Dari Jumlah Yang Direfund!');
    		                   exit(header("Location: ".$config['web']['base_url']."administrator/orders/"));  
    	                    }
    	                    $total_price_sementara = $data_orderan['rows']['total_price'] - $refund_amount;
    	                    $admin_fee_seller = ($website['rows']['admin_fee_seller']/100) * $total_price_sementara;
    	                    $price_for_seller_fix = $total_price_sementara - $admin_fee_seller;
    	                    $updatea_price_seller = $model->db_update($db, "orders", array('price_for_seller' => $price_for_seller_fix), "id = '$id'");
    	                    
    	                    $update_balance_seller = $model->db_update($db, "user", array('saldo_tersedia' => $seller['rows']['saldo_tersedia'] - $amount_refund_seller_fix), "id = '$seller_id'");
    	                    $updatea_penghasilans = $model->db_update($db, "penghasilan_admin", array('admin_fee_seller' => $admin_fee_seller), "order_id = '$id'");
    	                }
    	            
    	            
                    } elseif($data_orderan['rows']['kliring'] == '0' AND $data_orderan['rows']['status'] == 'complete' ) {
                        $price_seller = $data_orderan['rows']['price_for_seller'];
                        $update_saldo_seller = $model->db_update($db, "user", array('saldo_tersedia' => $seller['rows']['saldo_tersedia'] + $price_seller), "id = '$seller_id'");
                        $model->db_update($db, "orders", array('kliring' => '1'), "id = '$id'");
                        $admin_fee_from_seller = ($website['rows']['admin_fee_seller']/100) * $data_orderan['rows']['total_price'];
                        $now = date("Y-m-d 23:59:59");
                        
                        $model->db_update($db, "penghasilan_admin", array('admin_fee_seller' => $admin_fee_from_seller), "order_id = '$id'");
                        $seller_setelah_update = $model->db_query($db, "*", "user", "id = '".$seller['rows']['id']."'");
                        
    		            
                        if($validation['admin_fee'] == 'ya'){
                            if($seller_setelah_update['rows']['saldo_tersedia'] < $data_orderan['rows']['price_for_seller']){
    	                       $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Saldo Penjual Kurang Dari Jumlah Yang Direfund!');
    		                   exit(header("Location: ".$config['web']['base_url']."administrator/orders/"));  
    	                    }
    	                    $update_balance_seller = $model->db_update($db, "user", array('saldo_tersedia' => $seller_setelah_update['rows']['saldo_tersedia'] - $data_orderan['rows']['price_for_seller']), "id = '".$seller_setelah_update['rows']['id']."'");
    	                    $updatea_penghasilan_admin = $model->db_update($db, "penghasilan_admin", array('admin_fee_seller' => '0'), "order_id = '$id'");
    	                    $updatea_price_seller = $model->db_update($db, "orders", array('price_for_seller' => '0'), "id = '$id'");
    	                    
    	                } else {
    	                    $amount_refund_seller_sementara =  ($website['rows']['admin_fee_seller']/100) * $refund_amount;
    	                    $amount_refund_seller_fix = $refund_amount - $amount_refund_seller_sementara;
    	                    if($seller_setelah_update['rows']['saldo_tersedia'] < $amount_refund_seller_fix){
    	                       $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'Saldo Penjual Kurang Dari Jumlah Yang Direfund!');
    		                   exit(header("Location: ".$config['web']['base_url']."administrator/orders/"));  
    	                    }
    	                     
    	                    $total_price_sementara = $data_orderan['rows']['total_price'] - $refund_amount;
    	                    $admin_fee_seller = ($website['rows']['admin_fee_seller']/100) * $total_price_sementara;
    	                    $price_for_seller_fix = $total_price_sementara - $admin_fee_seller;
    	                    $updatea_price_seller = $model->db_update($db, "orders", array('price_for_seller' => $price_for_seller_fix), "id = '$id'");
    	                    
    	                    $update_balance_seller = $model->db_update($db, "user", array('saldo_tersedia' => $seller_setelah_update['rows']['saldo_tersedia'] - $amount_refund_seller_fix), "id = '".$seller_setelah_update['rows']['id']."'");
    	                    $updatea_penghasilans = $model->db_update($db, "penghasilan_admin", array('admin_fee_seller' => $admin_fee_seller), "order_id = '$id'");
    	                }
                        
                        
                    }
    	            if($validation['admin_fee'] == 'ya'){
    	                $updatea_penghasilan = $model->db_update($db, "penghasilan_admin", array('admin_fee' => '0'), "order_id = '$id'");
    	                
    	            } 
    	            $updatea_refund_amount = $model->db_update($db, "orders", array('refund_amount' => $refund_amount), "id = '$id'"); 
    	            $updatea_balance = $model->db_update($db, "user", array('saldo_tersedia' => $buyer['rows']['saldo_tersedia'] + $refund_amount), "id = '$buyer_id'"); 
    	            if($updatea_balance == true && $updatea_refund_amount ==  true){
    	                 $_SESSION['result'] = array('alert' => 'success', 'title' => 'Berhasil!', 'msg' => 'ID '.$id.' Telah Berhasil di Refund Sebesar Rp '.number_format($refund_amount,0,',','.') );
    		            exit(header("Location: ".$config['web']['base_url']."administrator/orders/"));
    	            } else {
    	                $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'gagal refund code error: 01');
    		            exit(header("Location: ".$config['web']['base_url']."administrator/orders/"));
    	            }
    	            
    	            
    	        } else {
    	            $_SESSION['result'] = array('alert' => 'danger', 'title' => 'Gagal!', 'msg' => 'gagal refund code error: 02');
    		            exit(header("Location: ".$config['web']['base_url']."administrator/orders/"));
    	        }
            
		    
		}
	}
}