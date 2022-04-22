<?
$website = $model->db_query($db, "*", "website", "id = '1'");
if(isset($_COOKIE['token_login'])){
    
    $cookie_token = $_COOKIE['token_login'];
    $lgid = $_COOKIE['lgid'];
    $check_user = $model->db_query($db, "*", "user", "token_login = '$cookie_token'");
    if ($check_user['count'] == 1) {
        
        $_SESSION['login'] = $check_user['rows']['id'];

    } else {
		unset($_COOKIE['token_login']);
	    setcookie('token_login', NULL, -1);
	}
     
}

$now = date("Y-m-d 23:59:59");
$service_pending = $model->db_query($db, "*", "services", "status = 'pending' ");
$req_pending = $model->db_query($db, "*", "permintaan_pembeli", "status = 'pending' ");
$wd_pending = $model->db_query($db, "*", "withdraw_request", "status = 'pending' AND estimasi_wd <= '$now' ");
$depo_pending = $model->db_query($db, "*", "deposit", "status = 'pending' ");
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?= $title;?> - <?=$website['rows']['title']?></title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="<?= $config['web']['base_url']; ?>file-photo/website/<?=$website['rows']['fav_icon']?>" type="image/x-icon"/>

	<!-- Fonts and icons -->
	<script src="<?= $config['web']['base_url']."administrator/"; ?>assets/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Open+Sans:300,400,600,700"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands"], urls: ['<?= $config['web']['base_url']."administrator/"; ?>assets/css/fonts.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>
    <!--sweetalert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
	<!-- CSS Files -->
	<link rel="stylesheet" href="<?= $config['web']['base_url']."administrator/"; ?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= $config['web']['base_url']."administrator/"; ?>assets/css/azzara.min.css">

</head>
<body>
	<div class="wrapper">
		<!--
			Tip 1: You can change the background color of the main header using: data-background-color="blue | purple | light-blue | green | orange | red"
		-->
		<div class="main-header" data-background-color="purple">
			<!-- Logo Header -->
			<div class="logo-header">
				
				<a href="<?= $config['web']['base_url']."administrator/"; ?>" class="logo">
					<img src="<?= $config['web']['base_url']; ?>file-photo/website/<?=$website['rows']['logo_web']?>" width="160px" height="35px" alt="navbar brand" class="navbar-brand">
				</a>
				<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon">
						<i class="fa fa-bars"></i>
					</span>
				</button>
				<button class="topbar-toggler more"><i class="fa fa-ellipsis-v"></i></button>
				<div class="navbar-minimize">
					<button class="btn btn-minimize btn-rounded">
						<i class="fa fa-bars"></i>
					</button>
				</div>
			</div>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<nav class="navbar navbar-header navbar-expand-lg">
				
				<div class="container-fluid">
					<div class="collapse" id="search-nav">
						<form class="navbar-left navbar-form nav-search mr-md-3">
							<div class="input-group">
								<div class="input-group-prepend">
									<button type="submit" class="btn btn-search pr-1">
										<i class="fa fa-search search-icon"></i>
									</button>
								</div>
								<input type="text" placeholder="Search ..." class="form-control">
							</div>
						</form>
					</div>
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<li class="nav-item toggle-nav-search hidden-caret">
							<a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
								<i class="fa fa-search"></i>
							</a>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-envelope"></i>
								<span class="notification">0</span>
							</a>
							<ul class="dropdown-menu messages-notif-box animated fadeIn" aria-labelledby="messageDropdown">
								<li>
									<div class="dropdown-title d-flex justify-content-between align-items-center">
										Messages 									
										<a href="#" class="small">Mark all as read</a>
									</div>
								</li>
								<li>
									<div class="message-notif-scroll scrollbar-outer">
										<div class="notif-center">
											
										</div>
									</div>
								</li>
								<li>
									<a class="see-all" href="javascript:void(0);">See all messages<i class="fa fa-angle-right"></i> </a>
								</li>
							</ul>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa fa-bell"></i>
								<span class="notification">0</span>
							</a>
							<ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
								<li>
									<div class="dropdown-title">You have 0 new notification</div>
								</li>
								<li>
									<div class="notif-scroll scrollbar-outer">
										<div class="notif-center">
											
										</div>
									</div>
								</li>
								<li>
									<a class="see-all" href="javascript:void(0);">See all notifications<i class="fa fa-angle-right"></i> </a>
								</li>
							</ul>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
								<div class="avatar-sm">
									<img src="<?= $config['web']['base_url']."administrator/"; ?>assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle">
								</div>
							</a>
							<ul class="dropdown-menu dropdown-user animated fadeIn">
								<li>
									<div class="user-box">
										<div class="avatar-lg"><img src="<?= $config['web']['base_url']."administrator/"; ?>assets/img/profile.jpg" alt="image profile" class="avatar-img rounded"></div>
										<div class="u-text">
											<h4>Hizrian</h4>
											<p class="text-muted">hello@example.com</p><a href="profile.html" class="btn btn-rounded btn-danger btn-sm">View Profile</a>
										</div>
									</div>
								</li>
								<li>
								    <?
    							    $user_header = $model->db_query($db, "*", "user", "id = '".$_SESSION['login']."'"); 
    							    ?>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="<?= $config['web']['base_url']; ?>user/<?=$user_header['rows']['username']?>" target="_blank">Profile</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="<?= $config['web']['base_url']; ?>setting/" target="_blank">Account Setting</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="<?= $config['web']['base_url']; ?>administrator/auth/sign-out.php">Logout</a>
								</li>
							</ul>
						</li>
						
					</ul>
				</div>
			</nav>
			<!-- End Navbar -->
		</div>