<?
require "../../web.php";
require '../../lib/csrf_token.php';
$website = $model->db_query($db, "*", "website", "id = '1'");

$title = "Sign In";
if (isset($_SESSION['login'])) {
	exit(header("Location: ".$config['web']['base_url']."administrator"));
}
?>
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="<?= $config['web']['base_url']; ?>file-photo/website/Logo-04.png" type="image/png" />
	<!--plugins-->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<link href="<?= $config['web']['base_url']; ?>administrator/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="<?= $config['web']['base_url']; ?>administrator/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="<?= $config['web']['base_url']; ?>administrator/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="<?= $config['web']['base_url']; ?>administrator/assets/css/pace.min.css" rel="stylesheet" />
	<script src="<?= $config['web']['base_url']; ?>administrator/assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="<?= $config['web']['base_url']; ?>administrator/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= $config['web']['base_url']; ?>administrator/assets/css/app.css" rel="stylesheet">
	<link href="<?= $config['web']['base_url']; ?>administrator/assets/css/icons.css" rel="stylesheet">
	<title><?=$title?> - <?=$website['rows']['title']?></title>
</head>

<body class="bg-login">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container-fluid">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="mb-4 text-center">
							<img src="<?= $config['web']['base_url']; ?>file-photo/website/Logo-05.png" width="180" alt="" />
						</div>
						<div class="card">
							<div class="card-body">
								<div class="border p-4 rounded">
									<div class="form-body">
									    <form class="row g-3" action="<?= $config['web']['base_url']; ?>administrator/auth/signin-action.php" method ="post">
                                            <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
											<div class="col-12">
												<label for="inputEmailAddress" class="form-label">Email Address</label>
												<input type="email" name="email" class="form-control" id="inputEmailAddress" placeholder="Email Address">
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label">Enter Password</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" name="password" class="form-control border-end-0" id="inputChoosePassword" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-check form-switch">
													<input class="form-check-input" type="checkbox" name="rememberme" id="flexSwitchCheckChecked">
													<label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
												</div>
											</div>
											<div class="col-md-6 text-end">	<a href="<?= $config['web']['base_url']; ?>reset_password/">Forgot Password ?</a>
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
											<div class="col-12">
                            				    <center><div class="g-recaptcha" data-sitekey="<?=$website['rows']['site_key']?>"></div></center>
                            				</div>
											<div class="col-12">
												<div class="d-grid">
													<button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Sign in</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="<?= $config['web']['base_url']; ?>administrator/assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="<?= $config['web']['base_url']; ?>administrator/assets/js/jquery.min.js"></script>
	<script src="<?= $config['web']['base_url']; ?>administrator/assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="<?= $config['web']['base_url']; ?>administrator/assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="<?= $config['web']['base_url']; ?>administrator/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<!--Password show & hide js -->
	<!--google recaptcha-->
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
