<?php
require '../web.php';
require '../lib/check_session_admin.php';
$title = "Dashboard";

require 'lib/sidebar.php';
require 'lib/header.php';

$user_database = $model->db_query($db, "*", "user", "created_at DESC");
$saldo_tersedia = mysqli_query($db, "SELECT SUM(saldo_tersedia) AS total FROM `user` ");
$fetch_saldo_tersedia = mysqli_fetch_array($saldo_tersedia);
$total_saldo_tersedia = $fetch_saldo_tersedia['total']; 

$query_kliring = mysqli_query($db, "SELECT SUM(price_for_seller) AS total FROM `orders` WHERE status ='complete' AND kliring = '0'");
$fetch_kliring = mysqli_fetch_array($query_kliring);
$total_saldo_kliring = $fetch_kliring['total'];

$saldo_aktif = mysqli_query($db, "SELECT SUM(price_for_seller) AS total FROM `orders` WHERE status in ('active','success', 'cancel') AND kliring = '0'");
$fetch_saldo_aktif = mysqli_fetch_array($saldo_aktif);
$total_saldo_aktif = $fetch_saldo_aktif['total']; 

$saldo_withdraw = mysqli_query($db, "SELECT SUM(withdraw) AS total FROM `user`");
$fetch_saldo_withdraw = mysqli_fetch_array($saldo_withdraw);
$total_saldo_withdraw = $fetch_saldo_withdraw['total'];

$saldo_withdraw_menunggu = mysqli_query($db, "SELECT SUM(amount) AS total FROM `withdraw_request` WHERE status='pending' ");
$fetch_saldo_withdraw_menunggu = mysqli_fetch_array($saldo_withdraw_menunggu);
$total_saldo_withdraw_menunggu = $fetch_saldo_withdraw_menunggu['total'];

$penghasilan_fee_buyer = mysqli_query($db, "SELECT SUM(admin_fee) AS total FROM `penghasilan_admin`");
$fetch_penghasilan_fee_buyer = mysqli_fetch_array($penghasilan_fee_buyer);
$total_penghasilan_fee_buyer = $fetch_penghasilan_fee_buyer['total'];

$penghasilan_fee_seller = mysqli_query($db, "SELECT SUM(admin_fee_seller) AS total FROM `penghasilan_admin`");
$fetch_penghasilan_fee_seller = mysqli_fetch_array($penghasilan_fee_seller);
$total_penghasilan_fee_seller = $fetch_penghasilan_fee_seller['total'];

$data_service = mysqli_query($db, "SELECT * FROM services WHERE status = 'active' ORDER by created_at DESC LIMIT 10");
$data_orderan = mysqli_query($db, "SELECT * FROM orders WHERE status != 'unpaid' ORDER by created_at DESC LIMIT 10");

$data_service_terlaris = mysqli_query($db, "SELECT * FROM services ORDER by total_sales DESC LIMIT 10");

$orderans = mysqli_query($db, "SELECT * FROM orders ORDER BY id DESC");

