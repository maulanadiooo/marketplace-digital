
		<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					<div class="search-bar flex-grow-1">
						<div class="position-relative search-bar-box">
							<input type="text" class="form-control search-control" placeholder="Type to search..."> <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
							<span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
						</div>
					</div>
					<div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center">
							<li class="nav-item mobile-search-icon">
								<a class="nav-link" href="#">	<i class='bx bx-search'></i>
								</a>
							</li>
							
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">0</span>
									<i class='bx bx-bell'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Notifications</p>
											<p class="msg-header-clear ms-auto">Marks all as read</p>
										</div>
									</a>
									<div class="header-notifications-list">
										<!--isi notif-->
									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">View All Notifications</div>
									</a>
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">0</span>
									<i class='bx bx-comment'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<!--isi pesan-->
									<a href="javascript:;">
										<div class="text-center msg-footer">View All Messages</div>
									</a>
								</div>
							</li>
						</ul>
					</div>
					<div class="user-box dropdown">
						<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img src="<?= $config['web']['base_url']."administrator/"; ?>assets/images/avatars/avatar-2.png" class="user-img" alt="user avatar">
							<div class="user-info ps-3">
							    <?
							    $user_header = $model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'"); 
							    ?>
								<p class="user-name mb-0"><?=$user_header['rows']['nama']?></p>
								<p class="designattion mb-0">Admin</p>
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="<?= $config['web']['base_url']; ?>user/<?=$user_header['rows']['username']?>" target="_blank"><i class="bx bx-user"></i><span>Profile</span></a>
							</li>
							<li><a class="dropdown-item" href="<?= $config['web']['base_url']; ?>setting/" target="_blank"><i class="bx bx-cog"></i><span>Settings</span></a>
							</li>
							<li>
								<div class="dropdown-divider mb-0"></div>
							</li>
							<li><a class="dropdown-item" href="<?= $config['web']['base_url']; ?>administrator/auth/sign-out.php"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<!--end header -->

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