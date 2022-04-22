<!-- Sidebar -->
		<div class="sidebar">
			
			<div class="sidebar-background"></div>
			<div class="sidebar-wrapper scrollbar-inner">
				<div class="sidebar-content">
					<div class="user">
						<div class="avatar-sm float-left mr-2">
							<img src="<?= $config['web']['base_url']."administrator/"; ?>assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle">
						</div>
						<div class="info">
							<a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
								<span>
								    <?
    							    $user_header = $model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'"); 
    							    ?>
									<?=$user_header['rows']['nama']?>
									<span class="user-level">Administrator</span>
								</span>
							</a>
							<div class="clearfix"></div>

							
						</div>
					</div>
					<ul class="nav">
						<li class="nav-item ">
							<a href="<?= $config['web']['base_url']; ?>administrator">
								<i class="fas fa-home"></i>
								<p>Dashboard</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= $config['web']['base_url']; ?>administrator/users/">
								<i class="fas fa-user-circle"></i>
								<p>Users</p>
							</a>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#settings">
								<i class="far fa-sun"></i>
								<p>Settings</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="settings">
								<ul class="nav nav-collapse">
									<li>
										<a href="<?= $config['web']['base_url']; ?>administrator/settings/">
											<span class="sub-item">Website</span>
										</a>
									</li>
									<li>
										<a href="<?= $config['web']['base_url']; ?>administrator/pages/">
											<span class="sub-item">Pages</span>
										</a>
									</li>
									<li>
										<a href="<?= $config['web']['base_url']; ?>administrator/category/">
											<span class="sub-item">Category</span>
										</a>
									</li>
									<li>
										<a href="<?= $config['web']['base_url']; ?>administrator/bank-pembayaran/">
											<span class="sub-item">Bank Pembayaran</span>
										</a>
									</li>
									<li>
										<a href="<?= $config['web']['base_url']; ?>administrator/bank-withdraws/">
											<span class="sub-item">Bank Withdraw</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#payment">
								<i class="fas fa-dollar-sign"></i>
								<p>Payment</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="payment">
								<ul class="nav nav-collapse">
									<li>
										<a href="<?= $config['web']['base_url']; ?>administrator/payment-auto/">
											<span class="sub-item">Automatic Payment</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item ">
							<a href="<?= $config['web']['base_url']; ?>administrator/mail-template/">
								<i class="fas fa-envelope"></i>
								<p>Mail Template</p>
							</a>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#settingss">
								<i class="fas fa-align-justify"></i>
								<?
							    $total_notif_product = $service_pending['count'] + $req_pending['count'];
							    if($total_notif_product > 0){
							    ?>   
							    <p>Product  <span class="badge badge-danger"><?=$total_notif_product?></span></p>
                                <?
                                } else {
                                ?>
                                <p>Product</p>
                                <?
                                }
                                ?> 
								<span class="caret"></span>
							</a>
							<div class="collapse" id="settingss">
								<ul class="nav nav-collapse">
									<li>
										<a href="<?= $config['web']['base_url']; ?>administrator/services/">
										    <span class="sub-item">All Product</span>
                                        </a>
									</li>
									<li>
										<a href="<?= $config['web']['base_url']; ?>administrator/services-pending/">
										    <?
                                            if($service_pending['count'] > 0){
                                            ?>
                                            <span class="sub-item">Product Pending <span class="badge badge-danger"><?=$service_pending['count']?></span></span>
                                            <?
                                            } else {
                                            ?>
                                            <span class="sub-item">Product Pending</span>
                                            <?
                                            }
                                            ?>
											
											
										</a>
									</li>
									<li>
										<a href="<?= $config['web']['base_url']; ?>administrator/request/">
											<span class="sub-item">All Request</span>
										</a>
									</li>
									<li>
										<a href="<?= $config['web']['base_url']; ?>administrator/request-pending/">
										    <?
                                            if($req_pending['count'] > 0){
                                            ?> 
                                            <span class="sub-item">Request Pending <span class="badge badge-danger"><?=$service_pending['count']?></span></span>
                                            <?
                                            } else {
                                            ?>
                                            <span class="sub-item">Request Pending</span>
                                            <?
                                            }
                                            ?>
											
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#settingsss">
								<i class="fas fa-money-bill-wave"></i>
								<?
							    $total_notif_wd = $wd_pending['count'];
							    if($total_notif_wd > 0){
							    ?>   
							    <p>Withdraw  <span class="badge badge-danger"><?=$total_notif_wd?></span></p>
                                <?
                                } else {
                                ?>
                                <p>Withdraw</p>
                                <?
                                }
                                ?> 
								<span class="caret"></span>
							</a>
							<div class="collapse" id="settingsss">
								<ul class="nav nav-collapse">
									<li>
										<a href="<?= $config['web']['base_url']; ?>administrator/withdraw-request/">
										    <?   
                                            if($wd_pending['count'] > 0){
                                            ?> 
                                            <span class="sub-item">Request <span class="badge badge-danger"><?=$wd_pending['count']?></span></span>
                                            <?
                                            } else {
                                            ?>
                                            <span class="sub-item">Request</span>
                                            <?
                                            }
                                            ?>
											
										</a>
									</li>
									<li>
										<a href="<?= $config['web']['base_url']; ?>administrator/withdraw-history/">
											<span class="sub-item">History</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item ">
							<a href="<?= $config['web']['base_url']; ?>administrator/orders/">
								<i class="fas fa-cart-arrow-down"></i>
								<p>Orders</p>
							</a>
						</li>
						<li class="nav-item ">
							<a href="<?= $config['web']['base_url']; ?>administrator/deposit-history/">
								<i class="fas fa-money-check-alt"></i>
								<p>Deposit</p>
							</a>
						</li>
						<li class="nav-item ">
							<a href="<?= $config['web']['base_url']; ?>administrator/user-chats/">
								<i class="fab fa-rocketchat"></i>
								<p>Chat User</p>
							</a>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#settingsssss">
								<i class="fas fa-align-justify"></i>
								<p>Logs</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="settingsssss">
								<ul class="nav nav-collapse">
									<li>
										<a href="<?= $config['web']['base_url']; ?>administrator/log-pembayaran/">
											<span class="sub-item">Log Pembayaran</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#settingssss">
								<i class="fas fa-align-justify"></i>
								<p>Report</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="settingssss">
								<ul class="nav nav-collapse">
									<li>
										<a href="<?= $config['web']['base_url']; ?>administrator/report-chat/">
											<span class="sub-item">Chat</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Sidebar -->
<?php
if (isset($_SESSION['result'])) {
?>
					
                    <?php
                    if ($_SESSION['result']['alert'] == "danger") {
                    ?>
                    <script>
                        Swal.fire({
                          position: 'top-end',
                          icon: 'error',
                          title: '<?php echo $_SESSION['result']['title'] ?>',
                          html: "<?php echo $_SESSION['result']['msg'] ?>",
                          showConfirmButton: false,
                          timer: 2500
                        })
                    </script>
                    <?php
                    } else {
                    ?>
                    <script>
                        Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: '<?php echo $_SESSION['result']['title'] ?>',
                          html: "<?php echo $_SESSION['result']['msg'] ?>",
                          showConfirmButton: false,
                          timer: 2500
                        })
                    </script>
                    <?php
                    }
                    ?>
<?php
unset($_SESSION['result']);
}
?>