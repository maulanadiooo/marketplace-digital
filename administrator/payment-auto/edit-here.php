<?php
require '../../web.php';
require '../../lib/check_session_admin.php';


$title = "Payment Auto";
require '../lib/header.php';
require '../lib/sidebar.php';
$midtrans = $model->db_query($db, "*", "payment_setting", "id = '1'");
$ipaymu = $model->db_query($db, "*", "payment_setting", "id = '2'");
$bca = $model->db_query($db, "*", "payment_setting", "id = '3'");
$cp = $model->db_query($db, "*", "payment_setting", "id = '4'");
?>

<div class="main-panel">
	<div class="content">
		<div class="page-inner">
		<div class="page-header">
				<h4 class="page-title">Settings</h4>
				<ul class="breadcrumbs">
					<li class="nav-home">
						<a href="#">
							<i class="flaticon-home"></i>
						</a>
					</li>
					<li class="separator">
						<i class="flaticon-right-arrow"></i>
					</li>
					<li class="nav-item">
						<a href="#">Automatic Payments</a>
					</li>
				</ul>
			</div>
		
		
		<div class="row">
			<div class="col-xl-7 mx-auto">
				<hr/>
				<div class="card border-top border-0 border-4 border-primary" >
					<form class="row g-3" action="<?= $config['web']['base_url'] ?>administrator/payment-auto/action.php" method="post">
					<div class="card-body p-5" id="show_hide_password1">
					    
					    <p><font color="red">Data dibawah ini bersifat rahasia, telah di enkripsi pada database, sehingga hanya anda yang bisa melihat</font></p>
					    <label for="inputTitle" class="form-label">Klik Untuk Melihat</label>
					    <div class="input-group">
					        <a href="javascript:;" class="input-group-text bg-transparent"><i class='fas fa-eye-slash'></i></a>
					    </div> <br>
						<div class="card-title d-flex align-items-center">
							<div><i class="fas fa-cog font-22 text-primary"></i>
							</div>
							<h5 class="mb-0 text-primary">Midtrans</h5>
						</div>
						<hr>
						    <div class="col-md-12">
								<label for="inputTitle" class="form-label">Server Key</label>
								<div class="input-group">
								<input type="password" class="form-control" id="inputTitle" name="server_key_midtrans" value="<?= decrypt($midtrans['rows']['value_1']); ?>" >
								</div>
							</div>
					</div>
					<div class="card-body p-5" id="show_hide_password1">
						<div class="card-title d-flex align-items-center">
							<div><i class="fas fa-cog font-22 text-primary"></i>
							</div>
							<h5 class="mb-0 text-primary">Ipaymu</h5>
						</div>
						<hr>
						    <div class="col-md-12">
								<label for="inputTitle" class="form-label">Virtual Account</label>
								<div class="input-group" id="show_hide_password2">
								<input type="password" class="form-control" id="inputTitle" name="va_ipaymu" value="<?= decrypt($ipaymu['rows']['value_1']); ?>" >
								</div>
							</div><br>
							<div class="col-md-12">
								<label for="inputTitle" class="form-label">Api Key</label>
								<input type="password" class="form-control" id="inputTitle" name="apikey_ipaymu" value="<?= decrypt($ipaymu['rows']['value_2']); ?>" >
							</div>
					</div>
					<div class="card-body p-5" id="show_hide_password1">
						<div class="card-title d-flex align-items-center">
							<div><i class="fas fa-cog me-1 font-22 text-primary"></i>
							</div>
							<h5 class="mb-0 text-primary">Bank BCA (Internet Banking)</h5>
						</div>
						<hr>
						<div class="col-md-12">
							<label for="inputTitle" class="form-label">User</label>
							<input type="password" class="form-control" id="inputTitle" name="user_bca" value="<?= decrypt($bca['rows']['value_1']); ?>" >
						</div><br>
						<div class="col-md-12">
							<label for="inputTitle" class="form-label">Password</label>
							<input type="password" class="form-control" id="inputTitle" name="password_bca" value="<?= decrypt($bca['rows']['value_2']); ?>" >
						</div>
					</div>
					<div class="card-body p-5" id="show_hide_password1">
						<div class="card-title d-flex align-items-center">
							<div><i class="fas fa-cog me-1 font-22 text-primary"></i>
							</div>
							<h5 class="mb-0 text-primary">Coinpayments</h5>
						</div>
						<hr>
						<div class="col-md-12">
							<label for="inputTitle" class="form-label">Public Key</label>
							<input type="password" class="form-control" id="inputTitle" name="public_key_cp" value="<?= decrypt($cp['rows']['value_1']); ?>" >
						</div><br>
						<div class="col-md-12">
							<label for="inputTitle" class="form-label">Private Key</label>
							<input type="password" class="form-control" id="inputTitle" name="private_key_cp" value="<?= decrypt($cp['rows']['value_2']); ?>" >
						</div>
						<div class="col-md-12">
							<label for="inputTitle" class="form-label">Merchant ID</label>
							<input type="password" class="form-control" id="inputTitle" name="merchant_id" value="<?= decrypt($cp['rows']['value_3']); ?>" >
						</div>
						<div class="col-md-12">
							<label for="inputTitle" class="form-label">IPN Secret</label>
							<input type="password" class="form-control" id="inputTitle" name="ipn_secret" value="<?= decrypt($cp['rows']['value_4']); ?>" >
						</div>
						<div class="col-md-12">
							<label for="inputTitle" class="form-label">Debug Email</label>
							<input type="password" class="form-control" id="inputTitle" name="debug_email" value="<?= decrypt($cp['rows']['value_5']); ?>" >
						</div>
					</div>
					<div class="card-body p-5">
						<div class="card-title d-flex align-items-center">
							<div><i class="fas fa-cog me-1 font-22 text-primary"></i>
							</div>
							<h5 class="mb-0 text-primary">PerfectMoney USD</h5>
						</div>
						<hr>
						 <p>Silahkan Atur Nomer dan Nama Perfectmoney USD dari halaman <a href="<?= $config['web']['base_url'] ?>administrator/bank-pembayaran/" target="_blank">Bank Pembayaran</a></p>
					</div>
					<div class="card-body p-5">
						<div class="card-title d-flex align-items-center">
							<div><i class="fas fa-cog me-1 font-22 text-primary"></i>
							</div>
							<h5 class="mb-0 text-primary">Paypal USD</h5>
						</div>
						<hr>
						<p><font color="red">Agar Paypal Berjalan Dengan Sempurna Lakukan Tahap Dibawah:</font><br>
						- Login Akun Paypal <br>
						- Klik Pengaturan Akun / Pengaturan Rekening <br>
						- Klik Pemberitahuan <br>
						- Klik "perbarui" pada pemberitahuan instan pembayaran <br>
						- Aktifkan Pemberitahuan Instan Pembayaran Dengan Centang "Terima pesan PIP"<br>
						- URL di isi dengan <b><?= $config['web']['base_url'] ?>checkout/paypal/ipn.php</b> <br>
						- Simpan Perubahan
						</p>
						 <p>Silahkan Atur Email dan Nama Paypal USD dari halaman <a href="<?= $config['web']['base_url'] ?>administrator/bank-pembayaran/" target="_blank">Bank Pembayaran</a></p>
					<div class="mb-3">
						<button class="btn btn-primary" type="submit">Save</button>
					</div>
					</div>
					
					</form>
				</div>
				<hr/>
				
			</div>
		</div>
		</div>
		<!--end row-->
		<!--end row-->
	</div>
</div>

<?
require'../lib/footer.php';
?>

<script>
	$(document).ready(function () {
		$("#show_hide_password1 a").on('click', function (event) {
			event.preventDefault();
			if ($('#show_hide_password1 input').attr("type") == "text") {
				$('#show_hide_password1 input').attr('type', 'password');
				$('#show_hide_password1 i').addClass("fa-eye-slash");
				$('#show_hide_password1 i').removeClass("fa-eye");
			} else if ($('#show_hide_password1 input').attr("type") == "password") {
				$('#show_hide_password1 input').attr('type', 'text');
				$('#show_hide_password1 i').removeClass("fa-eye-slash");
				$('#show_hide_password1 i').addClass("fa-eye");
			}
		});
	});
</script>