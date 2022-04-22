<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Bank Pembayaran";
require "../lib/header.php";
require "../lib/sidebar.php";

$bankBayar = mysqli_query($db, "SELECT * FROM bank_information ORDER BY bank ASC");
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
						<a href="#">Bank Pembayaran</a>
					</li>
				</ul>
			    </div>
				
				<div class="row">
			    <div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title">Bank Pembayaran</h4>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="basic-datatables" class="display table table-striped table-hover" >
											<thead>
												<tr>
            										<th>Bank</th>
                                                    <th>Pemilik</th>
                                                    <th>No Rekening</th>
                                                    <th>Status Orderan</th>
                                                    <th>Status Deposit</th>
                                                    <th>Status Fitur</th>
                                                    <th>Aksi</th>
            									</tr>
											</thead>
											<tfoot>
												<tr>
            										<th>Bank</th>
                                                    <th>Pemilik</th>
                                                    <th>No Rekening</th>
                                                    <th>Status Orderan</th>
                                                    <th>Status Deposit</th>
                                                    <th>Status Fitur</th>
                                                    <th>Aksi</th>
            									</tr>
											</tfoot>
											<tbody>
        								    <?php
                                                while ($bankBayar_assoc = mysqli_fetch_assoc($bankBayar)){
                                                if($bankBayar_assoc['status']=='active'){
                                                    $status = "Aktif";
                                                    $badge = 'success';
                                                } else {
                                                    $status = "Tidak Aktif";
                                                    $badge = 'danger';
                                                }
                                                if($bankBayar_assoc['deposit'] != '1'){
                                                    $status_depo = "Tidak Aktif";
                                                    $badge_depo = 'danger';
                                                } else {
                                                    $status_depo = "Aktif";
                                                    $badge_depo = 'success';
                                                }
                                                 if($bankBayar_assoc['fitur'] != '1'){
                                                    $status_fitur = "Tidak Aktif";
                                                    $badge_fitur = 'danger';
                                                } else {
                                                    $status_fitur = "Aktif";
                                                    $badge_fitur = 'success';
                                                }
                                                ?>  
        								    <tr>
        										<td><?=ucfirst($bankBayar_assoc['bank'])?></td>
        										<td><?=$bankBayar_assoc['nama_pemilik_bank']?></a></td>
                                                <td><?=$bankBayar_assoc['no_rek']?></td>
                                                <td><span class="badge bg-<?=$badge?>"><?=$status?></span></td>
                                                <td><span class="badge bg-<?=$badge_depo?>"><?=$status_depo?></span></td>
                                                <td><span class="badge bg-<?=$badge_fitur?>"><?=$status_fitur?></span></td>
        										<td><button title="Edit" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal<?=$bankBayar_assoc['id']?>"><i class="far fa-edit"></i></button></td>
        									</tr>
        									
        										<div class="modal fade" id="exampleModal<?=$bankBayar_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        											<div class="modal-dialog">
        												<div class="modal-content">
        												    <form action="<?= $config['web']['base_url']; ?>administrator/settings/bank-edit.php" method="post" enctype="multipart/form-data">
        													<div class="modal-header">
        														<h5 class="modal-title" id="exampleModalLabel">Edit Bank <?=$bankBayar_assoc['bank']?></h5>
        														<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        													</div>
        												
                                                          
                                                          <div class="modal-body">
                                                              <input type="hidden" name="buid" value="<?=$bankBayar_assoc['id']?>">
                                                            <div class="rating_field">
                                                                <label for="namaBank" class="form-label">Nama Bank</label>
                                                                <input id="namaBank" name="bank" class="form-control mb-3" type="text"   value="<?=ucfirst($bankBayar_assoc['bank'])?>">
                                                            </div>
                                                            <div class="form-group">
                        										<label for="squareSelect">Status Untuk Pembayaran</label>
                        										<select name="status" class="form-control input-square" aria-label=".form-select-sm example">
                                                                    <option value="<?=$bankBayar_assoc['status']?>"><?=$status?> (saat Ini)</option>
                                									<option value="active">Aktif</option>
                                                                    <option value="not-active">Tidak Aktif</option>
                                								</select>
                        									</div>
                                                            <div class="form-group">
                                                                <label for="namaPemilik" class="form-label">Nama Pemilik</label>
                                                                <input id="namaPemilik" name="nama_pemilik" class="form-control mb-3" type="text"   value="<?=ucfirst($bankBayar_assoc['nama_pemilik_bank'])?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="noRek" class="form-label">No Rekening</label>
                                                                <input id="noRek" name="no_rek" class="form-control mb-3" type="text"   value="<?=ucfirst($bankBayar_assoc['no_rek'])?>">
                                                            </div>
                                                            <div class="form-group">
                        										<label for="squareSelect">Aktifkan Untuk Deposit</label>
                        										<select name="status_depo" class="form-control input-square" id="squareSelect"> 
                        											<option value="<?=$bankBayar_assoc['deposit']?>"><?=$status_depo?> (saat Ini)</option>
                                                                    <option value='1'>Aktif</option>
                                                                    <option value='2'>Tangguhkan</option>
                        										</select>
                        									</div>
                                                            <div class="form-group">
                        										<label for="squareSelect">Aktifkan Untuk Pembayaran Fitur</label>
                        										<select name="status_fitur" class="form-control input-square" id="squareSelect"> 
                        											<option value="<?=$bankBayar_assoc['fitur']?>"><?=$status_fitur?> (saat Ini)</option>
                                                                    <option value='1'>Aktif</option>
                                                                    <option value='2'>Tangguhkan</option>
                        										</select>
                        									</div>
                                                            <div class="rating_field">
                                                                <label for="rateDollar" class="form-label">Rate Dollar</label>
                                                                <input id="rateDollar" name="rate_dollar" class="form-control mb-3" type="text"  placeholder="Jangan Diisi Jika Bukan Paypal/PerfectMoney" value="<?=ucfirst($bankBayar_assoc['rate_dollar'])?>">
                                                            </div>
                                                            <div class="rating_field">
                                                                <div class="form-group">
                                        							    <img src="<?=$config['web']['base_url']?>img/bank/<?=$bankBayar_assoc['icon']?>" width ="100px" height="50px">    
                                        							</div>
                                        							<div></div>
                                                                    <div class="upload_wrapper">
                                                                        <label>Ganti Logo</label>
                                                                        <div class="upload-field">
                                                                            <input id="logoBank" type="file" name="file" />
                                                                        </div>
                                                                                <span>(Hanya Menerima Format JPEG, JPG, PNG Max 2MB)</span>
                                                                    </div><!-- ends: .upload_wrapper -->
                                                                </div><!-- ends: .form-group -->
                                                            </div> 
                                                            <div class="modal-footer">
        														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        														<button type="submit" class="btn btn-primary">Save changes</button>
        													</div>
        													</div>
        													
        													
                                                          </form>
        												</div>
        											</div>
        										</div>
        									<?
        								    }
        								    ?>
        								</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
			        </div>
			      </div>
			</div>
		</div>

<?
require "../lib/footer.php"
?>