$users = mysqli_query($db, "SELECT * FROM user ORDER BY created_at DESC");
$assoc_user = mysqli_fetch_assoc($users);
$aktif_user = mysqli_query($db, "SELECT * FROM user WHERE status = 'Verified' ORDER BY created_at DESC");
?>

		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4">
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-secondary">Saldo User</p>
										<h4 class="my-1">Rp <?= number_format($total_saldo_tersedia,0,',','.') ?></h4>
										<p class="mb-0 font-13 text-success"><?=mysqli_num_rows($aktif_user);?> User Aktif</p>
									</div>
									<div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-wallet'></i>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-secondary">Saldo Aktif</p>
										<h4 class="my-1">Rp <?= number_format($total_saldo_aktif,0,',','.') ?></h4>
										
									</div>
									<div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-wallet'></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="card radius-10">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-secondary">Saldo Kliring</p>
										<h4 class="my-1">Rp <?= number_format($total_saldo_kliring,0,',','.') ?></h4>
										
									</div>
									<div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-wallet'></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="card radius-10">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-secondary">Menunggu Withdraw</p>
										<h4 class="my-1">Rp <?= number_format($total_saldo_withdraw_menunggu,0,',','.') ?></h4>
										
									</div>
									<div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-wallet'></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4">
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-secondary">Telah Ditarik</p>
										<h4 class="my-1">Rp <?= number_format($total_saldo_withdraw,0,',','.') ?></h4>
									</div>
									<div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-wallet'></i>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-secondary">Fee Buyer</p>
										<h4 class="my-1">Rp <?= number_format($total_penghasilan_fee_buyer,0,',','.') ?></h4>
									</div>
									<div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-wallet'></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="card radius-10">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-secondary">Fee Seller</p>
										<h4 class="my-1">Rp <?= number_format($total_penghasilan_fee_seller,0,',','.') ?></h4>
										
									</div>
									<div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-wallet'></i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="card radius-10">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-secondary">Penghasilan</p>
										<h4 class="my-1">Rp <?= number_format($total_penghasilan_fee_buyer + $total_penghasilan_fee_seller,0,',','.') ?></h4>
										
									</div>
									<div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bxs-wallet'></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
				<div class="row row-cols-1 row-cols-xl-1">
					<div class="col d-flex">
						<div class="card radius-10 w-100">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<h5 class="mb-1">10 Top Products</h5>
										<!--<p class="mb-0 font-13 text-secondary"><i class='bx bxs-calendar'></i>in last 30 days revenue</p>-->
									</div>
									<div class="font-22 ms-auto"><a href="<?= $config['web']['base_url'] ?>administrator/services/" target="_blank"><i class='bx bx-dots-horizontal-rounded'></i></a>
									</div>
								</div>
							</div>
							<div class="product-list p-3 mb-3">
							    <?
                                while ($data_target_service_terlaris = mysqli_fetch_assoc($data_service_terlaris)){
                                    $category_terlaris = $model->db_query($db, "*", "categories", "id = '".$data_target_service_terlaris['categories_id']."'"); 
                                    $user_produk_terlaris = $model->db_query($db, "*", "user", "id = '".$data_target_service_terlaris['author']."'"); 
                                    $price_terlaris= mysqli_query($db, "SELECT SUM(total_price) AS total FROM `orders` WHERE service_id = '".$data_target_service_terlaris['id']."' AND status in ('success', 'complete') ");
                                    $fetch_service_terlaris = mysqli_fetch_array($price_terlaris);
                                    $total_price_terlaris = $fetch_service_terlaris['total'];
                                    $user_service = $model->db_query($db, "*", "user", "id = '".$data_target_service_terlaris['author']."'"); 
                                ?>
								<div class="row border mx-0 mb-3 py-2 radius-10 cursor-pointer">
									<div class="col-sm-6">
										<div class="d-flex align-items-center">
											<div class="product-img">
												<img src="<?= $config['web']['base_url'] ?>file-photo/<?= $data_target_service_terlaris['id'] ?>/<?= $data_target_service_terlaris['photo'] ?>" alt="<?=$data_target_service_terlaris['nama_layanan']?>" />
											</div>
											<div class="ms-2">
												<h6 class="mb-1"><a href="<?= $config['web']['base_url'] ?>product/<?=$data_target_service_terlaris['id']?>/<?=$data_target_service_terlaris['url']?>"target="_blank"><?=$data_target_service_terlaris['nama_layanan']?></a></h6>
												
												<p class="mb-0"><a href="<?= $config['web']['base_url'] ?>user/<?=$user_service['rows']['username']?>" target="_blank"><?= $user_service['rows']['username'] ?></a></p>
												<p class="mb-0">Rp <?= number_format($data_target_service_terlaris['price'],0,',','.') ?></p>
											</div>
										</div>
									</div>
									<div class="col-sm">
										<h6 class="mb-1">Rp <?= number_format($total_price_terlaris,0,',','.') ?></h6>
										<p class="mb-0"><?=$data_target_service_terlaris['total_sales']?> Penjualan</p>
									</div>
								</div>
								<?   
                                }
								?>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
				<div class="row">
					<div class="col-xl-12 d-flex">
						<div class="card radius-10 w-100">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<h5 class="mb-1">Transaction History</h5>
										
									</div>
									<div class="font-22 ms-auto"><a href="<?= $config['web']['base_url'] ?>administrator/orders/"  target="_blank"><i class='bx bx-dots-horizontal-rounded'></i></a>
									</div>
								</div>
								<div class="table-responsive mt-4">
									<table class="table align-middle mb-0 table-hover" id="Transaction-History">
										<thead class="table-light">
											<tr>
												<th>Nama User</th>
												<th>Tgl - Waktu</th>
												<th>Jumlah</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
										    <?
										    while ($orderan = mysqli_fetch_assoc($orderans)){
                                    
                                                $user = $model->db_query($db, "*", "user", "id = '".$orderan['buyer_id']."'"); 
                                                $service = $model->db_query($db, "*", "services", "id = '".$orderan['service_id']."'"); 
                                                $cart = $model->db_query($db, "*", "cart", "kode_unik = '".$orderan['kode_unik']."'"); 
                                               $bank = $model->db_query($db, "*", "bank_information", "id = '".$cart['rows']['pembayaran_id_bank']."'"); 
                                                
                                                if($orderan['status'] == 'unpaid'){
                                                    $status = 'Belum Dibayar';
                                                    $bg = 'info text-dark';
                                                } elseif($orderan['status'] == 'active'){
                                                    $status = 'Aktif';
                                                    $bg = 'success';
                                                } elseif($orderan['status'] == 'success'){
                                                    $status = 'Sukses';
                                                    $bg = 'success';
                                                } elseif($orderan['status'] == 'complete'){
                                                    $status = 'Selesai';
                                                    $bg = 'success';
                                                } elseif($orderan['status'] == 'cancel'){
                                                    $status = 'Ditolak Pembeli';
                                                    $bg = 'danger';
                                                } elseif($orderan['status'] == 'refund'){
                                                    $status = 'Pengajuan Refund';
                                                    $bg = 'danger';
                                                } elseif($orderan['status'] == 'refunded'){
                                                    $status = 'Sudah Direfund';
                                                    $bg = 'danger';
                                                } 
                                                
                                                if($cart['rows']['status'] != "success"){
                                                    $invoice = $cart['rows']['kode_invoice'];
                                                    $kode_status = "Invoice";
                                                } else {
                                                    $invoice = $orderan['id'];
                                                    $kode_status = "Order";
                                                }
                                            ?>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="">
														    <?php
                                                            if($user['rows']['photo'] == null){
                                                            ?>    
                                                             <img class="auth-img" src="<?= $config['web']['base_url'] ?>img/avatar.png" alt="<?= $user['rows']['username']; ?>" class="rounded-circle" width="46" height="46"">
                                                            <?  
                                                            } else {
                                                            ?>
                                                            <img class="auth-img" src="<?= $config['web']['base_url'] ?>user-photo/<?= $user['rows']['photo'] ?>" alt="<?= $user['rows']['username']; ?>" class="rounded-circle" width="46" height="46"">
                                                            <?
                                                            }
                                                            ?>
														</div>
														<div class="ms-2">
															<h6 class="mb-1 font-14">Orderan Dari <?=$user['rows']['username']?></h6>
															<p class="mb-0 font-13 text-secondary"><?=$kode_status?> ID #<?=$invoice ?></p>
														</div>
													</div>
												</td>
												<td><?= (substr($cart['rows']['created_at'], 0, -9)); ?></td></td>
												<td>Rp <?= number_format($cart['rows']['total_price'],0,',','.') ?></td>
												<td>
													<div class="badge rounded-pill bg-<?=$bg?> w-100"><?=$status?></div>
												</td>
											</tr>
										    <?
										    }
										    ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
				<!--end row-->
				<div class="row">
					<div class="col-12 col-xl-12 d-flex">
						<div class="card radius-10 w-100">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<h5 class="mb-0">New User</h5>
									</div>
								</div>
							</div>
							<div class="customers-list p-3 mb-3">
							    <?
								    while ($users_assoc = mysqli_fetch_assoc($users)){
								        
								    ?>  
								<div class="customers-list-item d-flex align-items-center border-top border-bottom p-2 cursor-pointer">
									<div class="">
									     <?php
                                        if($users_assoc['photo'] == null){
                                        ?>    
                                         <img class="auth-img" src="<?= $config['web']['base_url'] ?>img/avatar.png" alt="<?= $users_assoc['username']; ?>" class="rounded-circle" width="46" height="46"">
                                        <?  
                                        } else {
                                        ?>
                                        <img class="auth-img" src="<?= $config['web']['base_url'] ?>user-photo/<?= $users_assoc['photo'] ?>" alt="<?= $users_assoc['username']; ?>" class="rounded-circle" width="46" height="46"">
                                        <?
                                        }
                                        ?>
									</div>
									<div class="ms-2">
										<h6 class="mb-1 font-14"><?=$users_assoc['nama']?></h6>
										<p class="mb-0 font-13 text-secondary"><?=decrypt($users_assoc['email'])?></p>
									</div>
									<div class="list-inline d-flex customers-contacts ms-auto">	<a href="javascript:;" class="list-inline-item"><i class='bx bxs-envelope'></i></a>
										<a href="javascript:;" class="list-inline-item"><i class='bx bxs-microphone'></i></a>
										<a href="javascript:;" class="list-inline-item"><i class='bx bx-dots-vertical-rounded'></i></a>
									</div>
								</div>
								<?}?>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
				
			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
	
<?
require "lib/footer.php";
?>