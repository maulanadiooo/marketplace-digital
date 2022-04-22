<?php

require '../../web.php';
require '../../lib/check_session_admin.php';


$title = "Website";

require '../lib/header.php';
require '../lib/sidebar.php';
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
						<a href="#">Website</a>
					</li>
					<li class="separator">
						<i class="flaticon-right-arrow"></i>
					</li>
				</ul>
			</div>
			<div class="col-md-12">
			                <div class="card">
			                    <form  action="<?= $config['web']['base_url'] ?>administrator/settings/action.php" method="post" enctype="multipart/form-data">
								<div class="card-header">
									<div class="card-title">Web Settings</div>
								</div>
								<div class="card-body">
									<div class="form-group">
										<label for="squareInput">Web Title</label>
										<input type="text" class="form-control input-square" id="squareInput" name="title_website" value="<?= $website['rows']['title']; ?>" required>
									</div>
									<div class="form-group">
										<label for="squareInput">Email Contact</label>
										<input type="text" class="form-control input-square" id="squareInput" name="email_notifikasi" value="<?= $website['rows']['email_notifikasi']; ?>" required>
									</div>
									<div class="form-group">
										<label for="basic-url">Whatsapp</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text" id="basic-addon3">+</span>
											</div>
											<input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" name="handphone" value="<?= $website['rows']['no_hp']; ?>">
										</div>
									</div>
									<div class="form-group">
										<label for="comment">Desripsi Website</label>
										<textarea class="form-control" id="comment" rows="5" name="deskripsi_website"><?= $website['rows']['description']; ?></textarea>
									</div>
									<div class="form-group">
										<label for="comment">Keyword Website</label>
										<textarea class="form-control" id="comment" rows="5" name="keyword_website"><?= $website['rows']['keyword']; ?></textarea>
									</div>
									<div class="form-group">
										<img src="<?= $config['web']['base_url']; ?>file-photo/website/<?= $website['rows']['fav_icon']; ?>" width ="150px" height="150px"> 
									</div>
									<div class="form-group">
										<label for="exampleFormControlFile1">Ganti Fav Icon</label>
										<input type="file" class="form-control-file" name="fav_icon" id="exampleFormControlFile1">
									</div>
									<div class="form-group">
										<img src="<?= $config['web']['base_url']; ?>file-photo/website/<?= $website['rows']['logo_web']; ?>" width ="250px" height="100px"> 
									</div>
									<div class="form-group">
										<label for="exampleFormControlFile1">Ganti Logo Website</label>
										<input type="file" class="form-control-file" name="logo_website" id="exampleFormControlFile1">
									</div>
									<div class="form-group">
										<img src="<?= $config['web']['base_url']; ?>file-photo/website/<?= $website['rows']['logo_promotion']; ?>" width ="250px" height="100px"> 
									</div>
									<div class="form-group">
										<label for="exampleFormControlFile1">Ganti Logo Promotion</label>
										<input type="file" class="form-control-file" name="logo_promotion" id="exampleFormControlFile1">
									</div>
									<div class="form-group">
										<label for="squareInput">Url Promotion</label>
										<input type="text" class="form-control input-square" id="squareInput" name="url_promotion" value="<?= $website['rows']['url_promotion']; ?>" required>
									</div>
									<div class="form-group">
										<label for="squareInput">Fee Pembeli (Rp)</label>
										<input type="number" class="form-control input-square" id="squareInput" name="admin_fee" value="<?= $website['rows']['admin_fee']; ?>" required>
									</div>
									<div class="form-group">
										<label for="squareInput">Fee Penjual (%)</label>
										<input type="text" class="form-control input-square" id="squareInput" name="admin_fee_seller" value="<?= $website['rows']['admin_fee_seller']; ?>" required>
									</div>
									<div class="form-group">
										<label for="squareInput">Minimal Withdraw (Rp)</label>
										<input type="number" class="form-control input-square" id="squareInput" name="min_wd" value="<?=$website['rows']['min_wd']?>" required>
									</div>
									<div class="form-group">
										<label for="squareInput">Minimal Deposit (Rp)</label>
										<input type="number" class="form-control input-square" id="squareInput" name="min_depo" value="<?=$website['rows']['min_depo']?>" required>
									</div>
									<div class="form-group">
										<label for="squareSelect">Review Otomatis</label>
										<select name="review_otomatis" class="form-control input-square" id="squareSelect">
											<option value="<?=$website['rows']['review_otomatis']?>"><?=$website['rows']['review_otomatis']?> (Hari)</option>
                                                <?php
                                                for($i = 1; $i <= 10; $i++){
                                                ?>
                                                <option value="<?=$i?>"><?=$i?> (Hari)</option>
                                                <?    
                                                }
                                                ?>
										</select>
									</div>
									<div class="form-group">
										<label for="squareSelect">Estimasi Withdraws</label>
										<select name="lama_wd" class="form-control input-square" id="squareSelect">
											<option value="<?=$website['rows']['lama_wd']?>"><?=$website['rows']['lama_wd']?> (Hari)</option>
                                                <?php
                                                for($i = 1; $i <= 10; $i++){
                                                ?>
                                                <option value="<?=$i?>"><?=$i?> (Hari)</option>
                                                <?    
                                                }
                                                ?>
										</select>
									</div>
									<div class="form-group">
										<label for="squareSelect">Lama Kliring</label>
										<select name="lama_kliring" class="form-control input-square" id="squareSelect">
											<option value="<?=$website['rows']['lama_kliring']?>"><?=$website['rows']['lama_kliring']?> (Hari)</option>
                                                <?php
                                                for($i = 1; $i <= 10; $i++){
                                                ?>
                                                <option value="<?=$i?>"><?=$i?> (Hari)</option>
                                                <?    
                                                }
                                                ?>
										</select>
									</div>
									<div class="form-group">
										<label for="squareSelect">Harga Featured</label>
										<input type="number" class="form-control input-square" id="squareInput" name="harga_featured" value="<?=$website['rows']['harga_fitur_featured']?>" required>
									</div>
									<div class="form-group">
										<label for="squareSelect">Durasi Featured</label>
										<select name="durasi_featured" class="form-control input-square" id="squareSelect">
											<option value="<?=$website['rows']['durasi_fitur_featured']?>"><?=$website['rows']['durasi_fitur_featured']?> (Hari)</option>
                                                <?php
                                                for($i = 1; $i <= 10; $i++){
                                                ?>
                                                <option value="<?=$i?>"><?=$i?> (Hari)</option>
                                                <?    
                                                }
                                                ?>
										</select>
									</div>
									<div class="form-group">
										<label for="squareSelect">Harga Premium</label>
										<input type="number" class="form-control input-square" id="squareInput" name="harga_premium" value="<?=$website['rows']['harga_fitur_premium']?>" required>
									</div>
									<div class="form-group">
										<label for="squareSelect">Lama Kliring</label>
										<select name="durasi_premium" class="form-control input-square" id="squareSelect">
											<option value="<?=$website['rows']['durasi_fitur_premium']?>"><?=$website['rows']['durasi_fitur_premium']?> (Hari)</option>
                                                <?php
                                                for($i = 1; $i <= 10; $i++){
                                                ?>
                                                <option value="<?=$i?>"><?=$i?> (Hari)</option>
                                                <?    
                                                }
                                                ?>
										</select>
									</div>
									<hr />
									<h3>Google Recaptcha</h3>
									<p>Note: Kosongkan Jika Tidak Ingin Menggunakan Recaptcha Google</p>
									<div class="form-group">
										<label for="squareInput">Site Key</label>
										<input type="text" class="form-control input-square" id="squareInput" name="google_sitekey" value="<?= $website['rows']['site_key']; ?>">
									</div>
									<div class="form-group">
										<label for="squareInput">Secret Key</label>
										<input type="text" class="form-control input-square" id="squareInput" name="google_secretkey" value="<?= $website['rows']['secret_key']; ?>">
									</div>
									
									
								</div>
								<div class="card-action">
								    <button class="btn btn-primary" type="submit">Save</button>
								</div>
							    </form>
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
<?php

