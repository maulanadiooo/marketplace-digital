<?php

require '../../web.php';
require '../../lib/check_session_admin.php';


$title = "SMTP Mail";
require '../lib/sidebar.php';
require '../lib/header.php';

?>

<div class="page-wrapper">
	<div class="page-content">
		<!--breadcrumb-->
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Settings</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
								</li> 
								<li class="breadcrumb-item active" aria-current="page">SMTP Mail</li>
							</ol>
						</nav>
					</div>
		            
					
				</div>
		<!--end breadcrumb-->
		
		
		<div class="row">
			<div class="col-xl-7 mx-auto">
				<hr/>
				<div class="card border-top border-0 border-4 border-primary">
					<div class="card-body p-5">
						<div class="card-title d-flex align-items-center">
							<div><i class="bx bxs-cog me-1 font-22 text-primary"></i>
							</div>
							<h5 class="mb-0 text-primary">SMTP Mail</h5>
						</div>
						<hr>
						<form class="row g-3" action="<?= $config['web']['base_url'] ?>administrator/settings/smtp-action.php" method="post">
							<div class="col-md-12">
								<label for="inputTitle" class="form-label">Host</label>
								<input type="text" class="form-control" placeholder="smtp.yourdomain.com" id="inputTitle" name="host" value="<?= decrypt($smtp_mail['rows']['host']); ?>" required>
							</div>
							<div class="col-md-12">
								<label for="inputEmail" class="form-label">username</label>
								<input type="text" class="form-control" placeholder="no-reply@yourdomain.com" id="inputEmail" name="username" value="<?= decrypt($smtp_mail['rows']['username']); ?>" required>
							</div>
							
							<div class="col-md-12">
							    <label for="inputEmail" class="form-label">password</label>
									<input type="password" class="form-control" placeholder="password" aria-label="Whatsapp" aria-describedby="inputWA" name="password" value="<?= decrypt($smtp_mail['rows']['password']); ?>">
								
							</div>
							<div class="col-md-12">
							    <label for="inputEmail" class="form-label">Port</label>
									<input type="text" class="form-control" placeholder="465" aria-label="Whatsapp" aria-describedby="inputWA" name="port" value="<?= $smtp_mail['rows']['port']; ?>">
								
							</div>
							<div class="col-md-12">
							    <label for="inputEmail" class="form-label">Reply To</label>
									<input type="text" class="form-control" placeholder="support@yourdomain.com" aria-label="Whatsapp" aria-describedby="inputWA" name="reply_to" value="<?= decrypt($smtp_mail['rows']['reply_to']); ?>">
							</div>
							<div class="col-md-12">
							    <label for="inputEmail" class="form-label">Nama Pada Email</label>
									<input type="text" class="form-control" placeholder="yourdomain.com" aria-label="Whatsapp" aria-describedby="inputWA" name="nama" value="<?= $smtp_mail['rows']['name']; ?>">
								
							</div>
							<div class="mb-3">
								<button class="btn btn-primary" type="submit">Save</button>
							</div>
						</form>
					</div>
				</div>
				<hr/>
				
			</div>
		</div>
		<!--end row-->
		<!--end row-->
	</div>
</div>

<?
require'../lib/footer.php';
?>