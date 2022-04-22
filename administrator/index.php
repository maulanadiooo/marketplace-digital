<?php
require '../web.php';
require '../lib/check_session_admin.php';
$title = "Dashboard";

require 'lib/header.php';
require 'lib/sidebar.php';


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

$data_service_terlaris = mysqli_query($db, "SELECT * FROM services ORDER by total_sales DESC LIMIT 5");

$orderans = mysqli_query($db, "SELECT * FROM orders ORDER BY id DESC");

$users = mysqli_query($db, "SELECT * FROM user ORDER BY created_at DESC LIMIT 5");
$assoc_user = mysqli_fetch_assoc($users);
$aktif_user = mysqli_query($db, "SELECT * FROM user WHERE status = 'Verified' ORDER BY created_at DESC");
?>
        <div class="main-panel">
			<div class="content">
				<div class="page-inner">
					<div class="page-header">
						<h4 class="page-title">Dashboard</h4>
					</div>
					<div class="row">
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body ">
									<div class="row align-items-center">
										<div class="col-icon">
											<div class="icon-big text-center icon-primary bubble-shadow-small">
												<i class="fas fa-users"></i>
											</div>
										</div>
										<div class="col col-stats ml-3 ml-sm-0">
											<div class="numbers">
												<p class="card-category">User Aktif</p>
												<h4 class="card-title"><?=mysqli_num_rows($aktif_user);?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row align-items-center">
										<div class="col-icon">
											<div class="icon-big text-center icon-info bubble-shadow-small">
												<i class="far fa-newspaper"></i>
											</div>
										</div>
										<div class="col col-stats ml-3 ml-sm-0">
											<div class="numbers">
												<p class="card-category">Saldo user</p>
												<h4 class="card-title">Rp <?= number_format($total_saldo_tersedia,0,',','.') ?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row align-items-center">
										<div class="col-icon">
											<div class="icon-big text-center icon-success bubble-shadow-small">
												<i class="far fa-chart-bar"></i>
											</div>
										</div>
										<div class="col col-stats ml-3 ml-sm-0">
											<div class="numbers">
												<p class="card-category">Saldo Aktif</p>
												<h4 class="card-title">Rp <?= number_format($total_saldo_aktif,0,',','.') ?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row align-items-center">
										<div class="col-icon">
											<div class="icon-big text-center icon-secondary bubble-shadow-small">
												<i class="far fa-check-circle"></i>
											</div>
										</div>
										<div class="col col-stats ml-3 ml-sm-0">
											<div class="numbers">
												<p class="card-category">Saldo Kliring</p>
												<h4 class="card-title">Rp <?= number_format($total_saldo_kliring,0,',','.') ?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-md-4">
							<div class="card card-stats card-round">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="flaticon-chart-pie text-warning"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Menunggu Withdraw</p>
												<h4 class="card-title">Rp <?= number_format($total_saldo_withdraw_menunggu,0,',','.') ?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-4">
							<div class="card card-stats card-round">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="flaticon-coins text-success"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Telah Ditarik</p>
												<h4 class="card-title">Rp <?= number_format($total_saldo_withdraw,0,',','.') ?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-4">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="flaticon-coins text-success"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Total Penghasilan</p>
												<h4 class="card-title">Rp <?= number_format($total_penghasilan_fee_buyer + $total_penghasilan_fee_seller,0,',','.') ?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="card-title">5 Top Products</div>
								</div>
								<div class="card-body pb-0">
								    <?
                                    while ($data_target_service_terlaris = mysqli_fetch_assoc($data_service_terlaris)){
                                        $category_terlaris = $model->db_query($db, "*", "categories", "id = '".$data_target_service_terlaris['categories_id']."'"); 
                                        $user_produk_terlaris = $model->db_query($db, "*", "user", "id = '".$data_target_service_terlaris['author']."'"); 
                                        $price_terlaris= mysqli_query($db, "SELECT SUM(total_price) AS total FROM `orders` WHERE service_id = '".$data_target_service_terlaris['id']."' AND status in ('success', 'complete') ");
                                        $fetch_service_terlaris = mysqli_fetch_array($price_terlaris);
                                        $total_price_terlaris = $fetch_service_terlaris['total'];
                                        $user_service = $model->db_query($db, "*", "user", "id = '".$data_target_service_terlaris['author']."'"); 
                                    ?>
									<div class="d-flex">
										<div class="avatar">
											<img src="<?= $config['web']['base_url'] ?>file-photo/<?= $data_target_service_terlaris['id'] ?>/<?= $data_target_service_terlaris['photo'] ?>" alt="<?=$data_target_service_terlaris['nama_layanan']?>" class="avatar-img rounded-circle">
										</div>
										<div class="flex-1 pt-1 ml-2">
											<h5 class="fw-bold mb-1"><a href="<?= $config['web']['base_url'] ?>product/<?=$data_target_service_terlaris['id']?>/<?=$data_target_service_terlaris['url']?>"target="_blank"><?=$data_target_service_terlaris['nama_layanan']?></a></h5>
											<small class="text-muted"><?=$data_target_service_terlaris['total_sales']?> Penjualan</small>
										</div>
										<div class="d-flex ml-auto align-items-center">
											<h3 class="text-info fw-bold">Rp <?= number_format($data_target_service_terlaris['price'],0,',','.') ?></h3>
										</div>
									</div>
									<div class="separator-dashed"></div>
									<?
									}
									?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
					<div class="col-md-12">
							<div class="card">
								<div class="card-body">
									<div class="card-title fw-mediumbold">5 User Baru</div>
									<div class="card-list">
									    <?
    								    while ($users_assoc = mysqli_fetch_assoc($users)){
    								        
    								    ?>
										<div class="item-list">
											<div class="avatar">
											    <?php
                                                if($users_assoc['photo'] == null){
                                                ?>
                                                <img src="<?= $config['web']['base_url'] ?>img/avatar.png" alt="<?= $users_assoc['username']; ?>" class="avatar-img rounded-circle">
                                                <?
                                                } else {
                                                ?>
                                                <img src="<?= $config['web']['base_url'] ?>user-photo/<?= $users_assoc['photo'] ?>" alt="<?= $users_assoc['username']; ?>" class="avatar-img rounded-circle">
                                                <?
                                                }
                                                ?>
												
											</div>
											<div class="info-user ml-3">
												<div class="username"><a href="<?= $config['web']['base_url'] ?>user/<?=$users_assoc['username']?>" target="_blank"><?=$users_assoc['nama']?></a></div>
												<div class="status"><?=decrypt($users_assoc['email'])?></div>
											</div>
										</div>
										<?
    								    }
    								    ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
<?
require 'lib/footer.php';
?>