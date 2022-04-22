<?php

require '../../web.php';
require '../../lib/check_session_admin.php';


$title = "Website";
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
								<li class="breadcrumb-item active" aria-current="page">Website</li>
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
							<h5 class="mb-0 text-primary">Web Settings</h5>
						</div>
						<hr>
						<form class="row g-3" action="<?= $config['web']['base_url'] ?>administrator/settings/action.php" method="post" enctype="multipart/form-data">
							<div class="col-md-4">
								<label for="inputTitle" class="form-label">Web Title</label>
								<input type="text" class="form-control" id="inputTitle" name="title_website" value="<?= $website['rows']['title']; ?>" required>
							</div>
							<div class="col-md-4">
								<label for="inputEmail" class="form-label">Email Contact</label>
								<input type="email" class="form-control" id="inputEmail" name="email_notifikasi" value="<?= $website['rows']['email_notifikasi']; ?>" required>
							</div>
							
							<div class="col-md-4">
							    <label for="inputEmail" class="form-label">Whatsapp</label>
								<div class="input-group flex-nowrap"> <span class="input-group-text" id="inputWA">+</span>
									<input type="number" class="form-control" placeholder="628...." aria-label="Whatsapp" aria-describedby="inputWA" name="handphone" value="<?= $website['rows']['no_hp']; ?>">
								</div>
							</div>
							<div class="col-12">
								<label for="inputDesripsiWebsite" class="form-label">Desripsi Website</label>
								<textarea class="form-control" id="inputDesripsiWebsite" rows="3" name="deskripsi_website"><?= $website['rows']['description']; ?></textarea>
							</div>
							<div class="col-12">
								<label for="inputKeyWebsite" class="form-label">Keyword Website</label>
								<textarea class="form-control" id="inputKeyWebsite"  rows="5" name="keyword_website"><?= $website['rows']['keyword']; ?></textarea>
							</div>
							<div class="col-md-4">
							<label>Fav Icon Saat Ini</label> <br>
							    <img src="<?= $config['web']['base_url']; ?>file-photo/website/<?= $website['rows']['fav_icon']; ?>" width ="150px" height="150px">    
							</div>
							<div class="mb-3">
							    <label for="inputKeyWebsite" class="form-label">Ganti Fav Icon</label>
								<input type="file" class="form-control" aria-label="file example" name="fav_icon">
							</div>
							<div class="col-md-4">
							<label>Logo Website Saat Ini</label> <br>
							    <img src="<?= $config['web']['base_url']; ?>file-photo/website/<?= $website['rows']['logo_web']; ?>" width ="250px" height="100px">    
							</div>
							<div class="mb-3">
							    <label for="inputKeyWebsite" class="form-label">Ganti Logo Website</label>
								<input type="file" class="form-control" aria-label="file example" name="logo_website">
							</div>
							<div class="col-md-6">
								<label for="inputFeeBuyer" class="form-label">Fee Pembeli (Rp)</label>
								<input type="number" class="form-control" id="inputFeeBuyer" name="admin_fee" value="<?= $website['rows']['admin_fee']; ?>" required>
							</div>
							<div class="col-md-6">
								<label for="inputFeeSeller" class="form-label">Fee Penjual (Rp)</label>
								<input type="text" class="form-control" id="inputFeeSeller" name="admin_fee_seller" value="<?= $website['rows']['admin_fee_seller']; ?>" required>
							</div>
							<div class="col-md-6">
								<label for="inputWD" class="form-label">Minimal Withdraw (Rp)</label>
								<input type="number" class="form-control" id="inputWD" name="min_wd" value="<?=$website['rows']['min_wd']?>" required>
							</div>
							<div class="col-md-6">
								<label for="inputWD" class="form-label">Minimal Deposit (Rp)</label>
								<input type="number" class="form-control" id="inputWD" name="min_depo" value="<?=$website['rows']['min_depo']?>" required>
							</div>
							<div class="col-md-4">
								<label for="inputRO" class="form-label">Review Otomatis</label>
								<select name="review_otomatis" class="form-select mb-3" aria-label="Default select example">
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
							<div class="col-md-4">
								<label for="inputLK" class="form-label">Lama Kliring</label>
								<select name="lama_kliring" class="form-select mb-3" aria-label="Default select example">
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
							<div class="col-md-4">
								<label for="inputEWD" class="form-label">Estimasi Withdraw</label>
								<select name="lama_wd" class="form-select mb-3" aria-label="Default select example">
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