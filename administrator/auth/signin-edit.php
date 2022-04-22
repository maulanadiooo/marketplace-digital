<?
require "../../web.php";
require '../../lib/csrf_token.php';
$website = $model->db_query($db, "*", "website", "id = '1'");
if(isset($_COOKIE['token_login'])){
    
    $cookie_token = $_COOKIE['token_login'];
    $lgid = $_COOKIE['lgid'];
    $check_user = $model->db_query($db, "*", "user", "token_login = '$cookie_token' AND role= '2' ");
    if ($check_user['count'] == 1) {
        
        $_SESSION['login'] = $check_user['rows']['id'];

    } else {
		unset($_COOKIE['token_login']);
	    setcookie('token_login', NULL, -1);
	}
     
}
$title = "Sign In";
if (isset($_SESSION['login']) && $model->db_query($db, "*", "user", "id = '".$_SESSION['login']."' AND role = '2'")['count'] == 1) {
	exit(header("Location: ".$config['web']['base_url']."administrator"));
}
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
<body class="login">
	<div class="wrapper wrapper-login">
		<div class="container container-login animated fadeIn">
			<center><img src="<?= $config['web']['base_url']; ?>file-photo/website/<?=$website['rows']['logo_web']?>" width="230" height="50" alt="<?=$website['rows']['title']?>" /></center>
		<form action="<?= $config['web']['base_url']; ?>administrator/auth/signin-action.php" method ="post">
			<div class="login-form">
				<div class="form-group form-floating-label">
					<input id="username" name="email" type="email" class="form-control input-border-bottom" required>
					<label for="username" class="placeholder">Email</label>
				</div>
				<div class="form-group form-floating-label">
					<input id="password" name="password" type="password" class="form-control input-border-bottom" required>
					<label for="password" class="placeholder">Password</label>
					<div class="show-password">
						<i class="flaticon-interface"></i>
					</div>
				</div>
				 <?
                if($website['rows']['site_key'] != null){
                ?>
                <div class="col-12">
				    <center><div class="g-recaptcha" data-sitekey="<?=$website['rows']['site_key']?>"></div></center>
				</div>
                <?
                }
                ?>
				<div class="row form-sub m-0">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" id="rememberme" name="rememberme">
						<label class="custom-control-label" for="rememberme">Remember Me</label>
					</div>
					
					<a href="<?= $config['web']['base_url']; ?>reset_password/" class="link float-right">Forget Password ?</a>
				</div>
				<div class="form-action mb-3">
				    <button type="submit" class="btn btn-primary btn-rounded btn-login">Sign in</button>
				</div>
			</div>
			</form>
		</div>
	</div>
	<script src="<?= $config['web']['base_url']."administrator/"; ?>assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="<?= $config['web']['base_url']."administrator/"; ?>assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="<?= $config['web']['base_url']."administrator/"; ?>assets/js/core/popper.min.js"></script>
	<script src="<?= $config['web']['base_url']."administrator/"; ?>assets/js/core/bootstrap.min.js"></script>
	<script src="<?= $config['web']['base_url']."administrator/"; ?>assets/js/ready.js"></script>
</body>
</html>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
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
