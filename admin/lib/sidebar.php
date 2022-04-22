<?
$website = $model->db_query($db, "*", "website", "id = '1'");
$smtp_mail = $model->db_query($db, "*", "smtp", "id = '1'");
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

<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="<?= $config['web']['base_url']; ?>file-photo/website/<?=$website['rows']['fav_icon']?>" type="image/png" />
	<!--plugins-->
	 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<link rel="stylesheet" href="<?= $config['web']['base_url']."administrator/"; ?>assets/plugins/notifications/css/lobibox.min.css" />
	<link href="<?= $config['web']['base_url']."administrator/"; ?>assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="<?= $config['web']['base_url']."administrator/"; ?>assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="<?= $config['web']['base_url']."administrator/"; ?>assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<link href="<?= $config['web']['base_url']."administrator/"; ?>assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="<?= $config['web']['base_url']."administrator/"; ?>assets/css/pace.min.css" rel="stylesheet" />
	<script src="<?= $config['web']['base_url']."administrator/"; ?>assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="<?= $config['web']['base_url']."administrator/"; ?>assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= $config['web']['base_url']."administrator/"; ?>assets/css/app.css" rel="stylesheet">
	<link href="<?= $config['web']['base_url']."administrator/"; ?>assets/css/icons.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="<?= $config['web']['base_url']."administrator/"; ?>assets/css/dark-theme.css" />
	<link rel="stylesheet" href="<?= $config['web']['base_url']."administrator/"; ?>assets/css/semi-dark.css" />
	<link rel="stylesheet" href="<?= $config['web']['base_url']."administrator/"; ?>assets/css/header-colors.css" />
	<title><?= $title;?> - <?=$website['rows']['title']?></title>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<!--<div>-->
				<!--	<img src="<?= $config['web']['base_url']; ?>file-photo/website/Logo-04.png" class="logo-icon" alt="logo icon">-->
				<!--</div>-->
				<div>
					<img src="<?= $config['web']['base_url']; ?>file-photo/website/<?=$website['rows']['logo_web']?>" width="100%" heigt="100%" class="logo-text">
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
				</div>
			</div>
			<!--navigation-->
			<ul class="metismenu" id="menu">
				
				<li>
					<a href="<?= $config['web']['base_url']."administrator/"; ?>">
						<div class="parent-icon"><i class="bx bx-home-circle"></i>
						</div>
						<div class="menu-title">Dashboard</div>
					</a>
				</li>
				<li>
					<a href="<?= $config['web']['base_url']."administrator/users/"; ?>">
						<div class="parent-icon"><i class="bx bx-user-circle"></i>
						</div>
						<div class="menu-title">Users</div>
					</a>
				</li>
				<li>
				<a class="has-arrow" href="javascript:;">
					<div class="parent-icon"><i class='bx bx-cog'></i>
					</div>
					<div class="menu-title">Settings</div>
				</a>
				<ul>
					<li> <a href="<?= $config['web']['base_url']."administrator/settings/"; ?>"><i class="bx bx-right-arrow-alt"></i>Website</a>
					</li>
					<li> <a href="<?= $config['web']['base_url']."administrator/pages/"; ?>"><i class="bx bx-right-arrow-alt"></i>Pages</a>
					</li>
					<li> <a href="<?= $config['web']['base_url']."administrator/category/"; ?>"><i class="bx bx-right-arrow-alt"></i>Category</a>
					</li>
					<li> <a href="<?= $config['web']['base_url']."administrator/bank-pembayaran/"; ?>"><i class="bx bx-right-arrow-alt"></i>Bank Pembayaran</a>
					</li>
					<li> <a href="<?= $config['web']['base_url']."administrator/bank-withdraws/"; ?>"><i class="bx bx-right-arrow-alt"></i>Bank Withdraws</a>
					</li>
				</ul>
				</li>
				<li>
				<a class="has-arrow" href="javascript:;">
					<div class="parent-icon"><i class="lni lni-coin"></i>
					</div>
					<div class="menu-title">Payment</div>
				</a>
				<ul>
					<li> <a href="<?= $config['web']['base_url']."administrator/payment-auto/"; ?>"><i class="bx bx-right-arrow-alt"></i>Automatic Payment</a>
					</li>
					
				</ul>
				</li>
				<li>
					<a href="<?= $config['web']['base_url']."administrator/mail_template/"; ?>">
						<div class="parent-icon"><i class="lni lni-envelope"></i>
						</div>
						<div class="menu-title">Mail Template</div>
					</a>
				</li>
				<li>
				<a class="has-arrow" href="javascript:;">
					<div class="parent-icon"><i class='bx bx-cart'></i>
					</div>
				<?
					if($service_pending['count']+$req_pending['count'] > 0){
					?>
					<div class="menu-title">Product <span class="badge rounded-pill bg-secondary"><?=$service_pending['count']+$req_pending['count']?></div>
					<?
					} else {
					?>
					<div class="menu-title">Product</div>
					<?
					}
				?>
					
				</a>
				<ul>
					<li> 
					    <a href="<?= $config['web']['base_url']."administrator/services/"; ?>"><i class="bx bx-right-arrow-alt"></i>All Product</a>
					</li>
				<?
                    if($service_pending['count'] > 0){
                    ?>
                    <li> 
					    <a href="<?= $config['web']['base_url']."administrator/services-pending/"; ?>"><i class="bx bx-right-arrow-alt"></i>Product Pending <span class="badge rounded-pill bg-secondary"><?=$service_pending['count']?></span></a>
					</li>
                    <?
                    } else {
                    ?>
                    <li> 
					    <a href="<?= $config['web']['base_url']."administrator/services-pending/"; ?>"><i class="bx bx-right-arrow-alt"></i>Product Pending</a>
					</li>
                    <?
                    }
                ?>
					<li> 
					    <a href="<?= $config['web']['base_url']."administrator/request/"; ?>"><i class="bx bx-right-arrow-alt"></i>All Request</a>
					</li>
				<?
                    if($req_pending['count'] > 0){
                    ?>
                    <li> 
                        <a href="<?= $config['web']['base_url']."administrator/request-pending/"; ?>"><i class="bx bx-right-arrow-alt"></i>Request Pending <span class="badge rounded-pill bg-secondary"><?=$req_pending['count']?></span></a>
					</li>
                    <?
                    } else {
                    ?>
                    <li> 
                        <a href="<?= $config['web']['base_url']."administrator/request-pending/"; ?>"><i class="bx bx-right-arrow-alt"></i>Request Pending</a>
					</li>
                    <?
                    }
                ?>
					
				</ul>
				</li>
				<li>
				<a class="has-arrow" href="javascript:;">
					<div class="parent-icon"><i class="fadeIn animated bx bx-money"></i>
					</div>
					<?
				    if($wd_pending['count'] > 0){
				    ?>
					<div class="menu-title">Withdraw <span class="badge rounded-pill bg-secondary"><?=$wd_pending['count']?></div>
				    <?
				    } else {
				    ?>
					<div class="menu-title">Withdraw</div>
				    <?
				    }
				    ?>
				</a>
				<ul>
					<li> 
					    <?
					    if($wd_pending['count'] > 0){
					    ?>
					    <a href="<?= $config['web']['base_url']."administrator/withdraw-request/"; ?>"><i class="bx bx-right-arrow-alt"></i>Request <span class="badge rounded-pill bg-secondary"><?=$wd_pending['count']?></a>
					    <?
					    } else {
					    ?>
					    <a href="<?= $config['web']['base_url']."administrator/withdraw-request/"; ?>"><i class="bx bx-right-arrow-alt"></i>Request</a>
					    <?
					    }
					    ?>
					</li>
					<li> 
					    <a href="<?= $config['web']['base_url']."administrator/withdraw-history/"; ?>"><i class="bx bx-right-arrow-alt"></i>History</a>
					</li>
				</ul>
				</li>
				<li>
					<a href="<?= $config['web']['base_url']."administrator/orders/"; ?>">
						<div class="parent-icon"><i class="bx bx-list-ul"></i>
						</div>
						<div class="menu-title">Orders</div>
					</a>
				</li>
				<li>
					<a href="<?= $config['web']['base_url']."administrator/deposit-history/"; ?>">
						<div class="parent-icon"><i class="fadeIn animated bx bx-money"></i>
						</div>
						<div class="menu-title">Deposits</div>
					</a>
				</li>
				<li>
				<a class="has-arrow" href="javascript:;">
					<div class="parent-icon"><i class="fadeIn animated bx bx-cookie"></i>
					</div>
					<div class="menu-title">Logs</div>
				</a>
				<ul>
					<li> <a href="<?= $config['web']['base_url']."administrator/log-pembayaran/"; ?>"><i class="bx bx-right-arrow-alt"></i>Pembayaran</a>
					</li>
				</ul>
				</li>
				<li>
				<a class="has-arrow" href="javascript:;">
					<div class="parent-icon"><i class="lni lni-service"></i>
					</div>
					<div class="menu-title">Report</div>
				</a>
				<ul>
					<li> <a href="<?= $config['web']['base_url']."administrator/report-chat/"; ?>"><i class="bx bx-right-arrow-alt"></i>Chat</a>
					</li>
				</ul>
				</li>
				<li>
					<a href="<?= $config['web']['base_url']."administrator/user-chats/"; ?>">
						<div class="parent-icon"><i class="fadeIn animated bx bx-message-square-detail"></i>
						</div>
						<div class="menu-title">User Chats</div>
					</a>
				</li>
			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->