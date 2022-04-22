<?
require "../../web.php";
require "../../lib/check_session_admin.php";
require "../../lib/is_login.php";

$title = "Bank Pembayaran";
require "../lib/sidebar.php";
require "../lib/header.php";

$bankBayar = mysqli_query($db, "SELECT * FROM bank_information ORDER BY bank ASC");
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
								<li class="breadcrumb-item active" aria-current="page">Bank Pembayaran</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<h6 class="mb-0 text-uppercase">Bank Pembayaran</h6>
				<hr/>
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Bank</th>
                                        <th>Pemilik</th>
                                        <th>No Rekening</th>
                                        <th>Status Orderan</th>
                                        <th>Status Deposit</th>
                                        <th>Aksi</th>
									</tr>
								</thead>
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
                                        if($bankBayar_assoc['deposit'] == '2'){
                                            $status_depo = "Tidak Aktif";
                                            $badge_depo = 'danger';
                                        } else {
                                            $status_depo = "Aktif";
                                            $badge_depo = 'success';
                                        }
                                        ?>  
								    <tr>
										<td><?=ucfirst($bankBayar_assoc['bank'])?></td>
										<td><?=$bankBayar_assoc['nama_pemilik_bank']?></a></td>
                                        <td><?=$bankBayar_assoc['no_rek']?></td>
                                        <td><span class="badge bg-<?=$badge?>"><?=$status?></span></td>
                                        <td><span class="badge bg-<?=$badge_depo?>"><?=$status_depo?></span></td>
										<td><button title="Edit" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?=$bankBayar_assoc['id']?>"><i class="fadeIn animated bx bx-message-edit"></i></button></td>
									</tr>
									
										<div class="modal fade" id="exampleModal<?=$bankBayar_assoc['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
												    <form action="<?= $config['web']['base_url']; ?>administrator/settings/bank-edit.php" method="post" enctype="multipart/form-data">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Edit Bank <?=$bankBayar_assoc['bank']?></h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
												
                                                  
                                                  <div class="modal-body">
                                                      <input type="hidden" name="buid" value="<?=$bankBayar_assoc['id']?>">
                                                    <div class="rating_field">
                                                        <label for="namaBank" class="form-label">Nama Bank</label>
                                                        <input id="namaBank" name="bank" class="form-control mb-3" type="text"   value="<?=ucfirst($bankBayar_assoc['bank'])?>">
                                                    </div>
                                                    <div class="rating_field">
                                                        <label>Status</label>
                                                        <select name="status" class="form-select form-select-sm mb-3" aria-label=".form-select-sm example">
                                                            <option value="<?=$bankBayar_assoc['status']?>"><?=$status?> (saat Ini)</option>
                        									<option value="active">Aktif</option>
                                                            <option value="not-active">Tidak Aktif</option>
                        								</select>
                                                    </div>
                                                    <div class="rating_field">
                                                        <label for="namaPemilik" class="form-label">Nama Pemilik</label>
                                                        <input id="namaPemilik" name="nama_pemilik" class="form-control mb-3" type="text"   value="<?=ucfirst($bankBayar_assoc['nama_pemilik_bank'])?>">
                                                    </div>
                                                    <div class="rating_field">
                                                        <label for="noRek" class="form-label">No Rekening</label>
                                                        <input id="noRek" name="no_rek" class="form-control mb-3" type="text"   value="<?=ucfirst($bankBayar_assoc['no_rek'])?>">
                                                    </div>
                                                    <div class="rating_field">
                                                        <label>Aktifkan Untuk Deposit</label>
                                                        <select name="status_depo" class="form-select form-select-sm mb-3" aria-label=".form-select-sm example">
                                                            <option value="<?=$bankBayar_assoc['deposit']?>"><?=$status_depo?> (saat Ini)</option>
                        									<option value="1">Aktif</option>
                                                            <option value="2">Tidak Aktif</option>
                        								</select>
                                                    </div>
                                                    <div class="rating_field">
                                                        <label for="rateDollar" class="form-label">Rate Dollar</label>
                                                        <input id="rateDollar" name="rate_dollar" class="form-control mb-3" type="text"  placeholder="Jangan Diisi Jika Bukan Paypal/PerfectMoney" value="<?=ucfirst($bankBayar_assoc['rate_dollar'])?>">
                                                    </div>
                                                    <div class="rating_field">
                                                        <div class="form-group">
                                                            <label for="logoBank" class="form-label">Logo Bank</label>
                                                            <div class="upload_wrapper">
                                                                <div class="upload-field">
                                                                    <input id="logoBank" type="file" name="file" />
                                                                </div>
                                                                        <span>(Hanya Menerima Format JPEG, JPG, PNG Max 2MB)</span>
                                                            </div><!-- ends: .upload_wrapper -->
                                                        </div><!-- ends: .form-group -->
                                                     </div> 
                                                  </div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
														<button type="submit" class="btn btn-primary">Save changes</button>
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

<?
require "../lib/footer.php"
?>