require '../../web.php';
require '../../lib/check_session_admin.php';


$title = "Website";

require '../lib/header.php';
require '../lib/sidebar.php';
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
						<a href="#">Website</a>
					</li>
					<li class="separator">
						<i class="flaticon-right-arrow"></i>
					</li>
				</ul>
			</div>
			<div class="col-md-12">
			                <div class="card">
			                    <form  action="<?= $config['web']['base_url'] ?>administrator/settings/action.php" method="post" enctype="multipart/form-data">
								<div class="card-header">
									<div class="card-title">Web Settings</div>
								</div>
								<div class="card-body">
									<div class="form-group">
										<label for="squareInput">Web Title</label>
										<input type="text" class="form-control input-square" id="squareInput" name="title_website" value="<?= $website['rows']['title']; ?>" required>
									</div>
									<div class="form-group">
										<label for="squareInput">Email Contact</label>
										<input type="text" class="form-control input-square" id="squareInput" name="email_notifikasi" value="<?= $website['rows']['email_notifikasi']; ?>" required>
									</div>
									<div class="form-group">
										<label for="basic-url">Whatsapp</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text" id="basic-addon3">+</span>
											</div>
											<input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" name="handphone" value="<?= $website['rows']['no_hp']; ?>">
										</div>
									</div>
									<div class="form-group">
										<label for="comment">Desripsi Website</label>
										<textarea class="form-control" id="comment" rows="5" name="deskripsi_website"><?= $website['rows']['description']; ?></textarea>
									</div>
									<div class="form-group">
										<label for="comment">Keyword Website</label>
										<textarea class="form-control" id="comment" rows="5" name="keyword_website"><?= $website['rows']['keyword']; ?></textarea>
									</div>
									<div class="form-group">
										<img src="<?= $config['web']['base_url']; ?>file-photo/website/<?= $website['rows']['fav_icon']; ?>" width ="150px" height="150px"> 
									</div>
									<div class="form-group">
										<label for="exampleFormControlFile1">Ganti Fav Icon</label>
										<input type="file" class="form-control-file" name="fav_icon" id="exampleFormControlFile1">
									</div>
									<div class="form-group">
										<img src="<?= $config['web']['base_url']; ?>file-photo/website/<?= $website['rows']['logo_web']; ?>" width ="250px" height="100px"> 
									</div>
									<div class="form-group">
										<label for="exampleFormControlFile1">Ganti Logo Website</label>
										<input type="file" class="form-control-file" name="logo_website" id="exampleFormControlFile1">
									</div>
									<div class="form-group">
										<img src="<?= $config['web']['base_url']; ?>file-photo/website/<?= $website['rows']['logo_promotion']; ?>" width ="250px" height="100px"> 
									</div>
									<div class="form-group">
										<label for="exampleFormControlFile1">Ganti Logo Promotion</label>
										<input type="file" class="form-control-file" name="logo_promotion" id="exampleFormControlFile1">
									</div>
									<div class="form-group">
										<label for="squareInput">Url Promotion</label>
										<input type="text" class="form-control input-square" id="squareInput" name="url_promotion" value="<?= $website['rows']['url_promotion']; ?>" required>
									</div>
									<div class="form-group">
										<label for="squareInput">Fee Pembeli (Rp)</label>
										<input type="number" class="form-control input-square" id="squareInput" name="admin_fee" value="<?= $website['rows']['admin_fee']; ?>" required>
									</div>
									<div class="form-group">
										<label for="squareInput">Fee Penjual (%)</label>
										<input type="text" class="form-control input-square" id="squareInput" name="admin_fee_seller" value="<?= $website['rows']['admin_fee_seller']; ?>" required>
									</div>
									<div class="form-group">
										<label for="squareInput">Minimal Withdraw (Rp)</label>
										<input type="number" class="form-control input-square" id="squareInput" name="min_wd" value="<?=$website['rows']['min_wd']?>" required>
									</div>
									<div class="form-group">
										<label for="squareInput">Minimal Deposit (Rp)</label>
										<input type="number" class="form-control input-square" id="squareInput" name="min_depo" value="<?=$website['rows']['min_depo']?>" required>
									</div>
									<div class="form-group">
										<label for="squareSelect">Review Otomatis</label>
										<select name="review_otomatis" class="form-control input-square" id="squareSelect">
											<option value="<?=$website['rows']['review_otomatis']?>"><?=$website['rows']['review_otomatis']?> (Hari)</option>
                                                <?php
                                                for($i = 1; $i <= 10; $i++){
                                                ?>
                                                <option value="<?=$i?>"><?=$i?> (Hari)</option>
                                                <?    
                                                }
                                                ?>
										</select>
									</div>
									<div class="form-group">
										<label for="squareSelect">Estimasi Withdraws</label>
										<select name="lama_wd" class="form-control input-square" id="squareSelect">
											<option value="<?=$website['rows']['lama_wd']?>"><?=$website['rows']['lama_wd']?> (Hari)</option>
                                                <?php
                                                for($i = 1; $i <= 10; $i++){
                                                ?>
                                                <option value="<?=$i?>"><?=$i?> (Hari)</option>
                                                <?    
                                                }
                                                ?>
										</select>
									</div>
									<div class="form-group">
										<label for="squareSelect">Lama Kliring</label>
										<select name="lama_kliring" class="form-control input-square" id="squareSelect">
											<option value="<?=$website['rows']['lama_kliring']?>"><?=$website['rows']['lama_kliring']?> (Hari)</option>
                                                <?php
                                                for($i = 1; $i <= 10; $i++){
                                                ?>
                                                <option value="<?=$i?>"><?=$i?> (Hari)</option>
                                                <?    
                                                }
                                                ?>
										</select>
									</div>
									<div class="form-group">
										<label for="squareSelect">Harga Featured</label>
										<input type="number" class="form-control input-square" id="squareInput" name="harga_featured" value="<?=$website['rows']['harga_fitur_featured']?>" required>
									</div>
									<div class="form-group">
										<label for="squareSelect">Durasi Featured</label>
										<select name="durasi_featured" class="form-control input-square" id="squareSelect">
											<option value="<?=$website['rows']['durasi_fitur_featured']?>"><?=$website['rows']['durasi_fitur_featured']?> (Hari)</option>
                                                <?php
                                                for($i = 1; $i <= 10; $i++){
                                                ?>
                                                <option value="<?=$i?>"><?=$i?> (Hari)</option>
                                                <?    
                                                }
                                                ?>
										</select>
									</div>
									<div class="form-group">
										<label for="squareSelect">Harga Premium</label>
										<input type="number" class="form-control input-square" id="squareInput" name="harga_premium" value="<?=$website['rows']['harga_fitur_premium']?>" required>
									</div>
									<div class="form-group">
										<label for="squareSelect">Lama Kliring</label>
										<select name="durasi_premium" class="form-control input-square" id="squareSelect">
											<option value="<?=$website['rows']['durasi_fitur_premium']?>"><?=$website['rows']['durasi_fitur_premium']?> (Hari)</option>
                                                <?php
                                                for($i = 1; $i <= 10; $i++){
                                                ?>
                                                <option value="<?=$i?>"><?=$i?> (Hari)</option>
                                                <?    
                                                }
                                                ?>
										</select>
									</div>
									<hr />
									<h3>Google Recaptcha</h3>
									<p>Note: Kosongkan Jika Tidak Ingin Menggunakan Recaptcha Google</p>
									<div class="form-group">
										<label for="squareInput">Site Key</label>
										<input type="text" class="form-control input-square" id="squareInput" name="google_sitekey" value="<?= $website['rows']['site_key']; ?>">
									</div>
									<div class="form-group">
										<label for="squareInput">Secret Key</label>
										<input type="text" class="form-control input-square" id="squareInput" name="google_secretkey" value="<?= $website['rows']['secret_key']; ?>">
									</div>
									
									
								</div>
								<div class="card-action">
								    <button class="btn btn-primary" type="submit">Save</button>
								</div>
							    </form>
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